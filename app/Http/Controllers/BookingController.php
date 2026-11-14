<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Extra;
use App\Models\Service;
use App\Notifications\BookingNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        return view('booking.create', [
            'services' => Service::active()->orderBy('sort_order')->get(),
            'extras' => Extra::where('is_active', true)->get(),
            'selectedService' => $request->query('service'),
            'defaults' => $request->only(['date', 'time', 'hours', 'guest_count', 'extra_ids']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
            'hours' => ['required', 'integer', 'between:1,12'],
            'guest_count' => ['required', 'integer', 'between:1,100'],
            'extra_ids' => ['nullable', 'array', 'max:20'],
            'extra_ids.*' => ['integer', 'distinct', 'exists:extras,id'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $start = Carbon::createFromFormat('!Y-m-d H:i', $data['date'].' '.$data['time'], config('app.timezone'));
        $end = $start->copy()->addHours((int) $data['hours']);
        if ($start->lessThan(now()->addMinutes(30))) {
            throw ValidationException::withMessages(['time' => 'Бронирование доступно не ранее чем за 30 минут до начала.']);
        }
        if ((int) $start->format('H') < 9 || ! $start->isSameDay($end) || $end->greaterThan($start->copy()->setTime(23, 0))) {
            // Время работы с 09:00 до 23:00 в день посещения.
            throw ValidationException::withMessages(['time' => 'Выберите время с 09:00 до 23:00 в пределах одного дня.']);
        }
        $booking = DB::transaction(function () use ($data, $start, $end, $request) {
            $service = Service::query()->whereKey($data['service_id'])->where('is_active', true)->lockForUpdate()->firstOrFail();
            if ($data['guest_count'] > $service->capacity) {
                throw ValidationException::withMessages(['guest_count' => 'Максимум гостей для этой услуги: '.$service->capacity]);
            }
            $occupied = Booking::where('service_id', $service->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('start_at', '<', $end)
                ->where('end_at', '>', $start)
                ->exists();
            if ($occupied) {
                throw ValidationException::withMessages(['time' => 'Выбранное время уже занято. Попробуйте другие часы или дату.']);
            }
            $ids = $data['extra_ids'] ?? [];
            $extras = Extra::where('is_active', true)->whereIn('id', $ids)->get();
            if ($extras->count() !== count($ids)) {
                throw ValidationException::withMessages(['extra_ids' => 'В списке есть недоступные дополнительные услуги.']);
            }
            $price = $service->price_per_hour * $data['hours'] + $extras->sum('price');
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'service_id' => $service->id,
                'start_at' => $start,
                'end_at' => $end,
                'guest_count' => $data['guest_count'],
                'status' => 'pending',
                'total_price' => $price,
                'comment' => $data['comment'] ?? null,
            ]);
            foreach ($extras as $extra) {
                $booking->extras()->attach($extra->id, ['price_at_booking' => $extra->price]);
            }
            return $booking;
        });
        $request->user()->notify(new BookingNotification($booking, 'Создана заявка на бронирование'));
        return redirect()->route('account.index')->with('success', 'Заявка №'.$booking->id.' создана! Ожидайте подтверждения администратора.');
    }
}
