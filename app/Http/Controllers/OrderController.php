<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\Product;
use App\User;
use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Http;
class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // 1. Save the order in database (example)
        $order = [
            'id' => rand(1000,9999), // replace with real order ID from DB
            'customer_name' => $request->name,
            'email' => $request->email,
            'items' => $request->items
        ];

        // 2. Send email
        Mail::to($order['email'])->send(new OrderNotification($order));

        // 3. Return success response
        return response()->json(['status' => 'success', 'message' => 'Order placed & email sent!']);
    }
    public function thankYou(Request $request)
    {
        print_r($request->all());
         $order = [
            'id' => 1009, // replace with real order ID from DB
            'customer_name' => "Bruce",
            'email' => "anupam@bastionex.net",
            'items' => []
            ];
            
            // 2. Send email
            Mail::to($order['email'])->send(new OrderNotification($order));
        // die;
        $status      = $request->query("status");
        $amount      = $request->query("amount");
        $orderid     = $request->query("orderid");
        $merchantTxn = $request->query("merchantTxn");

        if (!empty($status) && strtolower($status) == "success" && !empty($merchantTxn) && !empty($orderid))
        {
            $orderTransaction = order::where([
                "order_number" => $merchantTxn,
                "gateway_order_id" => $orderid
            ])
            ->whereNotIn("payment_status", ["success", "cancel"])
            ->where('reciever_type', 'client')
            ->first();

            if ($orderTransaction) { // Ensure the transaction exists
                $orderTransaction->payment_status = "success";
                $orderTransaction->save();
            }
           
        }
        if (!empty($status) && strtolower($status) == "failed" && !empty($merchantTxn) && !empty($orderid))
        {
            $orderTransaction = order::where([
                "order_number"      => $merchantTxn,
                "gateway_order_id"  => $orderid
            ])
            ->whereNotIn("payment_status", ["cancel","success"])
            // ->where('reciever_type', 'client')
            ->first();

            if ($orderTransaction) { // Ensure the transaction exists
                $orderTransaction->status = "cancel";
                $orderTransaction->save();
            }
        }

         return view('frontend.thanku')->with('order', session('order'))->with('todayOrder', session('todayOrder'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query();
        if(isset($query['orderId']) && $query['status'] == "same" && !empty($query['orderId']))
        {
            $orders = Order::select('*')
                            ->where('user_order_id', $query['orderId']);
            $orders =  $orders->get();

        }
        else if(!empty($query['status']) && $query['status'] == "dup" && !empty($query['phone']))
        {
            $orders = Order::select('*')
                            ->where('status', 'new')
                            ->where('phone', $query['phone']);

            if(!empty($query['date']) && $query['date'] == 'today')
            {
                $orders->whereDate('created_at', date('Y-m-d'));
            }
            $orders =  $orders->get();
        }
        else if(!empty($query['status']) && $query['status'] == "dup")
        {
            // $orders = Order::select(DB::raw('MAX(order_number) as order_number'), DB::raw('CONCAT(first_name, last_name) as customer_name'), 'first_name', 'quantity', 'phone', 'product_name', DB::raw('COUNT(phone) as orderCount'))
            $orders = Order::select('*', DB::raw('COUNT(phone) as orderCount'))
                            ->where('status', 'new')
                            ->groupBy('phone') // Only group by phone
                            ->having('orderCount', '>', 1)
                            ->orderBy('id', 'DESC')
                            ->get();

            // print_r($orders);
        }
        else if(!empty($query['status']))
        {
            $orders=Order::select('*', DB::raw('COUNT(phone) as orderCount'))
                            ->where('status', $query['status'])
                            ->groupBy('user_order_id')
                            ->orderBy('id','DESC')
                            ->get();
           // print_r($orders);die;
        }
        else
        {
            $orders =  Order::select('*', DB::raw('COUNT(user_order_id) AS orderCount'))->orderBy('id','DESC')->groupBy('user_order_id')->paginate();
            //$orders=Order::select('*', DB::raw('COUNT(order_number) as orderCount'))->having('orderCount', '>', 1)->group_by('phone')->orderBy('id','DESC')->paginate(10);
        }

        return view('backend.order.index')->with('orders',$orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    function decryptData($crypt, $country = "india")
    {
        // $iv = env("SECRET_IV");
        // $key = env("SECRET_KEY");
        $iv = ($country === "india") ? env("SECRET_IV") : env("SECRET_IV_TAKA");
        $key = ($country === "india") ? env("SECRET_KEY") : env("SECRET_KEY_TAKA");
        $crypt = base64_decode($crypt);
        $padtext = openssl_decrypt($crypt, "AES-256-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        $pad = ord($padtext[strlen($padtext) - 1]);
        if ($pad > strlen($padtext)) {
            return false;
        }
        if (strspn($padtext, $padtext[strlen($padtext) - 1], strlen($padtext) - $pad) != $pad) {
            $text = "Error";
        }
        $text = substr($padtext, 0, -1 * $pad);
        return $text;
    }

    function encryptData($text, $country = "india")
    {
        $iv = ($country === "india") ? env("SECRET_IV") : env("SECRET_IV_TAKA");
        $key = ($country === "india") ? env("SECRET_KEY") : env("SECRET_KEY_TAKA");
        $size = 16;
        $pad = $size - (strlen($text) % $size);
        $padtext = $text . str_repeat(chr($pad), $pad);
        $crypt = openssl_encrypt($padtext, "AES-256-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        return base64_encode($crypt);
    }

    public function fetchTransactions(Request $request)
    {
        $status      = $request->query("status");
        $amount      = $request->query("amount");
        $orderid     = $request->query("orderid");
        $merchantTxn = $request->query("merchantTxn");

        if (!empty($status) && strtolower($status) == "success" && !empty($merchantTxn) && !empty($orderid))
        {
            $orderTransaction = order::where([
                "order_number" => $merchantTxn,
                "gateway_order_id" => $orderid
            ])
            ->whereNotIn("payment_status", ["success", "cancel"])
            ->where('reciever_type', 'client')
            ->first();

            if ($orderTransaction) { // Ensure the transaction exists
                $orderTransaction->payment_status = "success";
                $orderTransaction->save();
            }
        }
        if (!empty($status) && strtolower($status) == "failed" && !empty($merchantTxn) && !empty($orderid))
        {
            $orderTransaction = order::where([
                "order_number"      => $merchantTxn,
                "gateway_order_id"  => $orderid
            ])
            ->whereNotIn("payment_status", ["cancel","success"])
            // ->where('reciever_type', 'client')
            ->first();

            if ($orderTransaction) { // Ensure the transaction exists
                $orderTransaction->status = "cancel";
                $orderTransaction->save();
            }
        }
    }

    public function updateTransactionStatus(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'data' => 'required'
        ]);
        if ($validator->fails()) {
            return Helper::getValidationReponse($validator->errors()->first());
        }
        $data = $validator->validated();
        $data = json_decode($this->decryptData($data['data']), true);
        // print_r($data);die;
        if (strtoupper($data["status"]) === "SUCCESS")
        {
            $transaction = Transaction::where('merchant_txn_id', $data['merchantid'])->first();
            if ($transaction["status"] === "complete") {
                return;
            }
            
            //update balance
            ClientAccount::where('id', $clientAccount['id'])->update($updateArray);
            $updateArray = [
                "status" => "complete"
            ];
            //update transaction
            if (isset($data["utr"])) {
                $updateArray["utr"] = $data["utr"];
            }
            Transaction::where('merchant_txn_id', $data['merchantid'])->update($updateArray);
            $transaction = Transaction::where('merchant_txn_id', $data['merchantid'])->first();
            $client = Client::where('id', $transaction["reciever_id"])->first();
            if (filter_var($client->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($client["email"])->send(new DepositMail(["transaction" => $transaction]));
            }

        } else {
            $updateArray = [
                "status" => 'cancel'
            ];
            Transaction::where('merchant_txn_id', $data['merchantid'])->update($updateArray);
        }
    }

    public function generatePaymentOrder($dataArray, $country = "india")
    {
        $url = env('PAYMENT_ORDER_GENERATE_URL');
        // Prepare the request data
        $encryptData = $this->encryptData(json_encode($dataArray), $country);
        $reqData = [
            "reqData" => $encryptData,
            "agentCode" => env('AGENT_CODE')
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $reqData);

        $responseData = $response->json();
        //print_r($responseData);die;
        if (isset($responseData['success']) && $responseData['success'] == 1 && isset($responseData['data'])) {
            return ["status" => "SUCCESS", "data" => $responseData["data"] , "success"=>true, "message"=>""];
        }
        return ["status" => "FAILED", "success"=>"", "message"=>$responseData['message'], "data" => ""]; // Assuming you want to return the response
    }

    public function makePayment($orderId, $amount)
    {
        $reqData = [
            'orderid' => $orderId, // Or use UUID
            'amount' => $amount,
        ];
        $country = "india";

        $fxResponseData = $this->generatePaymentOrder($reqData, $country);
          print_r($fxResponseData);die;
        if ($fxResponseData['status'] === "FAILED") 
        {
            $updateArray = [
                "status" => "cancel"
            ];
            // Order::where('user_order_id', $orderId)->update($updateArray);
            return $fxResponseData;
            // return Helper::getReponse('FAILED', 'Something wrong!', $fxResponseData, 301);
        }
        $fxResponseData = json_decode($this->decryptData($fxResponseData['data'], $country), true);
        if (isset($fxResponseData["url"]) && isset($fxResponseData["orderid"]))
        {
            $updateArray = [
                "gateway_order_id" => $fxResponseData["orderid"]
            ];
            Order::where('order_number', $orderId)->update($updateArray);
            return redirect($fxResponseData['url']);
        } else {
            $updateArray = [
                "status" => "cancel"
            ];
            Order::where('order_number', $orderId)->update($updateArray);
            // return $updateArray;
        }
        return $fxResponseData;
    }

    public function store_new(Request $request)
    {
        $this->validate($request, [
                                        'first_name'    =>'string|required',
                                    //  'last_name'     =>'string|required',
                                        'phone'         =>'required',
                                        'email'         =>'email|nullable',
                                        'address1'      =>'string|required',
                                        'shipping'      =>'numeric|required',
                                        'quantity'      =>'string|required',
                                       // 'size'          =>'string|nullable',
                                        //'color'         =>'string|nullable'
                                        'payment_method'=>'string|required'
                                    ]);

        //dd($request->all());

        $order                      =   new Order();
        $order_data                 =   $request->all();
        $order_data['order_number'] =   'ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']      =   null;
        $order_data['shipping_id']  =   $request->shipping;
        //$shipping                 =   Shipping::where('id',$order_data['shipping_id'])->pluck('price');
        $product                    =   Product::where('slug', $request->slug)->first();

        if(str_contains(strtolower($request->quantity), 'key'))
        {
            $selectedKeyArr             = explode('-', $request->quantity);
            $key                        = $selectedKeyArr[1] - 1;
            $quantityArr                = explode(',', $product->quantity_price);
            $quantityValue              = $quantityArr[$key];
            $quantityValueArr           = explode(':', $quantityValue);
            $finalselectedQtyValue      = $quantityValueArr[0];

            $order_data['quantity']          =  1;
            $order_data['quantity_selected'] =  $quantityValueArr[1];
            $order_data['sub_total']         =  $finalselectedQtyValue;
            $delivery_charge                 = (($product->delivery_charge)? $product->delivery_charge : 0);
            $order_data['total_amount']      =  $order_data['sub_total'] + $delivery_charge;
            $quantityToShow                  = $order_data['quantity_selected'];
        }
        else if(str_contains(strtolower($request->quantity), 'actual'))
        {
            $selectedKeyArr             =  explode('-', $request->quantity);
            $quantity_unit              =  ($selectedKeyArr[1] > 0)? $selectedKeyArr[1] : 1;
            $order_data['quantity']     =  $selectedKeyArr[1];
            $order_data['sub_total']    =  $product->discount_price * $quantity_unit;
            $delivery_charge            = (($product->delivery_charge)? $product->delivery_charge : 0);
            $order_data['total_amount'] =  $order_data['sub_total'] + (($product->delivery_charge)? $product->delivery_charge : 0);
            $quantityToShow             = $order_data['quantity'];
        }
        $order_data['product_name']   =   $product->title;
        $order_data['product_id']     =   $product->id;
        $order_data['payment_method'] =   $request->payment_method;

        if($product->vat == "yes")
        {
            $vatVal     =  round((($order_data['sub_total']*5)/100), 2);
        }
        else
        {
            $vatVal     = 0;
        }
        $order_data['total_amount'] = $order_data['total_amount'] + $vatVal;
	
	    // $products          = Product::find($product->id);
       	 
        $order->fill($order_data);
        // print_r($order);
        $status=$order->save();
        $orderNumber = $order_data['order_number'];
        $sessionId      = session()->getId();
        if($status)
        {
            $User_order_id  = "DKT-".sprintf('%06d', $order->id);
            $cacheUser      = json_decode(Cache::get('current_user_'.$sessionId)); //json_decode(Cache::get('current_user'));
            if(!empty($cacheUser))
            {
                if($request->phone == $cacheUser->phone)
                {
                    $order->is_cache    =  "yes";
                    $User_order_id      =  $cacheUser->orderId;
                }
            }
            $order->user_order_id  = $User_order_id;
            $order->payment_status = "pending";
            $order->payment_method =  $request->payment_method;
            $order->update();
        }
        if($request->payment_method == 'online')
        {
            $statusArr = $this->makePayment($orderNumber, $order_data['total_amount']);
            // print_r($statusArr);die;
            if($statusArr['status'] == 'cancel')
            {
                    Order::where('order_number', $orderNumber)->update(["payment_status"=>"cancel"]);
                    return redirect()->back()->with('status',$statusArr);
            }
            if($statusArr['status'] == 'FAILED' && empty($statusArr['success']))
            {
                    Order::where('order_number', $orderNumber)->update(["payment_status"=>"cancel"]);
                    return redirect()->back()->with('status',$statusArr);
            }
        }
            
        // Calculate the remaining time until midnight
        $now            =   strtotime(date('Y-m-d H:i:s'));
        $endofDay       =   strtotime(date('Y-m-d').' 23:59:59');
        $diffInMinutes  =   round(($endofDay - $now) / 60); // difference in minute
        $sessionId      =   session()->getId();
        $cookieData     =   array(
                                    "orderId"           =>  $User_order_id,
                                    "first_name"        =>  $order_data['first_name'],
                                    "phone"             =>  $order_data['phone'],
                                    "shipping"          =>  $order_data['shipping'],
                                    "email"             =>  $order_data['email'],
                                    "address1"          =>  $order_data['address1'],
                                    "delivery_charge"   =>  $delivery_charge,
                                    "sessionId"         =>  $sessionId,
                                   // "total_price"=> $order_data['sub_total']
                                );


         //echo $diffInMinutes;die;
        if (Cache::put('current_user_'.$sessionId, json_encode($cookieData), $diffInMinutes*60))
        {
            //Cache::put($order_data['phone'], json_encode($thankData), $diffInMinutes*60);
        }


       $thankData = array(
                                'product_image' =>  $product->photo,
                                'product_title' =>  $product->title,
                                'shipping_cost' =>  $delivery_charge,
                                'quantity'      =>  $quantityToShow,
                                'total_price'   =>  $order_data['sub_total'],
                                'vat'           =>  $product->vat,
                                "first_name"    =>  $order_data['first_name'],
                                "email"         =>  $order_data['email'],
                            );
        $matchedRecords = Order::select('orders.*','products.vat', 'products.delivery_charge', 'products.photo')
                                    ->join('products', 'products.id','=','orders.product_id')
                                    ->where('orders.id', '<>', $order->id)
                                    ->where('orders.user_order_id', $User_order_id)
                                    // ->whereDate('orders.created_at', date('Y-m-d'))
                                    // ->where('orders.phone', $order_data['phone'])
                                    ->get();

        request()->session()->flash('success','Your product order has been placed. Thank you for shopping with us.');
        return redirect('thanku')->with('order',$thankData)->with('todayOrder', $matchedRecords);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'first_name'=>'string|required',
            'last_name'=>'string|required',
            'address1'=>'string|required',
            'address2'=>'string|nullable',
            'coupon'=>'nullable|numeric',
            'phone'=>'numeric|required',
            'post_code'=>'string|nullable',
            'email'=>'string|required'
        ]);
        // return $request->all();

        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            request()->session()->flash('error','Cart is Empty !');
            return back();
        }
        // $cart=Cart::get();
        // // return $cart;
        // $cart_index='ORD-'.strtoupper(uniqid());
        // $sub_total=0;
        // foreach($cart as $cart_item){
        //     $sub_total+=$cart_item['amount'];
        //     $data=array(
        //         'cart_id'=>$cart_index,
        //         'user_id'=>$request->user()->id,
        //         'product_id'=>$cart_item['id'],
        //         'quantity'=>$cart_item['quantity'],
        //         'amount'=>$cart_item['amount'],
        //         'status'=>'new',
        //         'price'=>$cart_item['price'],
        //     );

        //     $cart=new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }

        // $total_prod=0;
        // if(session('cart')){
        //         foreach(session('cart') as $cart_items){
        //             $total_prod+=$cart_items['quantity'];
        //         }
        // }

        $order                          =  new Order();
        $order_data                     =  $request->all();
        $order_data['order_number']     =  'ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']          =  $request->user()->id;
        $order_data['shipping_id']      =  $request->shipping;
        $shipping                       =  Shipping::where('id',$order_data['shipping_id'])->pluck('price');
        // return session('coupon')['value'];
        $order_data['sub_total']        =  Helper::totalCartPrice();
        $order_data['quantity']         =  Helper::cartCount();
        if(session('coupon')){
            $order_data['coupon']       =  session('coupon')['value'];
        }
        if($request->shipping){
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0]-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0];
            }
        }
        else{
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice();
            }
        }
        // return $order_data['total_amount'];
        // $order_data['status']="new";
        // if(request('payment_method')=='paypal'){
        //     $order_data['payment_method']='paypal';
        //     $order_data['payment_status']='paid';
        // }
        // else{
        //     $order_data['payment_method']='cod';
        //     $order_data['payment_status']='Unpaid';
        // }
        if (request('payment_method') == 'paypal') {
            $order_data['payment_method'] = 'paypal';
            $order_data['payment_status'] = 'paid';
        } elseif (request('payment_method') == 'cardpay') {
            $order_data['payment_method'] = 'cardpay';
            $order_data['payment_status'] = 'paid';
        } else {
            $order_data['payment_method'] = 'cod';
            $order_data['payment_status'] = 'Unpaid';
        }
        $order->fill($order_data);
        $status=$order->save();
        if($order)
        // dd($order->id);
        $users=User::where('role','admin')->first();
        $details=[
            'title'=>'New Order Received',
            'actionURL'=>route('order.show',$order->id),
            'fas'=>'fa-file-alt'
        ];
        Notification::send($users, new StatusNotification($details));
        if(request('payment_method')=='paypal'){
            return redirect()->route('payment')->with(['id'=>$order->id]);
        }
        else{
            session()->forget('cart');
            session()->forget('coupon');
        }
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // dd($users);
        request()->session()->flash('success','Your product order has been placed. Thank you for shopping with us.');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::find($id);
        // return $order;
        return view('backend.order.show')->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order=Order::find($id);
        return view('backend.order.edit')->with('order',$order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order=Order::find($id);
        $this->validate($request,[
            'status'=>'required|in:new,confirm,delete,dispatched,return,waiting,delivered'
        ]);
        $data=$request->all();
        // return $request->status;
        if($request->status=='delivered'){
            foreach($order->cart as $cart){
                $product=$cart->product;
                // return $product;
                $product->stock -=$cart->quantity;
                $product->save();
            }
	    $products          = Product::find($order->product_id);
//            $products->stock  -= $order->quantity;

	    if($products->stock >=  $order->quantity)
            {
                $products->stock  -= $order->quantity;
            }else{
                request()->session()->flash('error','Sorry, the requested quantity of this product is not available in stock.');
                return redirect()->route('order.index');
            }

            $products->save();
        }
        $status=$order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated order');
        }
        else{
            request()->session()->flash('error','Error while updating order');
        }
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        // return $request->all();
        $order=Order::where('user_id',auth()->user()->id)->where('order_number',$request->order_number)->first();
        if($order){
            if($order->status=="new"){
            request()->session()->flash('success','Your order has been placed.');
            return redirect()->route('home');

            }
            elseif($order->status=="process"){
                request()->session()->flash('success','Your order is currently processing.');
                return redirect()->route('home');

            }
            elseif($order->status=="delivered"){
                request()->session()->flash('success','Your order has been delivered. Thank you for shopping with us.');
                return redirect()->route('home');

            }
            else{
                request()->session()->flash('error','Sorry, your order has been canceled.');
                return redirect()->route('home');

            }
        }
        else{
            request()->session()->flash('error','Invalid order number. Please try again!');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request){
        $order=Order::getAllOrder($request->id);
        // return $order;
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        // return $file_name;
        $pdf=PDF::loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
