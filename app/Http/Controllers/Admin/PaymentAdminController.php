<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentAdminController extends Controller
{
    public function index(){
        $models = Payment::paginate(5);
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Payment/Index', [
            'models'        => $models,
        ]);
        }else{
            return 'You dont Allowed This Page!';
        }
    }

    public function create(){
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Payment/Create', [
            'form_type'     => 'POST',
            'title'         => 'Create Payment',
            'model'         => [],
            'route_url'     => route('payments.store'),
        ]);
        }else{
            return 'You dont Allowed This Page!';
        }
    }

    public function store(Request $request){
        $request->validate([
            'name'          => 'required',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index');
    }

    public function edit(Payment $payment){
        if(Auth::user()->level == 'admin'){
        return Inertia::render('Payment/Create', [
            'form_type'     => 'PUT',
            'title'         => 'Edit Payment',
            'model'         => $payment,
            'route_url'     => route('payments.update', $payment->id),
        ]);
        }else{
            return 'You dont Allowed This Page!';
        }
    }

    public function update(Request $request, Payment $payment){
        $request->validate([
            'name'      => 'required',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index');
    }

    public function destroy(Payment $payment){
        $payment->delete();

        return redirect()->route('payments.index');
    }
}
