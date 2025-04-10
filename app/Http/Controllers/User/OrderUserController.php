<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\RequestPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\table;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class OrderUserController extends Controller
{
    public function index(){
        if (!in_array(Auth::user()->level, ['admin', 'user'])) {
            abort(401); 
        }

        $models = Order::with('user', 'table', 'product')->paginate(5);
        
        if(Auth::user()->level == 'user'){
            $order = Order::with('user', 'table', 'product')->where('user_id', Auth::user()->id)->paginate(5);
        }
        
        return Inertia::render('Order/Index', [
            'level'         => Auth::user()->level,
            'models'         => isset($order) ? $order : $models,
        ]);
    }

    public function create(){
        if (!in_array(Auth::user()->level, ['admin', 'user'])) {
            abort(401); 
        }

        $tables = Table::where('status', 'active')->get();
        $products = Product::where('stock', '>', 0)->get();
        return Inertia::render('Order/Create', [
            'level'         => Auth::user()->level,
            'tables'        => $tables,
            'products'      => $products,
            'form_type'     => 'POST',
            'title'         => 'Create Order',
            'model'         => [],
            'route_url'     => route('orders.store'),
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name'          => 'required|string|max:255',
            'table_id'      => 'required|exists:tables,id',
            'guests'        => 'required|integer',
            'product_id'    => 'required|exists:products,id',
            'count'         => 'required|integer',
        ]);
        
        $product = Product::find($request->product_id);
    
        // Cek apakah produk ditemukan
        if (!$product) {
            return redirect()->route('orders.index')->withErrors('Product Not Found');
        }

        $subtotal = $product->price * $request->count;
        // dd($subtotal);
        Order::create([
            'user_id'       => Auth::user()->id,
            'name'          => $request->name,
            'table_id'      => $request->table_id,
            'guests'        => $request->guests,
            'product_id'    => $request->product_id,
            'count'         => $request->count,
            'subtotal'      => $subtotal,
            'date'          => Carbon::now()->setTimezone("Asia/Jakarta")->format('Y-m-d H:i:s'),
        ]);

        if($subtraction = Product::find($request->product_id)) {
            // dd($table->status);
            $subtraction->stock -= $request->count;
            $subtraction->save();
            // dd($subtraction);
        }

        if($table = Table::find($request->table_id)) {
            // dd($table->status);
            $table->status = 'inactive';
            $table->save();
            // dd($table);
        }
        
        // dd($order);
        return redirect()->route('orders.index');         
    }

    public function edit(Order $order){
        if (!in_array(Auth::user()->level, ['admin', 'user'])) {
            abort(401); 
        }

        $tables = Table::all();
        $products = Product::all();
        return Inertia::render('Order/Create', [
            'level'         => Auth::user()->level,
            'tables'        => $tables,
            'products'        => $products,
            'form_type'     => 'PUT',
            'title'         => 'Edit Reservation',
            'model'         => $order,
            'route_url'     => route('orders.update', $order->id),
        ]);
    }

    public function update(Request $request, Order $order){
        $request->validate([
           'name'          => 'required|string|max:255',
            'table_id'      => 'required|exists:tables,id',
            'guests'        => 'required|integer',
            'product_id'    => 'required|exists:products,id',
            'count'         => 'required|integer',
        ]);

        $product = Product::find($request->product_id);
    
        // Cek apakah produk ditemukan
        if (!$product) {
            return redirect()->route('orders.index')->withErrors('Product Not Found');
        }

        $subtotal = $product->price * $request->count;

        $order->update([
            'user_id'       => Auth::user()->id,
            'name'          => $request->name,
            'table_id'      => $request->table_id,
            'guests'        => $request->guests,
            'product_id'    => $request->product_id,
            'count'         => $request->count,
            'subtotal'      => $subtotal,
            'date'          => Carbon::now()->setTimezone("Asia/Jakarta")->format('Y-m-d H:i:s'),
        ]);

        if($subtraction = Product::find($request->product_id)) {
            // dd($table->status);
            $subtraction->stock -= $request->count;
            $subtraction->save();
            // dd($subtraction);
        }

        if($table = Table::find($request->table_id)) {
            // dd($table->status);
            $table->status = 'inactive';
            $table->save();
            // dd($table);
        }

        return redirect()->route('reservations.index');
    }

    public function destroy(Order $order){
        $order->delete();

        if($subtraction = Product::find($order->product_id)) {
            // dd($table->status);
            $subtraction->stock += $order->count;
            $subtraction->save();
            // dd($subtraction);
        }

        if($table = Table::find($order->table_id)) {
            // dd($table->status);
            $table->status = 'active';
            $table->save();
            // dd($table);
        }

        return redirect()->route('orders.index');
    }

    public function show($order){
        $models = Order::with('user', 'table', 'product', 'requestpay')->findOrFail($order);
        return Inertia::render('Order/Show', [
            'models'        => $models,
        ]);
    }

    public function reqPay(Request $request, Order $order) {
        
        $key = env("XENDIT", "");
        // $order->load(['table']);
        // dd($key, $reservation);

        $response = Http::withBasicAuth($key, '')
            ->post("https://api.xendit.co/v2/invoices", [
                "external_id"   => "order-{$order->id}",
                "amount"        => $order->subtotal,
                "payer_email"   => Auth::user()->email,
                "description"   => "Order #{$order->id}"
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
                'order_id'                      => $order->id,
            ]);

            return redirect()->route('orders.show', $order->id);
        } else {
            dd($response->object());
        }
    }

    public function finished(Order $order){
        // dd($order);
        if($table = Table::find($order->table_id)) {
            // dd($table->status);
            $table->status = 'active';
            $table->save();
            // dd($table);
        }

        $order = Order::find($order->id);
        $order->status_order = 'Finished';
        $order->save();
    }
}
