<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ImportDetail;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(): View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        if (Auth::check()) {
            $category = Category::all();
            $brand = Brand::all();
            $unit = Unit::all();
            return view('user.product.product')
                ->with('cate',$category)
                ->with('brand',$brand)
                ->with('unit',$unit);
        }
        return Redirect::to('/login');
    }

    public function fetchdata()
    {
        if (Auth::check()) {
            $data = Product::query()->select('brands.name as brand_name','categories.name as category_name','units.name as unit_name','products.*',DB::raw('SUM(importdetails.quantity) As quantity'),DB::raw('SUM(importdetails.soldout) As soldout'))
                ->leftJoin('importdetails','importdetails.product_id','=','products.id')
                ->join('brands','brands.id','=','products.brand_id')
                ->join('categories','categories.id','=','products.category_id')
                ->join('units','units.id','=','products.unit_id')
                ->groupBy('brands.name','categories.name','units.name','products.id','products.image','products.name','products.brand_id','products.category_id','products.unit_id','products.created_at','products.updated_at')
                ->get();
            return response()->json([
                "data" => $data->toArray(),
            ]);
        }
    }

    public function create(Request $request)
    {
        if (Auth::check()) {
            $check = Product::query()->where('brand_id','=',$request->input('brand_id'))->where('name','=',$request->input('name'))->first();
            if (!$check){
                $product = Product::query()->create([
                    'name' => $request->input('name'),
                    'category_id' => $request->input('category_id'),
                    'brand_id' => $request->input('brand_id'),
                    'unit_id' => $request->input('unit_id'),
                ]);
                $get_image = $request->file('image');
                if ($get_image) {
                    $get_name_image = $get_image->getClientOriginalName();
                    $name_image = current(explode('.',$get_name_image));
                    $new_image =  $name_image . rand(0,99) . '.' . $get_image->getClientOriginalExtension();
                    $get_image->move('uploads/product',$new_image);
                    $product->update([
                        'image' => $new_image,
                    ]);
                }
                return 1;
            } else{
                return 0;
            }
        }
    }

    public function edit(int $id)
    {
        if (Auth::check()) {
            $query = Product::query()->where('id','=',$id)->first();
            if($query){
                return response()->json($query->toArray());
            }
        }
    }

    public function update(Request $request,int $id)
    {
        if (Auth::check()) {
            $check = Product::query()->where('brand_id','=',$request->input('brand_id'))->where('name','=',$request->input('name'))->where('id','!=',$id)->first();
            if (!$check){
                $product = Product::query()->where('id','=',$id)->first();
                $product->update([
                    'name' => $request->input('name'),
                    'category_id' => $request->input('category_id'),
                    'brand_id' => $request->input('brand_id'),
                    'unit_id' => $request->input('unit_id'),
                ]);
                $get_image = $request->file('image');
                if ($get_image) {
                    if ($product->image) {
                        $destinationPath = 'uploads/product/' . $product->image;
                        if (file_exists($destinationPath)) {
                            unlink($destinationPath);
                        }
                    }
                    $get_name_image = $get_image->getClientOriginalName();
                    $name_image = current(explode('.',$get_name_image));
                    $new_image =  $name_image . rand(0,99) . '.' . $get_image->getClientOriginalExtension();
                    $get_image->move('uploads/product',$new_image);
                    $product->update([
                        'image' => $new_image,
                    ]);
                }
                return 1;
            } else{
                return 0;
            }
        }
    }

    public function destroy(int $id)
    {
        if (Auth::check()) {
            $check = ImportDetail::query()->where('product_id','=',$id)->first();
            if($check){
                return 0;
            } else{
                $query = Product::query()->where('id','=',$id)->first();
                if($query){
                    $query->delete();
                    return 1;
                }
            }
        }
    }
    public function load(Request $request)
    {
        if (Auth::check()) {
            $product = Product::query()->where('brand_id','=',$request->brand_id)->where('category_id','=',$request->category_id)->get();
            if ($product->count() > 0) {
                $output = '';
                foreach ($product as $key => $val) {
                    $output .= '<option value="'.$val->id.'">' . $val->name . '</option>';
                }
            }
            else{
                $output = '<option value="">Không có sản phẩm ở danh mục và thương hiệu này</option>';
            }
            return $output;
        }
    }
    /**
     * @param mixed $val
     * @param string $output
     * @return string
     */
    public function getOutput(mixed $val, string $output): string
    {
        if (Auth::user()->role <= 1) {
            $output .= '<li class="li_search_product" data-code="' . $val->product_code . '">Barcode: ' . $val->product_code . ' - ' . $val->brand_name . '  ' . $val->name . ' - Serial: ' . $val->product_serial . ' - Bảo hành đến: ' . Carbon::parse($val->date_end)->format('d/m/Y') . ' - Giá nhập: ' . number_format($val->import_price, 0, ',', '.') . ' đ' . ' - Giá bán: ' . number_format($val->sell_price, 0, ',', '.') . ' đ' . '</li>';
        } else {
            $output .= '<li class="li_search_product" data-code="' . $val->product_code . '">Barcode: ' . $val->product_code . ' - ' . $val->brand_name . '  ' . $val->name . ' - Serial: ' . $val->product_serial . ' - Bảo hành đến: ' . Carbon::parse($val->date_end)->format('d/m/Y') . ' - Giá bán: ' . number_format($val->sell_price, 0, ',', '.') . ' đ' . '</li>';
        }
        return $output;
    }

    public function autocomplete(Request $request)
    {
        if (Auth::check()) {
            if($request->input('order_id') != null ||  $request->input('import_id') != null){
                $product = Product::query()->select('importdetails.*','brands.name as brand_name','products.*')
                    ->join('brands','brands.id','=','products.brand_id')
                    ->join('importdetails','importdetails.product_id','=','products.id')
                    ->join('orderdetails','orderdetails.product_code','=','importdetails.product_code')
                    ->where('importdetails.import_id','=',$request->input('import_id'))
                    ->orwhere('orderdetails.order_id','=',$request->input('order_id'))
                    ->where('products.name','LIKE','%' .  $request->input('value') . '%')
                    ->orwhere('brands.name','LIKE','%' .  $request->input('value') . '%')
                    ->orwhere('importdetails.product_code','LIKE','%' .  $request->input('value') . '%')
                    ->get();
                if ($product->count() > 0) {
                    $output = '<ul class="dropdown-menu2">';
                    foreach ($product as $key => $val){
                        $output = $this->getOutput($val, $output);
                    }
                    $output .= '</ul>';
                }
            } else{
                $product = Product::query()->select('importdetails.*','brands.name as brand_name','products.*')
                    ->join('brands','brands.id','=','products.brand_id')
                    ->join('importdetails','importdetails.product_id','=','products.id')
                    ->where('products.name','LIKE','%' .  $request->input('value') . '%')
                    ->orwhere('brands.name','LIKE','%' .  $request->input('value') . '%')
                    ->orwhere('importdetails.product_code','LIKE','%' .  $request->input('value') . '%')
                    ->get();
                if ($product->count() > 0) {
                    $output = '<ul class="dropdown-menu2">';
                    foreach ($product as $key => $val){
                        if($val->quantity - $val->soldout > 0){
                            $output = $this->getOutput($val, $output);
                        }
                    }
                    $output .= '</ul>';
                }
            }
            return $output;
        }
    }

    public function load_detail(int $id)
    {
        if (Auth::check()) {
            $detail = ImportDetail::query()->select('suppliers.name as supplier_name','suppliers.created_at as supplier_time','products.name as product_name','imports.supplier_id','importdetails.*')
                ->join('imports','imports.id','=','importdetails.import_id')
                ->join('products','products.id','=','importdetails.product_id')
                ->join('suppliers','suppliers.id','=','imports.supplier_id')
                ->where('product_id','=',$id)->get();
            $output = '
            <div class="card-body">
            <table class="table table-separate table-head-custom table-checkable display nowrap" cellspacing="0" width="100%" id="responsive2">
                    <thead>
                        <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Mã sản phẩm</th>
                            <th scope="col">Serial</th>';
            if(Auth::user()->role <= 1){
                $output .='
                            <th scope="col">Nhà cung cấp</th>
                            <th scope="col">VAT</th>
                            <th scope="col">Giá nhập</th>
                            ';
            }
            $output .='
                            <th scope="col">Giá bán</th>
                            <th scope="col">Số lượng</th>';
            if(Auth::user()->role <= 1){
                $output .='<th scope="col">Thời gian nhập</th>';
            }
            $output .='
                            <th scope="col">Bảo hành từ</th>
                            <th scope="col">Bảo hành đến</th>';
            if(Auth::user()->role <= 1){
                $output .='
                <th scope="col">Link drive</th>
                <th scope="col">Chức năng</th>';
            }
            $output .='
                        </tr>
                    </thead>
                    <tbody>
            ';
            $detail_count = $detail->count();
            if ($detail_count > 0) {
                $i = 0;
                $total = 0;
                foreach ($detail as $key => $item) {
                    $i++;
                    $subtotal = $item->sell_price * $item->quantity;
                    $total += $subtotal;
                    $output .= '
                        <tr>
                            <td scope="row">' . $i . '</td>';
                    if($item->image){
                        $output .='
                            <td>
                                <div class="product__shape">
                                    <img class="product__img" src="'.url('uploads/import/'.$item->image.'').'">
                                </div>
                            </td>';
                    }else {
                        $output .='
                            <td>
                                <div class="product__shape">
                                    <img class="product__img" src="'.url('asset/media/users/noimage.png').'">
                                </div>
                            </td>';
                    }

                    $output .='
                        <td>'.$item->product_name.'</td>
                        <td>'.$item->product_code.'</td>
                        <td>'.$item->product_serial.'</td>';
                    if(Auth::user()->role <= 1){
                        $output .='<td>'.$item->supplier_name.'</td>';
                        if($item->vat){
                            $output .='<td>'.$item->vat.'</td>';
                        }else {
                            $output .='<td>Không có</td>';
                        }
                        $output .='<td>'.number_format($item->import_price,0,',','.').'đ'.'</td>';
                    }
                    $output .='
                            <td>'.number_format($item->sell_price,0,',','.').'đ'.'</td>
                            <td>'.$item->quantity.'</td>';
                    if(Auth::user()->role <= 1){
                        $output .=' <td>'.$item->created_at.'</td>';
                    }
                    $output .='
                            <td>'.$item->date_start.'</td>
                            <td>'.$item->date_end.'</td>';
                    if(Auth::user()->role <= 1){
                        if($item->drive){
                            $output .='
                            <td>
                                <a href='.$item->drive.' target="_blank">'.$item->drive.'</a>
                            </td>';
                        } else{
                            $output .='
                            <td>Không có</td>';
                        }
                        $output .='
                        <td>
                            <span data-id='.$item->id.' class="edit_price btn btn-sm btn-clean btn-icon" title="Sửa">
                                <i class="la la-edit"></i>
                            </span>
                        </td>';
                    }
                }
                $output .= '
                            </tr>
                        </tbody>
                    </table>
                </div>';
            } else {
                $output .= '<tr style="text-align: center" ><td colspan="11">Trong kho chưa có sản phẩm này</td></tr>';
            }
            return $output;
        }
    }

}
