<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJSON()) {
            return response(
                $request->user()->cart()->get()
            );
        }
        return view('cart.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|exists:products,barcode',
        ]);
        $barcode = $request->barcode;

        $cart = $request->user()->cart()->where('barcode', $barcode)->first();
        if($cart){
            // update quantity when returned multiple
            $cart->pivot->quantity = $cart->pivot->quantity + 1;
            $cart->pivot->save();
        }else{
        $product = Product::where('barcode', $barcode)->first();
        $request->user()->cart()->attach($product->id, ['quantity' => 1]);
        }
        Log::info($barcode);
        return response('', 204);
    }
    public function changeQty(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = $request->user()->cart()->where('id', $request->product_id)->first();

        if($cart){
            $cart->pivot->quantity = $request->quantity;
            $cart->pivot->save();
        }
        return response([
            'success' => true
        ]);
    }
    public function delete(Request $request){
        $request->validate([
            'product_id' => 'required|exists:products,id|integer',
        ]);
        $request->user()->cart()->detach($request->product_id);

        return response('', 204);
    }
    public function empty(Request $request){
        $request->user()->cart()->detach();
        return response('', 204);
    }
    public function voidOrder(Request $request){
        //$user = User::where('name', '=', $request->login)->first();
        $username = $request->login;
        $passw = $request->pass;
        $checker = User::where('name', $username)->first();
        if($checker){
            if(Hash::check($passw, $checker->password)){
                if($checker->hasPermissionTo('manage products')){
                    return response('success');
                } 
            }
            //if(Hash::check($passw, $checker->password)){
                //$request->user()->cart()->detach();
                //return response('', 204);
            //}//else{
                //return response([
                    //'success' => false,
                    //'message' => 'Wrong Password'
                //]);
            }
        
        //if($checker && Hash::check($passw, $checker->password)) {
            //return response('success');
        //}
    } 
}
