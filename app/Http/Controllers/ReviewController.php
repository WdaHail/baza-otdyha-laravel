<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:15', 'max:2000'],
        ]);
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($data['booking_id']);
        if (! $booking->canBeReviewed()) {
            throw ValidationException::withMessages(['booking_id' => 'Отзыв доступен после завершения подтверждённого посещения (один на бронирование).']);
        }
        Review::create([
            'user_id' => $request->user()->id,
            'booking_id' => $booking->id,
            'rating' => $data['rating'],
            'body' => $data['body'],
            'status' => 'pending',
        ]);
        return redirect()->route('account.index')->with('success', 'Спасибо! Ваш отзыв отправлен на модерацию.');
    }
}
