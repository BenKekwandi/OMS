<?php

namespace App\Http\Middleware;

use App\Models\Offers;
use App\Models\Orders;
use Carbon\Carbon;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Expiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $offers = Offers::all();
        $orders = Orders::all();
        $currentDate = Carbon::now();

        foreach ($offers as $offer) {

            $creationDate = Carbon::parse($offer->created_at);
            $numberOfDays = $currentDate->diffInDays($creationDate);
            if ($numberOfDays >= 14) {
                $offer->update(['status' => 4]);
            }
        }

        $currentDateTimestamp = strtotime(date('Y-m-d'));

        foreach ($orders as $order) {
            $deadlineDate = new Datetime($order->deadline);
            $deadlineDateTimestamp = strtotime($deadlineDate->format('Y-m-d'));

            if ($deadlineDateTimestamp < $currentDateTimestamp) {
                $order->update(['status' => 13]);
            }

        }

        return $next($request);
    }
}
