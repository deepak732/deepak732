<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->string('city')->toString();
        $guests = (int) $request->integer('guests', 1);
        $minPrice = $request->has('min_price') ? (float) $request->input('min_price') : null;
        $maxPrice = $request->has('max_price') ? (float) $request->input('max_price') : null;
        $amenities = $request->input('amenities'); // comma-separated IDs or names
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');

        $hotels = Hotel::query()
            ->when($city, fn (Builder $q) => $q->where('city', 'like', "%$city%"))
            ->with(['rooms' => function ($q) use ($guests, $minPrice, $maxPrice, $amenities, $checkIn, $checkOut) {
                $q->when($guests, fn (Builder $rq) => $rq->where('capacity', '>=', $guests))
                  ->when($minPrice !== null, fn (Builder $rq) => $rq->where('price_per_night', '>=', $minPrice))
                  ->when($maxPrice !== null, fn (Builder $rq) => $rq->where('price_per_night', '<=', $maxPrice))
                  ->when($amenities, function (Builder $rq) use ($amenities) {
                      $amenityList = is_array($amenities) ? $amenities : array_filter(array_map('trim', explode(',', (string) $amenities)));
                      if (!empty($amenityList)) {
                          $rq->whereHas('amenities', function (Builder $aq) use ($amenityList) {
                              $aq->whereIn('amenities.id', $amenityList)
                                 ->orWhereIn('amenities.name', $amenityList);
                          });
                      }
                  })
                  ->when($checkIn && $checkOut, function (Builder $rq) use ($checkIn, $checkOut) {
                      $rq->whereDoesntHave('bookings', function (Builder $bq) use ($checkIn, $checkOut) {
                          $bq->where(function (Builder $overlap) use ($checkIn, $checkOut) {
                              $overlap->where('check_in', '<', $checkOut)
                                      ->where('check_out', '>', $checkIn);
                          });
                      });
                  });
            }])
            ->get();

        return response()->json($hotels);
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms.amenities']);
        return response()->json($hotel);
    }
}

