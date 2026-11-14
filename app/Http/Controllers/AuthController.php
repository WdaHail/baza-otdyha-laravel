<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Неверный email или пароль.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended(route('account.index'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        $data['role'] = 'user'; // Никогда не берём роль из формы регистрации.
        $user = User::create($data);
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('account.index')->with('success', 'Добро пожаловать! Вы зарегистрированы.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
