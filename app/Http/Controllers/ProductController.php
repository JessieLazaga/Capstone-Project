<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Requests\GenerateRequest;
use App\Models\procurement;
use App\Models\dayscount;
use App\Models\dates;
use App\Models\Product;
use App\Models\Expiry;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Image;
use Alert;
use Imagick;
use QrCode;
use Auth;
use Carbon\Carbon;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Models\OrderItem;
use Input;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('can:manage products', ['only'=> ['create', 'store', 'edit', 'chart', 'update', 'destroy', 'DeleteProduct']]);
    }
    public function setBatch($id)
    {
        $product = Product::find($id);
        $batches = Expiry::where('product_id', $product->id)->get();
        return view('admin.products.setBatch')->with('product', $product)->with('batches', $batches);
    }
    public function updateBatch(Request $request)
    {
        $products = Product::find($request->id);
        $products->active = $request->batch;
        $products->save();
        return redirect()->route('products.index');
    }
    public function deleteBatch($id)
    {
        $batch = Expiry::find($id);
        $reductQuantity = $batch->quantity;
        $product = Product::where('id', $batch->product_id)->first();
        $productQuantity = $product->quantity;
        $product->quantity = $productQuantity - $reductQuantity;
        $product->save();
        $batch->delete();
        return redirect()->back();
    }
    public function confirmDelivery(Request $request)
    {
        $order = procurement::find($request->procure_id);
        $product = Product::find($request->product_id);
        $product->ordered = false;
        $product->save();
        $order->confirmed = true;
        $order->save();
    }
    public function batchIndex()
    {
        $batches = Expiry::all();
        return view('admin.products.batch')->with('batches', $batches);
    }
    public function index(Request $request)
    {

        $products = new Product();
        if ($request->search) {
            $products = $products->where('name', 'LIKE', "%{$request->search}%");
        }
        $products = $products->latest()->Paginate(3, ['*'], 'all');
        
        if (request()->wantsJson()) {
            return ProductResource::collection($products);
        }
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manufacturers = User::whereHas('roles', function($query) {
            $query->where('name', 'manufacturer');
        })->whereNotNull('email_verified_at')->get();
        return view('admin.products.create')->with('manufacturers', $manufacturers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $image = $request->file('image');
        if($image)
        {
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,200)->save('image/products/'.$name_gen);
            $last_img = 'image/products/'.$name_gen;
            $product = Product::create([
                'name' => $request->name,
                'manufacturer_id' => $request->get('manufacturer'),
                'image' => $last_img,
                'quantity' => $request->quantity,
                'barcode'=> $request->barcode,
                'price' => $request->price,
            ]);
        }else{
            $product = Product::create([
                'name' => $request->name,
                'manufacturer_id' => $request->get('manufacturer'),
                'quantity' => $request->quantity,
                'barcode'=> $request->barcode,
                'price' => $request->price,
            ]);
        }
        if (! $product){
            return redirect()->back()->with('error', 'Sorry, there was a problem while creating product.');
        }
        return redirect()->route('products.index')->with('success', 'Product Inserted Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $manufacturers = User::whereHas('roles', function($query) {
            $query->where('name', 'manufacturer');
        })->whereNotNull('email_verified_at')->get();
        return view('admin.products.edit')->with('product', $product)->with('manufacturers', $manufacturers);
    }
    public function chart(GenerateRequest $request, $id, dates $dateID)
    {
            $option = $request->groupBy;
            $from = $request->fromDate;
            $to = Carbon::parse($request->toDate)->addDays(1);
            $product = Product::find($id);
            $name = $product->name;
            $prodId = $product->id;
            $dateID = dates::find(1);
            $dateID->product_id = $prodId;
            $dateID->save();
            if($option == 1){
                $chart_options = [
                    'chart_title' => 'Daily '.$name.' sales',
                    'report_type' => 'group_by_date',
                    'model' => 'App\Models\OrderItem',
                    'chart_type' => 'line',
                    'conditions' => [
                        ['name' => 'Food', 'condition' => 'product_id = '.$prodId, 'color' => 'red', 'fill' => true],
                    ],
                    'group_by_field' => 'created_at',
                    'group_by_period' => 'day',
                    'aggregate_function' => 'sum',
                    'aggregate_field' => 'quantity',
                    'filter_field' => 'created_at',
                    'range_date_start' => $from,
                    'range_date_end' => $to,
                    'continuous_time' => true,
                ];
            }elseif($option == 2){
                $chart_options = [
                    'chart_title' => 'Weekly '.$name.' sales',
                    'report_type' => 'group_by_date',
                    'model' => 'App\Models\OrderItem',
                    'chart_type' => 'line',
                    'conditions' => [
                        ['name' => 'Food', 'condition' => 'product_id = '.$prodId, 'color' => 'red', 'fill' => true],
                    ],
                    'group_by_field' => 'created_at',
                    'group_by_period' => 'week',
                    'aggregate_function' => 'sum',
                    'aggregate_field' => 'quantity',
                    'filter_field' => 'created_at',
                    'range_date_start' => $from,
                    'range_date_end' => $to,
                    'continuous_time' => true,
                ];
            }if($option == 3){
                $chart_options = [
                    'chart_title' => 'Monthly '.$name.' sales',
                    'report_type' => 'group_by_date',
                    'model' => 'App\Models\OrderItem',
                    'chart_type' => 'line',
                    'conditions' => [
                        ['name' => 'Food', 'condition' => 'product_id = '.$prodId, 'color' => 'red', 'fill' => true],
                    ],
                    'group_by_field' => 'created_at',
                    'group_by_period' => 'month',
                    'aggregate_function' => 'sum',
                    'aggregate_field' => 'quantity',
                    'filter_field' => 'created_at',
                    'range_date_start' => $from,
                    'range_date_end' => $to,
                    'continuous_time' => true,
                ];
            }
        $chart1 = new LaravelChart($chart_options);
        return view('admin.products.chart', compact('chart1'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->manufacturer_id = $request->get('manufacturer_id');
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if($request->hasFile('image')){
            Storage::delete($product->image);
            $image = $request->file('image');

            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,200)->save('image/products/'.$name_gen);

            $last_img = 'image/products/'.$name_gen;
            $product->image = $last_img;
        }

        if (! $product->save()){
            return redirect()->back()->with('error', 'Sorry, there was a problem whilte creating product.');
        }
        return redirect()->route('products.index')->with('success', 'Product Inserted Successfully');

    }
    public function showQr()
    {
        $products = new Product();
        $products = $products->latest()->Paginate(5, ['*'], 'all');
        return view('admin.products.qr', compact('products'));
    }
    public function generateQr($id)
    {
        $product = Product::find($id);
        $barcode = $product->barcode;
        $image = \QrCode::format('png')
                 ->margin(5)
                 ->size(200)->errorCorrection('H')
                 ->generate($barcode);
        $output_file = 'img\qr-code\img-' . $id . '.png';
        Storage::disk('local')->put($output_file, $image);
        $img = Image::make(storage_path('app/img/qr-code/img-' . $id . '.png'));
        $img->text($barcode, 100, 195, function($font){ 
            $font->file(public_path('font/EBGaramond-Regular.ttf'));
            $font->size(25);
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
            $font->angle(0);
        });
        $img->save(storage_path('app/img/qr-code/id-' . $id . '.png'));
        Storage::disk('local')->delete($output_file, $image);
        $last_img = 'app\img\qr-code\id-' . $id . '.png';
        return response()
        ->download(storage_path($last_img))
        ->deleteFileAfterSend(true);
        return redirect()->route('showqr');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted Successfully');
    }
    public function DeleteProduct($id)
    {
        $image = Product::find($id);
        $old_image = $image->image;
        if($old_image)
        {
            unlink($old_image);
        }
        Product::find($id)->delete();
        return Redirect()->route('products.index')->with('success', 'Brand Deleted Successfully');
    }   
    public function showStocks()
    {
        $products = new Product();
        $products = $products->latest()->Paginate(5, ['*'], 'all');
        return view('admin.products.stocks', compact('products'));
    }
    public function orderPanel(Product $product)
    {
        return view('admin.products.procure')->with('product', $product);
    }
    public function placeOrder(Request $request)
    {
        $product_id = Product::where('id', $request->id)->first();
        $manufacturer = $product_id->manufacturer_id;
        $order = new procurement();
        $order->quantity = $request->quantity;
        $order->order_number = $request->order_number;
        $order->product_id = $request->id;
        $order->manufacturer_id = $manufacturer;
        $order->save();
        return redirect()->route('products.index');
    }
    public function procureIndex()
    {
        $procure = new procurement();
        $procure = $procure->latest()->Paginate(5, ['*'], 'all');
        return view('admin.products.procurements', compact('procure'));
    }
    public function receivedOrder(procurement $procure)
    {
        //$quantity = Product::find($request->ident);
        //$quantity->increment('quantity', $request->quan);
        //$received = new dayscount();
        //$received->product_id = $request->ident;
        //$received->days = $request->daysc;
        //$received->save();
        //procurement::where('order_number', $request->num)->delete();
        //$procured = procurement::find($id);
        return view('admin.products.passProcure')->with('procure', $procure);
    }
    public function submitOrder(Request $request)
    {
        $procure = procurement::find($request->id);
        $productID = $procure->product_id;
        $orderNum = $procure->order_number;
        $quantitySub = $procure->quantity;
        $requestQuantity = $request->quantity;
        $product = Product::find($productID);
        $product->increment('quantity', $requestQuantity);
        $received = new dayscount();
        $received->product_id = $productID;
        $now = Carbon::now();
        $days_count = Carbon::parse($procure->created_at)->diffInDays($now);
        $received->days = $days_count;
        $received->save();
        $exp = Expiry::create([
            'product_id' => $productID,
            'order_number' => $request->batch,
            'expiry_date' => $request->expiry_date,
            'quantity' => $requestQuantity,
        ]);
        if($quantitySub == $requestQuantity)
        {
            procurement::where('order_number', $orderNum)->delete();
        }
        else
        {
            $procure->quantity = $quantitySub - $requestQuantity;
            $procure->save();
        }
        return redirect()->route('procurement.index');


        // $expiryDates = $request->get('expiry_date');
        // $quantities = $request->get('quantity');
        // foreach ($expiryDates as $index => $expiryDate)
        // {
        //     $quantity = $quantities[$index];
        //     Expiry::create([
        //         'product_id' => 10,
        //         'order_number' => mt_rand(100000, 999999),
        //         'expiry_date' => $expiryDate,
        //         'quantity' => $quantity,
        //     ]);
        // }
        // return redirect()->route('procurement.index');
    }
    public function click(Request $request){
        $dates = $request->input('date');
        Log::info($dates);
        $dateClick = dates::find(1);
        $dateClick->chart_Date = $dates; 
        $dateClick->save();   
        //$product = dates::create([
            //'chart_Date' => $dates,
        //]);
    }
    public function clack(){
        $dates = dates::find(1);
        $date = $dates->chart_Date;
        $id = $dates->product_id;
        $clickedPoint = OrderItem::whereDate('created_at', '=', $date)
        ->where('product_id', '=', $id)
        ->get();
        return view('admin.products.click', compact('clickedPoint'));
    }
    public function expiry()
    {
        $exp = new Expiry();
        $exp = $exp->Paginate(5, ['*'], 'all');
        return view('admin.products.expiry', compact('exp'));
    }
}
