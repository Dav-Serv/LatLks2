<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            if($rp = RequestPay::where('external_id', $pg->external_id)->first()) {
                $rp->load(['reservation']);
                if($r = Reservation::find($rp->reservation->id)) {
                    $r->status = "Paid";
                    $r->save();
                }
            }
        }
    }
}
