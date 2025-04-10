<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\RequestPay;
use App\Models\PayGate;
use App\Models\Reservation;

class PayCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->all();
        unset($data['id']);
        if($pg = PayGate::updateOrCreate(["external_id" => $data['external_id']], $data)) {
            // dd($pg);
            if($rp = RequestPay::where('external_id', $pg->external_id)->first()) {
                // dd($rp);
                $rp->load(['reservation', 'order']);
                // dd($rp);
                if($r = Reservation::find($rp->reservation->id ?? "x")) {
                    // dd($r);
                    $r->status_pay = "Paid";
                    $r->save();
                }
                // dd($rp);
                if($o = Order::find($rp->order->id ?? "x")) {
                    // dd($o);
                    $o->status_pay = "Paid";
                    $o->save();
                }
            }
        }
    }
}