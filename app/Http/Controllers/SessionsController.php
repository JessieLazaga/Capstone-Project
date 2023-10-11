<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJSON()) {
            return response(
                $request->cart()->get()
            );
        }
        return view('cart.sessions');
    }
}
