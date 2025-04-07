<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use App\Models\Table;
use App\Models\RequestPay;
use Carbon\Carbon;

class ReservationUserController extends Controller
{
    public function index(){
        if (!in_array(Auth::user()->level, ['admin', 'user'])) {
            abort(401); 
        }

        $models = Reservation::with('user', 'table')->paginate(5);

        if(Auth::user()->level == 'user'){
            $reser = Reservation::with('user', 'table')->where('user_id', Auth::user()->id)->paginate(5);
        }
        
        return Inertia::render('Reservation/Index', [
            'level'         => Auth::user()->level,
            'models'         => isset($reser) ? $reser : $models,
        ]);
    }

    public function create(){
        if (!in_array(Auth::user()->level, ['admin', 'user'])) {
            abort(401); 
        }

        $tables = Table::all();
        return Inertia::render('Reservation/Create', [
            'level'         => Auth::user()->level,
            'tables'        => $tables,
            'form_type'     => 'POST',
            'title'         => 'Create Reservation',
            'model'         => [],
            'route_url'     => route('reservations.store'),
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name'          => 'required|string:255',
            'email'         => 'required|email',
            'telephone'     => 'required',
            'guests'        => 'required|numeric',
            'date'          => 'required',
            'table_id'      => 'required|exists:tables,id',
        ]);
        $request->merge([
            'date' => Carbon::parse($request->input('date'))->setTimezone("Asia/Jakarta")->format('Y-m-d H:i:s')
        ]);
        
        // dd($request->all());
        Reservation::create([
            'user_id'       => Auth::user()->id,
            'name'          => $request->name,
            'email'         => $request->email,
            'telephone'     => $request->telephone,
            'guests'        => $request->guests,
            'date'          => $request->date,
            'table_id'      => $request->table_id,
        ]);

        return redirect()->route('reservations.index');         
    }

    public function show($reservation){
        $models = Reservation::with('user', 'table')->findOrFail($reservation);

        return Inertia::render('Reservation/Show', [
            'models'        => $models,
        ]);
    }

    public function reqPay(Request $request, Reservation $reservation) {
        $key = env("XENDIT", "");
        $reservation->load(['table']);
        // dd($key, $reservation);
        $response = Http::withBasicAuth($key, '')
            ->post("https://api.xendit.co/v2/invoices", [
                "external_id"   => "invoice-{$reservation->id}",
                "amount"        => $reservation->table->price,
                "payer_email"   => $reservation->email,
                "description"   => "Invoice #{$reservation->id}"
            ]);

        if($response->successful()) {
            $data = $response->object();

            // dd($data);
            RequestPay::updateOrCreate([
                'external_id' => $data->external_id
            ], [
                'external_id'                   => $data->external_id,
                'user_id'                       => $data->user_id,
                'status'                        => $data->status,
                'merchant_name'                 => $data->merchant_name,
                'merchant_profile_picture_url'  => $data->merchant_profile_picture_url,
                'amount'                        => $data->amount,
                'payer_email'                   => $data->payer_email,
                'description'                   => $data->description,
                'expiry_date'                   => $data->expiry_date,
                'invoice_url'                   => $data->invoice_url,
                'reservation_id'                => $data->reservation_id,
            ]);
        }
    }
}
