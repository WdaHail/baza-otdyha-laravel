<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('account.index', [
            'bookings' => $user->bookings()->with(['service', 'extras', 'review'])->latest()->paginate(10),
            'notifications' => $user->notifications()->latest()->limit(10)->get(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'current_password' => ['required_with:password', 'current_password'],
        ]);
        $request->user()->update(array_filter([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'] ?? null,
        ], fn ($value, $key) => $key !== 'password' || filled($value), ARRAY_FILTER_USE_BOTH));
        return back()->with('success', 'Профиль сохранён.');
    }

    public function readNotifications(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', 'Уведомления отмечены как прочитанные.');
    }
}
