<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;



class DashboardController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasRole('Manufacturer')) {
            return redirect()->route('manufacturer.dashboard');
        }
        $orders = Order::with(['items', 'payment'])->get();
        return view('admin.index', [
            'orders_count' => $orders->count(),
            'income' => $orders->map(function($i) {
                if($i->getReceived() > $i->getTotal()) {
                    return $i->getTotal();
                }
                return $i->getReceived();
            })->sum(),
            'income_today' => $orders->where('created_at', '>=', date('Y-m-d').' 00:00:00')->map(function($i) {
                if($i->getReceived() > $i->getTotal()) {
                    return $i->getTotal();
                }
                return $i->getReceived();
            })->sum(),
        ]);
    }
   
}
