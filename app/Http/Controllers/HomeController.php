<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Extra;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'services' => Service::active()->orderBy('sort_order')->take(3)->get(),
            'reviews' => Review::published()->with('user')->latest()->take(3)->get(),
            'allServices' => Service::active()->orderBy('sort_order')->get(),
            'extras' => Extra::where('is_active', true)->get(),
        ]);
    }

    public function about() { return view('about'); }

    public function services()
    {
        return view('services.index', ['services' => Service::active()->orderBy('sort_order')->paginate(9)]);
    }

    public function service(Service $service)
    {
        abort_unless($service->is_active, 404);
        return view('services.show', compact('service'));
    }

    public function reviews()
    {
        return view('reviews.index', ['reviews' => Review::published()->with('user')->latest()->paginate(9)]);
    }

    public function contacts() { return view('contacts'); }

    public function feedback(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:255', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:30', 'required_without:email'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);
        Feedback::create($data);
        return back()->with('success', 'Сообщение отправлено. Мы скоро с вами свяжемся!');
    }
}
