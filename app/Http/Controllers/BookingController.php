<?php

namespace App\Http\Controllers;

use App\Facades\General;
use App\Http\Requests\CreateBookRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Throwable;

class BookingController extends Controller
{
    /**
     * Booking the boat's seat
     *
     * @param CreateBookRequest $request
     * @return JsonResponse
     */
    public function create(CreateBookRequest $request)
    {
        try {
            // Get the request query parameters
            $date = Carbon::createFromFormat(General::DATE_FORMAT, $request->get('date'))->toDateString();
            $numOfGuests = $request->get('numOfGuests');

            // Get the bookings from cache storage
            $bookingCollections = Cache::get(General::CACHE_KEY);

            // If the booking data are not available
            // Initialize the booking collection
            if (is_null($bookingCollections)) {
                $bookingCollections = collect();
            }

            // check the requested booking date existed or not in the collection
            $checkBookDateExist = $bookingCollections->where('date', $date);

            if ($checkBookDateExist->count() > 0) {
                $bookItem = $checkBookDateExist->first();

                // Check the requested guests with maximum number of guests limit
                // If requested guests are more than limit then throw validation error
                if (((int)$bookItem['numOfGuests'] + (int)$numOfGuests) > General::GUEST_LIMIT) {

                    $availableSeats = General::GUEST_LIMIT - (int)$bookItem['numOfGuests'];
                    $errorMessage = $availableSeats > 0 ?
                        __('messages.available_seat', ['availableSeats' => $availableSeats]) :
                        __('messages.unavailable_seat');

                    return response()->json([
                        'success' => false,
                        'data'    => [
                            'numOfGuests' => [$errorMessage]
                        ],
                        'message' => __('messages.validation_errors')
                    ], 200);
                }

                // Update the num of guests into booking collections for the requested date
                $bookingCollections = $bookingCollections->map(function ($item, $key) use ($date, $numOfGuests) {
                    if ($item['date'] == $date) {
                        $item['numOfGuests'] = (int)$item['numOfGuests'] + $numOfGuests;
                    }

                    return $item;
                });

            } else {
                // Add date with num of guests into booking collections
                $bookingCollections->push([
                    'date'        => $date,
                    'numOfGuests' => (int)$numOfGuests
                ]);
            }

            // Update the booking collections into the Cache storage
            Cache::forever(General::CACHE_KEY, $bookingCollections);

            return response()->json([
                'success' => true,
                'data'    => $request->all(),
                'message' => __('messages.success_booked')
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'data'    => $e->getMessage(),
                'message' => __('messages.error_in_book')
            ], 500);
        }
    }

    /**
     * Get the list of bookings
     *
     * @return JsonResponse
     */
    public function read()
    {
        try {
            // Get the bookings from cache storage
            $bookingCollections = Cache::get(General::CACHE_KEY);

            $mappedCollections = collect();

            // Convert trip dates as key and number of booked guests as value
            if (!is_null($bookingCollections)) {
                $mappedCollections = $bookingCollections->mapWithKeys(function ($item) {
                    $key = Carbon::createFromFormat('Y-m-d', $item['date'])->format(General::DATE_FORMAT);
                    return [$key => $item['numOfGuests']];
                });
            }

            return response()->json([
                'success' => true,
                'data'    => $mappedCollections->toArray(),
                'message' => __('messages.success_fetched')
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'data'    => $e->getMessage(),
                'message' => __('messages.error_in_fetch')
            ], 500);
        }
    }
}
