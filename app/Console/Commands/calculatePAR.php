<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\dayscount;
use App\Models\OrderItem;
use DB;

class calculatePAR extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:PAR';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate PAR';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::all();
        foreach($products as $product)
        {
            $Avg = dayscount::where('product_id', $product->id)->avg('days');
            $leadAvg = number_format($Avg, 0);
            $leadMax = dayscount::where('product_id', $product->id)->max('days');
            $maxSalesDay = OrderItem::where('product_id', $product->id)
                ->select(DB::raw('DATE(created_at) as day, SUM(quantity) as total_sales'))
                ->groupBy('day')
                ->orderBy('total_sales', 'desc')
                ->first();
            $sumDailySales = OrderItem::where('product_id', $product->id)
                ->select(DB::raw('DATE(created_at) as day, SUM(quantity) as total_sales'))
                ->groupBy('day')
                ->get();
            $sumWeeklySales =OrderItem::where('product_id', $product->id)
                ->select(DB::raw('WEEK(DATE_ADD(created_at, INTERVAL -1 DAY)) as week, SUM(quantity) as total_sales'))
                ->groupBy('week')
                ->get();
            $weeklySales = [];
            foreach ($sumWeeklySales as $weeklySale) {
                $weeklySales[] = $weeklySale->total_sales;
            }
            $sales = [];
            foreach ($sumDailySales as $sale) {
                $sales[] = $sale->total_sales;
            }
            $averageDaily = collect($sales)->avg();
            $averageWeekly = collect($weeklySales)->avg();
            if ($maxSalesDay != null) {
                $mostSalesCount = $maxSalesDay->total_sales;
                $safetyStock = ($mostSalesCount * $leadMax) - ($averageDaily * $leadAvg);
                $parLevel = ($averageWeekly + $safetyStock) / 1;
                $product->par_level = $parLevel;
                $product->save();
                echo "Par level updated";
            }
        }
    }
}
