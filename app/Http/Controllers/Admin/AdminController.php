<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Extra;
use App\Models\Feedback;
use App\Models\Review;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\BookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'bookingsCount' => Booking::count(),
            'pendingCount' => Booking::where('status', 'pending')->count(),
            'usersCount' => User::count(),
            'reviewsCount' => Review::where('status', 'pending')->count(),
            'recentBookings' => Booking::with(['user', 'service'])->latest()->limit(7)->get(),
        ]);
    }

    public function bookings(Request $request)
    {
        $status = $request->query('status');
        $query = Booking::with(['user', 'service', 'extras']);
        if (in_array($status, ['pending', 'confirmed', 'cancelled'], true)) $query->where('status', $status);
        $date = $request->query('date');
        if (is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) $query->whereDate('start_at', $date);
        $month = $request->query('month');
        $monthStart = is_string($month) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)
            ? Carbon::createFromFormat('!Y-m', $month, config('app.timezone'))->startOfMonth()
            : now()->startOfMonth();
        $calendar = Booking::whereBetween('start_at', [$monthStart->copy()->startOfDay(), $monthStart->copy()->endOfMonth()->endOfDay()])
            ->get(['start_at', 'status'])->groupBy(fn ($booking) => $booking->start_at->toDateString())
            ->map(fn ($items) => $items->count());
        return view('admin.bookings.index', [
            'bookings' => $query->orderBy('start_at', 'desc')->paginate(20)->withQueryString(),
            'monthStart' => $monthStart, 'calendar' => $calendar,
        ]);
    }

    public function bookingStatus(Request $request, Booking $booking)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])]]);
        if ($data['status'] !== 'cancelled' && $booking->status === 'cancelled') {
            return back()->withErrors(['status' => 'Отменённую бронь нельзя восстановить. Создайте новую заявку.']);
        }
        if ($data['status'] === 'confirmed') {
            $busy = Booking::where('service_id', $booking->service_id)->whereKeyNot($booking->id)
                ->where('status', 'confirmed')->where('start_at', '<', $booking->end_at)
                ->where('end_at', '>', $booking->start_at)->exists();
            if ($busy) return back()->withErrors(['status' => 'Нельзя подтвердить: пересекается с другой подтверждённой бронью.']);
        }
        if ($booking->status !== $data['status']) {
            $booking->update(['status' => $data['status']]);
            $names = ['pending' => 'Ожидает подтверждения', 'confirmed' => 'Подтверждено', 'cancelled' => 'Отменено'];
            $booking->user->notify(new BookingNotification($booking, 'Бронирование №'.$booking->id.': '.$names[$booking->status]));
        }
        return back()->with('success', 'Статус бронирования обновлён.');
    }

    public function reviews() { return view('admin.reviews.index', ['reviews' => Review::with('user')->latest()->paginate(20)]); }

    public function reviewStatus(Request $request, Review $review)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'published', 'rejected'])]]);
        $review->update($data);
        return back()->with('success', 'Статус отзыва изменён.');
    }

    public function services() { return view('admin.services.index', ['services' => Service::orderBy('sort_order')->paginate(20)]); }
    public function serviceCreate() { return view('admin.services.form', ['service' => new Service()]); }
    public function serviceEdit(Service $service) { return view('admin.services.form', compact('service')); }

    private function serviceData(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'price_per_hour' => ['required', 'integer', 'between:0,10000000'],
            'capacity' => ['required', 'integer', 'between:1,1000'],
            'cover_url' => ['nullable', 'url', 'max:500', 'starts_with:https://'],
            'sort_order' => ['required', 'integer', 'between:0,9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug'] = $service?->slug ?? (Str::slug($data['name']) ?: 'service').'-'.Str::lower(Str::random(5));
        return $data;
    }

    public function serviceStore(Request $request)
    {
        Service::create($this->serviceData($request));
        return redirect()->route('admin.services.index')->with('success', 'Услуга создана.');
    }
    public function serviceUpdate(Request $request, Service $service)
    {
        $service->update($this->serviceData($request, $service));
        return redirect()->route('admin.services.index')->with('success', 'Услуга сохранена.');
    }
    public function serviceDestroy(Service $service)
    {
        if ($service->bookings()->exists()) {
            $service->update(['is_active' => false]);
            return back()->with('success', 'У услуги есть бронирования, поэтому она архивирована и скрыта с сайта.');
        }
        $service->delete();
        return back()->with('success', 'Услуга удалена.');
    }

    public function extras() { return view('admin.extras.index', ['extras' => Extra::orderBy('id')->get()]); }
    private function extraData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'integer', 'between:0,10000000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
    public function extraStore(Request $request)
    {
        Extra::create($this->extraData($request));
        return back()->with('success', 'Дополнительная услуга создана.');
    }
    public function extraUpdate(Request $request, Extra $extra)
    {
        $extra->update($this->extraData($request));
        return back()->with('success', 'Дополнительная услуга сохранена.');
    }
    public function extraDestroy(Extra $extra)
    {
        if ($extra->bookings()->exists()) {
            $extra->update(['is_active' => false]);
            return back()->with('success', 'Услуга используется в бронированиях и скрыта из каталога.');
        }
        $extra->delete();
        return back()->with('success', 'Дополнительная услуга удалена.');
    }

    public function users() { return view('admin.users.index', ['users' => User::latest()->paginate(20)]); }
    public function userRole(Request $request, User $user)
    {
        $data = $request->validate(['role' => ['required', Rule::in(['admin', 'manager', 'user'])]]);
        if ($user->isAdmin() && $data['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            throw ValidationException::withMessages(['role' => 'Нельзя удалить роль у последнего администратора.']);
        }
        $user->update($data);
        return back()->with('success', 'Роль пользователя обновлена.');
    }

    public function settings()
    {
        return view('admin.settings.index', ['values' => SiteSetting::pluck('value', 'key')->all()]);
    }
    public function settingsUpdate(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'map_url' => ['nullable', 'url', 'max:500', 'starts_with:https://'],
            'vk_url' => ['nullable', 'url', 'max:500', 'starts_with:https://'],
            'telegram_url' => ['nullable', 'url', 'max:500', 'starts_with:https://'],
            'hero_title' => ['required', 'string', 'max:150'],
            'hero_subtitle' => ['required', 'string', 'max:500'],
            'about_text' => ['required', 'string', 'max:3000'],
            'about_history' => ['required', 'string', 'max:5000'],
            'about_rules' => ['required', 'string', 'max:5000'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'developers' => ['nullable', 'string', 'max:255'],
        ]);
        foreach ($data as $key => $value) SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
        return back()->with('success', 'Настройки обновлены.');
    }

    public function feedback() { return view('admin.feedback.index', ['messages' => Feedback::latest()->paginate(20)]); }
    public function feedbackUpdate(Feedback $feedback)
    {
        $feedback->update(['is_processed' => true]);
        return back()->with('success', 'Обращение отмечено обработанным.');
    }
}
