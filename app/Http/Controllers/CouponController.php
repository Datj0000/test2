<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CouponController extends Controller
{
    public function index(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            return view('user.event.coupon');
        }
        return Redirect::to('/login');
    }
    public function fetchdata():\Illuminate\Http\JsonResponse
    {
        if (Auth::check()) {
            $data = Coupon::all();
            return response()->json([
                "data" => $data->toArray(),
            ]);
        }
    }

    public function create(Request $request)
    {
        if (Auth::check()) {
            $check = Coupon::query()->where('code','=',$request->input('code'))->first();
            if (!$check){
                Coupon::query()->create([
                    'name' => $request->input('name'),
                    'code' => $request->input('code'),
                    'condition' => $request->input('condition'),
                    'number' => $request->input('number'),
                    'time' => $request->input('time'),
                    'date_start' => Carbon::parse($request->input('date_start'))->format('Y-m-d'),
                    'date_end' => Carbon::parse($request->input('date_end'))->format('Y-m-d'),
                    'status' => $request->input('status'),
                ]);
                return 1;
            } else{
                return 0;
            }
        }
    }

    public function edit(int $id)
    {
        if (Auth::check()) {
            $query = Coupon::query()->where('id','=',$id)->first();
            if($query){
                return response()->json($query->toArray());
            }
        }
    }
    public function status(Request $request,int $id)
    {
        if (Auth::check()) {
            $query = Coupon::query()->where('id','=',$id)->first();
            if($query){
                $query->update([
                    'status' => $request->input('status'),
                ]);
            }
        }
    }
    public function update(Request $request,int $id)
    {
        if (Auth::check()) {
            $check = Coupon::query()->where('code','=',$request->input('code'))->where('id','!=',$id)->first();
            if (!$check){
                Coupon::query()->where('id','=',$id)->update([
                    'name' => $request->input('name'),
                    'code' => $request->input('code'),
                    'condition' => $request->input('condition'),
                    'number' => $request->input('number'),
                    'time' => $request->input('time'),
                    'date_start' => Carbon::parse($request->input('date_start'))->format('Y-m-d'),
                    'date_end' => Carbon::parse($request->input('date_end'))->format('Y-m-d'),
                ]);
                return 1;
            } else{
                return 0;
            }
        }
    }

    public function destroy(int $id)
    {
        if (Auth::check()) {
            $query = Coupon::query()->where('id','=',$id)->first();
            if($query){
                $check = Order::query()->where('coupon','=',$query->code)->first();
                if($check){
                    return 0;
                } else{
                    $query->delete();
                    return 1;
                }
            }
        }
    }
    public function autocomplete(Request $request)
    {
        if (Auth::check()) {
            $today = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
            // $coupon = Coupon::query()->where('status','=',0)->where('code',$request->query)->get();
            $coupon = Coupon::query()->where('status','=',0)
                ->where('code','LIKE','%' . $request->input('value'). '%')
                ->where('time','>' ,0)
                ->where('date_start','<=' ,$today)
                ->where('date_end','>=' ,$today)
                ->get();
            if ($coupon->count() > 0) {
                $output = '
                <ul class="dropdown-menu2">';
                foreach ($coupon as $key => $val) {
                    $output .= '
                        <li class="li_search_coupon">' . $val->code . '</li>
                   ';
                }
                $output .= '</ul>';
                return $output;
            }
        }
    }
    public function use(Request $request)
    {
        if (Auth::check()) {
            $today = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
            $coupon = Coupon::query()->where('status','=',0)
                ->where('code','=',$request->input('coupon'))
                ->where('time','>' ,0)
                ->where('date_start','<=' ,$today)
                ->where('date_end','>=' ,$today)
                ->first();
            if($coupon){
                $session = Session::get($request->input('type'));
                if ($session == true) {
                    Session::put($request->input('type'),null);
                }
                $cou[] = array(
                    'code' => $coupon->code,
                    'condition' => $coupon->condition,
                    'number' => $coupon->number,
                );
                Session::put($request->input('type'),$cou);
            } else{
                Session::forget($request->input('type'));
            }
        }
    }
}
