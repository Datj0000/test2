<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Redirect;

class UnitController extends Controller
{
    public function index(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            return view('user.product.unit');
        }
        return Redirect::to('/login');
    }
    public function fetchdata()
    {
        if (Auth::check()) {
            $query = Unit::all();
            return response()->json([
                "data" => $query->toArray(),
            ]);
        }
    }

    public function create(Request $request)
    {
        if (Auth::check()) {
            $check = Unit::query()->where('name',$request->name)->first();
            if (!$check){
                Unit::query()->create([
                    'name' => $request->input('name'),
                    'desc' => $request->input('desc'),
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
            $query = Unit::query()->where('id','=',$id)->first();
            if($query){
                return response()->json($query->toArray());
            }
        }
    }

    public function update(Request $request,int $id)
    {
        if (Auth::check()) {
            $check = Unit::query()->where('name','=',$request->input('name'))->where('id','!=',$id)->first();
            if (!$check){
                Unit::query()->where('id','=',$id)->update([
                    'name' => $request->input('name'),
                    'desc' => $request->input('desc'),
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
            $check = Product::query()->where('unit_id','=',$id)->first();
            if($check){
                return 0;
            } else{
                $query = Unit::query()->where('id','=',$id)->first();
                if($query){
                    $query->delete();
                    return 1;
                }
            }
        }
    }
}
