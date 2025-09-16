<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Shipping;
use App\Models\Order;
use DB;
use App\Models\ProductImages;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class CustomController extends Controller
{

    public function deleteImage($productId, $imageId)
    {
        $product  = ProductImages::where('product_id', $productId)->where('id', $imageId)->first();
        if($product)
        {
            $product->delete();
            return "yes";
        }
        else
        {
            return "no";
        }
    }

    public function editOrder($orderId)
    {
        $order          =   Order::find($orderId);
        $products       =   Product::all();
        //print_r($products);die;
        return view('backend.order.edit-order')->with(['order'=>$order, 'products'=>$products]);
    }
    public function modifiedOrder(Request $request, $id)
    {
      //  dd($request->first_name);
        $order=Order::find($id);
        $this->validate($request,[
            // 'status'=>'required|in:new,confirm,delete,dispatched,return,waiting,delivered'
            'first_name'    =>'required|string',
            'last_name'     =>'required|nullable',
            'phone'         =>'required',
            'address1'      =>'required|string',
            'product_id'    =>'required|numeric',
            'shipping_id'   =>'required|numeric',
        ]);

      // print_r($request->all());die;

        $order_data                 =   Order::find($id);

        $order_data->first_name     =   $request->first_name;
        $order_data->last_name      =   $request->last_name;
        $order_data->phone          =   $request->phone;
        $order_data->address1       =   $request->address1;
       // $status=$order->save();

        $shipping                   =   Shipping::where('id', $request->shipping_id)->pluck('price');
        $product                    =   Product::where('id', $request->product_id)->first();

        $after_discount             =   ($product->price - ($product->price * $product->discount) / 100);
        $order_data->shipping_id    =   $request->shipping_id;
        $order_data->product_name   =   $product->title;
        $order_data->product_id     =   $product->id;
        $order_data->sub_total      =   $after_discount; //Helper::totalCartPrice();
        $order_data->quantity       =   $request->quantity; //Helper::cartCount();
        $after_quantity             =   $after_discount * $request->quantity;
        $order_data->total_amount   =   $after_quantity+$shipping[0]; //$after_discount+$shipping[0];

        //print_r($order_data);die;
        $status=$order_data->save();

      //  $status=$order->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated order');
        }
        else{
            request()->session()->flash('error','Error while updating order');
        }
        return redirect()->route('order.index');
    }

    public function ajaxChangeStatus(Request $request)
    {
        $status = strtolower($request->status);
        $product  = Order::whereIn('id', $request->ids)->update(['status'=>$status]);
        if($product)
        {
            session()->flash('success','Successfully updated order');
           // $product->delete();
            return "yes";
        }
        else
        {
            session()->flash('error','Ordre not updated');
            return "no";
        }
    }

    public function exportCsv(Request $request)
    {
        //print_r($request->selected_ids);die;
        $ids        = explode(',', $request->selected_ids);
        $orders     = \DB::table('orders as od')
                        ->select('od.user_order_id', 'od.id', 'od.order_number', 'od.first_name', 'od.address1', 'od.phone', 'sp.type as city', 'delivery_note', 'pd.code', 'pd.title', 'pd.delivery_charge', 'pd.discount_price', 'od.quantity_selected', 'od.sub_total', 'od.quantity', 'pd.vat', 'od.total_amount', 'od.size', 'od.color')
                        ->leftJoin('products as pd', 'pd.id', '=', 'od.product_id')
                        ->leftJoin('shippings as sp', 'sp.id', '=', 'od.shipping_id')
                        ->whereIn('od.id', $ids)
                        ->groupBy('od.user_order_id')
                        //->limit(100)
                        ->get();

        $header = [
                        'Order_Id',
                        'Name',
                        'Address',
                        'Contact_Number',
                        'City',
                        'Delivery_Note',
                        'Product_Code',
                        'Product_Name',
                        'Delivery_Charge',
                        'Product_Price',
                        'Quantity',
                        'Product_VAT',
                        'Product_Sub_Price',
                        'Product_Total_Price',
                        'Size',
                        'Color'
                    ];
        $data[] = $header;
        foreach($orders as $order)
        {
            if($order->vat == "yes")
            {
               $vatAmount   = round((($order->sub_total * 5)/100), 2);
               //$totalAmount = $order->sub_total + $vatAmount;
            }
            else
            {
                //$totalAmount    = $order->total_amount;
                $vatAmount      =  0;
            }
            $totalAmount        = $order->total_amount;

            $productCode            = "";
            $productTitle           = "";
            $deliveryCharges        = "";
            $discountPrice          = "";
            $selectedQuantity       = "";
            $productSubPrice        = "";
            $sub_VatAmount          = 0;
            $Sub_totalAmount        = 0;
            $sub_size               = '';
            $sub_color              = '';

            $checkOrderRep    = Order::where('user_order_id', $order->user_order_id)->count();
           // echo $checkOrderRep;die;
            if($checkOrderRep > 1)
            {
                $sub_orders     = \DB::table('orders as od')
                                    ->select('od.user_order_id', 'od.first_name', 'od.address1', 'od.phone', 'sp.type as city', 'delivery_note', 'pd.code', 'pd.title', 'pd.delivery_charge', 'pd.discount_price', 'od.quantity_selected',  'od.sub_total', 'od.quantity', 'pd.vat', 'od.total_amount', 'od.size', 'od.color')
                                    ->leftJoin('products as pd', 'pd.id', '=', 'od.product_id')
                                    ->leftJoin('shippings as sp', 'sp.id', '=', 'od.shipping_id')
                                    ->where('od.user_order_id', $order->user_order_id)
                                    ->where('od.id','<>', $order->id)
                                    ->get();
               // print_r($sub_orders);
                foreach($sub_orders as $sub_order)
                {
                    $suQuantity          = ((!empty($sub_order->quantity_selected)) ? $sub_order->quantity_selected : $sub_order->quantity);

                    $productCode        = $productCode.', '.$sub_order->code.' X '.$suQuantity;
                    $productTitle       = $productTitle.', '.$sub_order->title;
                    $deliveryCharges    = $deliveryCharges.', '.$sub_order->delivery_charge;
                    $discountPrice      = $discountPrice.', '.$sub_order->discount_price;
                    $selectedQuantity   = $selectedQuantity .', '.$suQuantity;
                    $productSubPrice    = $productSubPrice.', '.$sub_order->total_amount;
                    $sub_size           = $sub_size.', '.$sub_order->size;
                    $sub_color          = $sub_color.', '.$sub_order->color;

                    if($sub_order->vat == "yes")
                    {
                        $sub_VatAmount_unit   = round((($sub_order->sub_total * 5)/100), 2);
                        //$totalAmount_unit     = $sub_order->sub_total + $sub_VatAmount_unit + $sub_order->delivery_charge;
                    }
                    else
                    {
                       // $totalAmount_unit    = $sub_order->sub_total + $sub_order->delivery_charge;
                        $sub_VatAmount_unit  =  0;
                    }

                    $sub_VatAmount          = $sub_VatAmount + $sub_VatAmount_unit;
                    $Sub_totalAmount        = $Sub_totalAmount + $sub_order->total_amount;
                 //   print_r($selectedQuantity)."<br>";
                }

            }
            //print_r($selectedQuantity)."<br>";
            $sQuantity = ((!empty($order->quantity_selected)) ? $order->quantity_selected : $order->quantity);

            $arr['order_number']        = $order->user_order_id;
            $arr['first_name']          = $order->first_name;
            $arr['address1']            = $order->address1;
            $arr['phone']               = $order->phone;
            $arr['city']                = $order->city;
            $arr['delivery_note']       = $order->delivery_note;
            $arr['code']                = $order->code . ' X ' . $sQuantity . $productCode;
            $arr['name']                = $order->title . $productTitle;
            $arr['delivery_charge']     = $order->delivery_charge.$deliveryCharges;
            $arr['product_price']       = $order->discount_price.$discountPrice;
            $arr['quantity']            = $sQuantity . $selectedQuantity;
            $arr['product_vat']         = $vatAmount + $sub_VatAmount;
            $arr['product_sub_price']   = $order->total_amount.$productSubPrice;
            $arr['total_amount']        = $totalAmount + $Sub_totalAmount;
            $arr['size']                = $order->size.$sub_size;
            $arr['color']               = $order->color.$sub_color;

            $data[] = $arr;
        }



       // die;
        // Generate CSV file
        $csvFileName = 'exported_data.csv';

        // Set headers
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        );

        // Use the stream method to send CSV content as a stream
        return response()->stream(
            function () use ($data) {
                $handle = fopen('php://output', 'w');

                // Output the CSV content
                foreach ($data as $row) {
                    fputcsv($handle, $row);
                }

                fclose($handle);
            },
            200,
            $headers
        );
    }

    public function deleteRecords(Request $request)
    {
        $ids   = explode(',', $request->selected_del_ids);
        //print_r($ids);die;
        Order::whereIn('id', $ids)->delete();

        request()->session()->flash('success','Deleted Successfully!');
        return redirect()->route('order.index');
    }
    public function checkTodayOrders(Request $request)
    {
       $sameData = \DB::table('orders')->select("phone","first_name AS full_name", DB::raw('COUNT(phone) as phoneCount'))
                            ->whereIn('phone', $request->ids)
                            ->whereDate('created_at', date('Y-m-d'))
                            ->having('phoneCount', '>', 1)
                            ->groupBy('phone')
                            ->get();
       // print_r($sameData);
      //  echo json_encode($sameData);die;
        return response()->json($sameData);
    }

    public function missingOrders(Request $request)
    {
       $missingOrders = \DB::table('order_missing')
                            // ->whereDate('created_at', date('Y-m-d'))
                            // ->having('phoneCount', '>', 1)
                            ->orderBy('id', 'DESC')
                            ->get();
        return view('backend.order.missing', compact('missingOrders'));
    }

    public function deleteMissingOrders(Request $request)
    {
        $ids   = explode(',', $request->selected_del_ids);
        //print_r($ids);die;
        DB::table('order_missing')->whereIn('id', $ids)->delete();
        request()->session()->flash('success','Deleted Successfully!');
        return redirect()->route('order.missing');
    }

    public function missingOrderDestroy($id)
    {
        $order = DB::table('order_missing')->where('id', $id)->first();

        if($order)
        {
            $status =  DB::table('order_missing')->where('id', $id)->delete();
           // $status =   $order->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            //return redirect()->route('order.index');
            return redirect()->back();
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }

    public function addMissingOrder(Request $request)
    {
        //print_r($request->all());
         $dataArr = ['product_id'=>$request->product_id, 'product_name'=>$request->product_name, 'name'=>$request->name, 'phone'=>$request->phone];
        $chck  =  DB::table('order_missing')->where('phone', $dataArr['phone'])->whereDate('created_at', date('Y-m-d'))->count();
        if($chck > 0)
        {
            return false;
        }
        else
        {
            DB::table('order_missing')->insert($dataArr);
            return true;
        }



    }
}
