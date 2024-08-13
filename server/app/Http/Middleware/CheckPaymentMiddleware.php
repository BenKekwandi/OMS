<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Orders;
use App\Models\Payment;
use App\Models\Invoice;

class CheckPaymentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $orders = Orders::whereNotIn('status', [1, 2, 3, 4, 5, 9, 10, 11, 12, 13])->get();


        foreach ($orders as $order) {
            $invoice = Invoice::where('order_id', $order->id)->first();
            if ($invoice->is_paid === false) {
                $order->status = 5;
                $order->save();
            }
           
        }

        return $next($request);
    }

}
