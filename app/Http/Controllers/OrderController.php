<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    public function index(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            if(Session::get('cart')){
                Session::forget('cart');
            }
            $customer = Customer::query()->get();
            return view('user.order.order')
                ->with('customer',$customer);
        }
        return Redirect::to('/login');
    }

    public function fetchdata()
    {
        if (Auth::check()) {
            $data = Order::query()->select('customers.name as customer_name','orders.*')
                ->join('customers','customers.id','=','orders.customer_id')
                ->get();
            return response()->json([
                "data" => $data->toArray(),
            ]);
        }
    }

    public function create(Request $request)
    {
        if (Auth::check()) {
            do {
                $code = rand(106890122,1000000000);
                $check = Order::query()->where('code','=',$code)->first();
            } while ($check);
            $order = Order::query()->create([
                'code' => $code,
                'customer_id' => $request->input('customer_id'),
                'method_pay' => $request->input('method_pay'),
                'note' => $request->input('note'),
            ]);
            if (Session::get('fee')){
                $order->update([
                    'fee_ship' => Session::get('fee')
                ]);
                Session::forget('fee');
            }
            if (Session::get('coupon')){
                foreach (Session::get('coupon') as $key => $cou) {
                    $order->update([
                        'coupon' => $cou['code']
                    ]);
                    $coupon = Coupon::query()->where('code','=',$cou['code'])->first();
                    $coupon?->update([
                        'time' => $coupon->time - 1,
                    ]);
                }
                Session::forget('coupon');
            }
            if (Session::get('cart')) {
                foreach (Session::get('cart') as $key => $cart) {
                    OrderDetail::query()->create([
                        'order_id' => $order->id,
                        'product_code' => $cart['product_code'],
                        'quantity' => $cart['product_quantity'],
                    ]);
                    $detail = ImportDetail::query()->where('product_code','=',$cart['product_code'])->first();
                    if($detail){
                        $detail->update([
                            'soldout' => $detail->soldout + $cart['product_quantity'],
                        ]);
                    }
                }
                Session::forget('cart');
            }
        }
    }

    public function edit(int $id)
    {
        if (Auth::check()) {
            $query = Order::query()->select('customers.name as customer_name','customers.phone as customer_phone','orders.*')
                ->join('customers','customers.id','=','orders.customer_id')
                ->where('orders.id','=',$id)->first();
            if($query){
                return response()->json($query->toArray());
            }
        }
    }

    public function update(Request $request,int $id)
    {
        if (Auth::check()) {
            $order = Order::query()->where('id','=',$id)->first();
            $order->update([
                'customer_id' => $request->input('customer_id'),
                'method_pay' => $request->input('method_pay'),
                'note' => $request->input('note'),
            ]);
            if (Session::get('edit_fee')){
                $order->update([
                    'fee_ship' => Session::get('edit_fee')
                ]);
                Session::forget('edit_fee');
            }
            if (Session::get('edit_coupon')){
                foreach (Session::get('edit_coupon') as $key => $cou) {
                    if($order->coupon != $cou['code']){
                        $coupon = Coupon::query()->where ('code','=',$order->coupon)->first();
                        $coupon?->update(['time' => $coupon->time + 1,]);
                        $order->update(['coupon' => $cou['code']]);
                        $coupon2 = Coupon::query()->where('code','=',$cou['code'])->first();
                        $coupon2?->update(['time' => $coupon2->time - 1,]);
                    }
                }
                Session::forget('edit_coupon');
            }
            if (Session::get('edit_cart')) {
                foreach (Session::get('edit_cart') as $key => $cart) {
                    $query = OrderDetail::query()->where('order_id','=',$id)->where('product_code','=',$cart['product_code'])->first();
                    $detail = ImportDetail::query()->where('product_code','=',$cart['product_code'])->first();
                    if($detail){
                        $detail->update([
                            'soldout' => $detail->soldout + $cart['product_quantity'] - $query->quantity,
                        ]);
                    }
                    $query->delete();
                    OrderDetail::query()->create([
                        'order_id' => $id,
                        'product_code' => $cart['product_code'],
                        'quantity' => $cart['product_quantity'],
                    ]);
                }
                Session::forget('edit_cart');
            }
        }
    }

    public function destroy(int $id)
    {
        if (Auth::check()) {
            $check = OrderDetail::query()->where('order_id','=',$id)->first();
            if($check){
                return 0;
            } else{
                $query = Order::query()->where('id','=',$id)->first();
                if($query){
                    $query->delete();
                    return 1;
                }
            }
        }
    }

    public function add_cart(Request $request)
    {
        if (Auth::check()) {
            $detail = ImportDetail::query()->select('brands.name as brand_name','products.name as product_name','products.brand_id','importdetails.*')
                ->join('products','products.id','=','importdetails.product_id')
                ->join('brands','brands.id','=','products.brand_id')
                ->where('product_code','=',$request->input('code'))
                ->first();
            $session_id = substr(md5(microtime()),rand(0,26),5);
            $cart = Session::get($request->input('type'));
            if($detail){
                $date_start = Carbon::parse($detail->date_start)->floorMonth();
                $date_end = Carbon::parse($detail->date_end)->floorMonth();
                $insurance = $date_end->diffInMonths($date_start);
                if ($cart == true) {
                    $check = 0;
                    foreach ($cart as $key => $val) {
                        if ($val['product_code'] == $request->input('code')) {
                            $check++;
                        }
                    }
                    if ($check == 0) {
                        $cart[] = array(
                            'session_id' => $session_id,
                            'product_code' => $detail->product_code,
                            'product_insurance' => $insurance,
                            'product_name' => $detail->brand_name .' '. $detail->product_name,
                            'product_image' => $detail->image,
                            'product_quantity' => '1',
                            'product_iprice' => $detail->import_price,
                            'product_price' => $detail->sell_price,
                        );
                        Session::put($request->input('type'),$cart);
                        return 1;
                    } else{
                        return 0;
                    }
                } else {
                    $cart[] = array(
                        'session_id' => $session_id,
                        'product_code' => $detail->product_code,
                        'product_insurance' => $insurance,
                        'product_name' => $detail->brand_name .' '. $detail->product_name,
                        'product_image' => $detail->image,
                        'product_quantity' => '1',
                        'product_iprice' => $detail->import_price,
                        'product_price' => $detail->sell_price,
                    );
                    Session::put($request->input('type'),$cart);
                    return 1;
                }
            } else{
                return 2;
            }
        }
    }

    public function update_cart(Request $request)
    {
        if (Auth::check()) {
            $cart = Session::get($request->input('type'));
            if ($cart == true) {
                foreach ($cart as $key => $val) {
                    if ($val['session_id'] == $request->input('session_id')) {
                        $cart[$key]['product_quantity'] = $request->input('product_quantity');
                    }
                }
                Session::put($request->input('type'),$cart);
            } else {
                return 0;
            }
        }
    }

    public function destroy_cart(Request $request)
    {
        if (Auth::check()) {
            $cart = Session::get($request->input('type'));
            if ($cart == true) {
                foreach ($cart as $key => $val) {
                    if ($val['session_id'] == $request->input('session_id')) {
                        unset($cart[$key]);
                    }
                }
                Session::put($request->input('type'),$cart);
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function feeship(Request $request)
    {
        if (Auth::check()) {
            Session::put($request->input('type'),$request->input('value'));
        }
    }

    public function load_cart(Request $request)
    {
//        Session::forget('cart');
        if (Auth::check()) {
            if (Session::get($request->input('cart'))) {
                $output = '
            <table class="table table-separate table-head-custom table-checkable display nowrap" cellspacing="0" width="100%" id="table_'.$request->input('cart').'">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá bán</th>
                        <th>Số lượng</th>
                        <th>Bảo hành</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>';
                $i = 1;
                $total = 0;
                $iprice = 0;
                $count = 0;
                foreach (Session::get($request->input('cart')) as $key => $cart) {
                    $subtotal = $cart['product_price'] * $cart['product_quantity'];
                    $iprice += $cart['product_iprice'];
                    $total += $subtotal;
                    $detail = ImportDetail::query()->where('product_code','=',$cart['product_code'])->first();
                    $detail_count = ImportDetail::query()->where('import_id','=',$detail->import_id)->get();
                    foreach($detail_count as $key){
                        $count += $key->quantity;
                    }
                    $import = Import::query()->where('id','=',$detail->import_id)->first();
                    $total_fee = $import->fee_ship / $count;
                    $output .= '
                <tr data-type="'.$request->input('cart').'" data-session_id="'.$cart['session_id'].'">
                    <td>' . $i++ . '</td>';
                    if ($cart['product_image']) {
                        $output .= '
                        <td>
                            <div class="cart__shape">
                                <img class="cart__img" src="' . asset('uploads/import/' . $cart['product_image']) . '" alt="IMG">
                            </div>
                        </td>';
                    } else {
                        $output .= '
                        <td>
                            <div class="cart__shape">
                                <img class="cart__img" src="' . asset('asset/media/users/noimage.png') . '" alt="IMG">
                            </div>
                        </td>';
                    }
                    $output .= '
                    <td>' . $cart['product_name'] . '</td>
                    <td>' . number_format($cart['product_price'],0,',','.') . 'đ</td>';
                    $check_qty = ImportDetail::query()->where('product_code',$cart['product_code'])->first();
                    if($request->input('cart') == 'cart'){
                        $output .= '<input class="product_quantity_' . $cart['session_id'] . '" type="hidden" value="' . $check_qty->quantity - $check_qty->soldout. '">';
                    } else{
                        $output .= '<input class="product_quantity_' . $cart['session_id'] . '" type="hidden" value="' . $check_qty->quantity + 1 - $check_qty->soldout . '">';
                    }
                    $output .= '
                    <td>
                        <div class="wrap-num-product flex-w">
                            <div data-session_id="' . $cart['session_id'] . '" class="btn-num-product-down hov-btn3 flex-c-m">
                                <i class="las la-minus"></i>
                            </div>
                            <input data-type="'.$request->input('cart').'" data-session_id="' . $cart['session_id'] . '" class="cart_qty txt-center num-product" type="number" value="' . $cart['product_quantity'] . '">
                            <div data-session_id="' . $cart['session_id'] . '" class="btn-num-product-up hov-btn3 flex-c-m">
                                <i class="las la-plus"></i>
                            </div>
                        </div>
                    </td>';
                    $output .= '
                    <td>' . $cart['product_insurance'] . ' tháng</td>
                    <td>' . number_format($subtotal,0,',','.') . 'đ</td>
                    <td> <i style="cursor: pointer" data-type="'.$request->input('cart').'" data-session_id="' . $cart['session_id'] . '" class="destroy_cart la la-trash"></td>
                </tr>';
                }
                $output .= '
                    </table>
                    </div>
                </div>
            </div>
            <div style="margin-top: 20px" class="row form-group">
                <div style="width: 10%">Tổng:</div>
                <div style="width: 90%">
                    ' . number_format($total,0,',','.') . 'đ' . '
                </div>
                ';
                if (Session::get($request->input('coupon'))) {
                    foreach (Session::get($request->input('coupon')) as $key => $cou) {
                        if ($cou['condition'] == 0) {
                            $total_coupon = ($total * $cou['number']) / 100;
                        } else {
                            $total_coupon = $cou['number'];
                        }
                        if ($iprice + $total_fee > $total - $total_coupon) {
                            $total_coupon = $iprice + $total_fee;
                            $intomoney = $iprice + $total_fee;
                        } else {
                            $intomoney = $total - $total_coupon;
                        }
                        $output .= '
                            <div style="width: 10%">Giảm giá:</div>
                            <div style="width: 90%">
                                ' . number_format($total_coupon,0,',','.') . 'đ' . '
                            </div>';
                    }
                } else{
                    $intomoney = $total;
                }
                if (Session::get($request->input('fee'))) {
                    $fee_ship = Session::get($request->input('fee'));
                    $intomoney += $fee_ship;
                    $output .= '
                    <div style="width: 10%">Phí lắp đặt:</div>
                    <div style="width: 90%">
                        ' . number_format($fee_ship,0,',','.') . 'đ' . '
                    </div>';
                }
                $output .= '
                <div style="width: 10%">Thành tiền:</div>
                <div style="width: 90%">
                    ' . number_format($intomoney,0,',','.') . 'đ' . '
                </div>
            </div>
            ';
            } else {
                $output = '';
            }
            return $output;
        }
    }
    public function edit_cart(int $id)
    {
        if (Auth::check()) {
            if(Session::get('edit_fee')){
                Session::put('edit_fee',null);
            }
            if(Session::get('edit_coupon')){
                Session::put('edit_coupon',null);
            }
            if(Session::get('edit_cart')){
                Session::put('edit_cart',null);
            }
            $detail = OrderDetail::query()->select('brands.name as brand_name','products.name as product_name','importdetails.*','orderdetails.*')
                ->join('importdetails','importdetails.product_code','=','orderdetails.product_code')
                ->join('products','products.id','=','importdetails.product_id')
                ->join('brands','brands.id','=','products.brand_id')
                ->where('orderdetails.order_id','=',$id)
                ->get();
            if($detail->toArray()){
                foreach ($detail as $key){
                    $session_id = substr(md5(microtime()),rand(0,26),5);
                    $date_start = Carbon::parse($key['date_start'])->floorMonth();
                    $date_end = Carbon::parse($key['date_end'])->floorMonth();
                    $insurance = $date_end->diffInMonths($date_start);
                    $cart[] = array(
                        'session_id' => $session_id,
                        'product_code' => $key['product_code'],
                        'product_insurance' => $insurance,
                        'product_name' => $key['brand_name'] .' '. $key['product_name'],
                        'product_image' => $key['image'],
                        'product_quantity' => $key['quantity'],
                        'product_iprice' => $key['import_price'],
                        'product_price' => $key['sell_price'],
                    );
                }
                Session::put('edit_cart',$cart);
            }
        }
    }
    public function data_print(int $id){

        $detail = OrderDetail::query()->select('units.name as unit_name','brands.name as brand_name','products.name as product_name','importdetails.*','orderdetails.*')
            ->join('importdetails','importdetails.product_code','=','orderdetails.product_code')
            ->join('products','products.id','=','importdetails.product_id')
            ->join('brands','brands.id','=','products.brand_id')
            ->join('units','units.id','=','products.unit_id')
            ->where('orderdetails.order_id','=',$id)
            ->get();
        return $detail;
    }
    public function print(int $id)
    {
        if (Auth::check()) {
            $detail = OrderDetail::query()->select('units.name as unit_name','brands.name as brand_name','products.name as product_name','importdetails.*','orderdetails.*')
                ->join('importdetails','importdetails.product_code','=','orderdetails.product_code')
                ->join('products','products.id','=','importdetails.product_id')
                ->join('brands','brands.id','=','products.brand_id')
                ->join('units','units.id','=','products.unit_id')
                ->where('orderdetails.order_id','=',$id)
                ->get();
            $order = Order::query()->where('id','=',$id)->first();
            $customer = Customer::query()->where('id','=',$order->customer_id)->first();
            $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.order',[
                'order' => $order,
                'customer' => $customer,
                'details' => $this->data_print($id),
            ]);
            return $pdf->stream();
        }
    }
    public function print_detail(int $id)
    {
        if (Auth::check()) {
            $order = Order::query()->where('id','=',$id)->first();
            $customer = Customer::query()->where('id','=',$order->customer_id)->first();
            $pdf = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.orderdetail',[
                'order' => $order,
                'customer' => $customer,
                'details' => $this->data_print($id),
            ]);
            return $pdf->stream();
        }
    }
    public function autocomplete(Request $request)
    {
        if (Auth::check()) {
            $query = Order::query()->select('customers.name as customer_name', 'orders.*')
                ->join('customers','customers.id','=','orders.customer_id')
                ->where('code','LIKE','%' .  $request->input('value') . '%')->get();
            if ($query->count() > 0) {
                $output = '<ul class="dropdown-menu2">';
                foreach ($query as $key => $val) {
                    $output .= '<li class="li_search_order" data-id="'.$val->id.'">Mã đơn hàng: ' . $val->code . ' - Khách hàng: ' . $val->customer_name . '</li>';
                }
                $output .= '</ul>';
                return $output;
            }
        }
    }
}
