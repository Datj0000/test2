<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Import;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ImportController extends Controller
{
    public function index(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            $brand = Brand::all();
            $category = Category::all();
            $import = Import::all();
            $product = Product::all();
            $supplier = Supplier::all();
            return view('user.product.import')
                ->with('brand',$brand)
                ->with('category',$category)
                ->with('product',$product)
                ->with('import',$import)
                ->with('supplier',$supplier);
        }
        return Redirect::to('/login');
    }

    public function fetchdata()
    {
        if (Auth::check()) {
            $data = Import::query()->select('suppliers.name as supplier_name',DB::raw('SUM(importdetails.import_price) As total'),DB::raw('SUM(importdetails.quantity) As quantity'),'imports.*')
                ->leftJoin('importdetails','importdetails.id','=','imports.id')
                ->join('suppliers','suppliers.id','=','imports.supplier_id')
                ->groupBy('suppliers.name','imports.code','imports.id','imports.fee_ship','imports.supplier_id','imports.created_at','imports.updated_at')
                ->orderBy('id','Desc')->get();
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
                $check = Import::query()->where('code','=',$code)->first();
            } while ($check);
            $import = Import::query()->create([
                'code' => $code,
                'supplier_id' => $request->input('supplier_id'),
                'fee_ship' => $request->input('fee_ship'),
            ]);
            return $import->id;
        }
    }

    public function edit(int $id)
    {
        if (Auth::check()) {
            $query = Import::query()->where('id','=',$id)->first();
            if($query){
                return response()->json($query->toArray());
            }
        }
    }

    public function update(Request $request,int $id)
    {
        if (Auth::check()) {
            Import::query()->where('id','=',$id)->update([
                'supplier_id' => $request->input('supplier_id'),
                'fee_ship' => $request->input('fee_ship'),
            ]);
        }
    }

    public function destroy(int $id)
    {
        if (Auth::check()) {
            $check = ImportDetail::query()->where('import_id','=',$id)->first();
            if($check){
                return 0;
            } else{
                $query = Import::query()->where('id','=',$id)->first();
                if($query){
                    $query->delete();
                    return 1;
                }
            }
        }
    }

    public function print(int $id)
    {
        if (Auth::check()) {
            $import = Import::query()->where('id','=',$id)->first();
            $supplier = Supplier::query()->where('id','=',$import->supplier_id)->first();
            $detail = ImportDetail::query()->select('categories.name as category_name', 'products.name as product_name','importdetails.*')
                ->join('products','products.id','=','importdetails.product_id')
                ->join('categories','categories.id','=','products.category_id')
                ->where('importdetails.id','=',$id)->get();
            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('pdf.import',[
                'import' => $import,
                'supplier' => $supplier,
                'details' => $detail
            ]);
            return $pdf->stream();
        }
    }
    public function print_barcode(Request $request)
    {
        if (Auth::check()) {
            $data = $request->input('data');
            $output = '';
            foreach ($data['table'] as $key => $item){
                for($i = 0 ; $i < $item['quantity']; $i++){
                    $output .= '
                    <div style="max-width: 140px; float: left; margin-right: 20px; margin-bottom: -10px">
                        '.\Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($item['code'], "C128",1.4,22).'
                        <p style="text-align: center">'.$item['code'].'</p>
                    </div>';
                }
            }
            return $output;
        }
    }
    public function autocomplete(Request $request)
    {
        if (Auth::check()) {
            $query = Import::query()->select('suppliers.name as supplier_name', 'imports.*')
                ->join('suppliers','suppliers.id','=','imports.supplier_id')
                ->where('imports.code','LIKE','%' .  $request->input('value') . '%')->get();
            if ($query->count() > 0) {
                $output = '<ul class="dropdown-menu2">';
                    foreach ($query as $key => $val) {
                        $output .= '<li class="li_search_import" data-id="'.$val->id.'">Mã đơn: ' . $val->code . ' - Nhà cung cấp ' . $val->supplier_name . '</li>';
                    }
                $output .= '</ul>';
                return $output;
            }
        }
    }
}
