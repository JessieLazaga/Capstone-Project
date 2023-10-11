<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\payment;
use App\Models\Expiry;
use PdfReport;


class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:manage products', ['only'=> ['index', 'displayReport', 'view']]);
        $this->middleware('can:use POS', ['only'=> ['store']]);
    }
    public function index(Request $request)
    {
        $orders = new Order();
        if($request->start_date){
            $orders = $orders->where('created_at', '>', $request->start_date);
        }
        if($request->end_date){
            $orders = $orders->where('created_at', '>', $request->end_date);
        }
        $orders = $orders->with(['items', 'payment', 'user'])->latest()->paginate(8);
        
        $total = $orders->map(function ($i){
            return $i->getTotal();
        })->sum();
        return view('orders.index', compact('orders', 'total'));
    }
    public function displayReport(Request $request)
    {
        $fromDate = $request->start_date;
        $toDate = $request->end_date;
        

        $title = 'Orders Report'; // Report title

        $meta = [ // For displaying filters description on header
            'Registered on' => $fromDate . ' To ' . $toDate,
        ];

        $queryBuilder = payment::select(['name','total_amount','created_at']) // Do some querying..
                            ->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate)
                            ->orderBy('created_at', 'ASC');
        foreach($queryBuilder as $order){
            $userName = $order->user_id->name;
        }
        $columns = [ // Set Column to be displayed
            'Cashier ID' => 'name',
            'Total' => 'total_amount',
            'Registered At' => 'created_at', 
        ];

        // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
        return PdfReport::of($title, $meta, $queryBuilder, $columns)
            ->editColumn('Registered At', [ // Change column class or manipulate its data for displaying to report
                'displayAs' => function($result) {
                    return $result->created_at->format('d M Y');
                },
                'class' => 'left'
            ])
            ->editColumns(['Registered At','Total'], [ // Mass edit column
                'class' => 'right bold'
            ])
            ->showTotal([ // Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                'Total' => 'point' // if you want to show dollar sign ($) then use 'Total Balance' => '$'
            ])
            ->limit(20) // Limit record to be showed
            ->download($fromDate.'-'.$toDate); // other available method: store('path/to/file.pdf') to save to disk, download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
            return view('orders.index');
        }
    
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user()->id,
        ]);
        $cart = $request->user()->cart()->get();
        foreach ($cart as $item){
            $order->items()->create([
                'price' => $item->price * $item->pivot->quantity,
                'quantity' => $item->pivot->quantity,
                'product_id' => $item->id,
            ]);
            $batch = $item->active;
            $activeBatch = Expiry::where('order_number', $batch)->first();
            $activeBatch->quantity = $activeBatch->quantity - $item->pivot->quantity;
            $activeBatch->save();
            $item->quantity = $item->quantity - $item->pivot->quantity;
            $item->save();
        }
        $request->user()->cart()->detach();
        $order->payment()->create([
            'amount' => $request->amount,
            'total_amount' => $request->tAmount,
            'user_id' => $request->user()->id,
            'name' => $request->user()->name,
        ]);
        
        return 'success';
    }
    public function view($id){
        $orders = Order::where('id', $id)->first();
        return view('orders.view', compact('orders'));
    }
}
