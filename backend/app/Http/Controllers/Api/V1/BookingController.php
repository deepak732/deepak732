<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'before:check_out'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if ($validated['guests'] > $room->capacity) {
            return response()->json(['message' => 'Guest count exceeds room capacity.'], 422);
        }

        $overlap = Booking::where('room_id', $room->id)
            ->where(function ($q) use ($validated) {
                $q->where('check_in', '<', $validated['check_out'])
                  ->where('check_out', '>', $validated['check_in']);
            })->exists();

        if ($overlap) {
            return response()->json(['message' => 'Room is not available for selected dates.'], 409);
        }

        $nights = (new \DateTime($validated['check_in']))->diff(new \DateTime($validated['check_out']))->days;
        if ($nights <= 0) {
            return response()->json(['message' => 'Invalid date range.'], 422);
        }
        $total = $nights * (float) $room->price_per_night;

        $booking = Booking::create([
            'room_id' => $room->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests' => $validated['guests'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'total_price' => $total,
            'status' => 'confirmed',
        ]);

        return response()->json($booking->load('room.hotel'), 201);
    }
}

