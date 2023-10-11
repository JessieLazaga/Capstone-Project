<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\User;
use App\Models\procurement;
use App\Models\tryUpdate;
use Illuminate\Support\Facades\Mail;
use App\Jobs\lowStockJob;
use App\Mail\lowStockMail;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $lowStocks = Product::where('ordered', false)->get();
        if($lowStocks)
        {
            foreach($lowStocks as $lowStock)
            {
                $procured = procurement::where('product_id', $lowStock->id)->sum('quantity');
                if($lowStock->quantity + $procured < $lowStock->par_level)
                {
                    if($lowStock->manufacturer_id){
                        $manufacturer = User::where('id', $lowStock->manufacturer_id)->first();
                        Mail::to($manufacturer->email,'Manufacturer')
                        ->queue(new lowStockMail());
                        $lowStock->ordered = true;
                        $lowStock->save();
                        $order = new procurement();
                        $order->quantity = 100;
                        $order->manufacturer_id = $lowStock->manufacturer_id;
                        $order->order_number = mt_rand(100000, 999999);
                        $order->product_id = $lowStock->id;
                        $order->save();
                    }
                }
                
                //Mail::send('emails.lowStock', ['lowStock' => $lowStock], function ($message){
                    //$message->to('robert@gmail.com','CODE POTA')->subject('noreply');
                //});
            }
        }
        
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
