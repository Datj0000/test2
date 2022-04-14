<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetchdata()
    {
        if (Auth::check()) {
            $query = Notification::query()->select('products.name as product_name','brands.name as brand_name','importdetails.*','notifications.*')
                ->join('importdetails','importdetails.id','=','notifications.importdetail_id')
                ->join('products','products.id','=','importdetails.product_id')
                ->join('brands','brands.id','=','products.brand_id')
                ->get();
            $output = '';
            foreach ($query as $key => $item){
                if($item->status == 1){
                    $output .= '
                    <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-warning mr-5">
                            <span class="svg-icon svg-icon-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <span class="font-weight-normel text-dark-75 font-size-sm mb-1">Sản phẩm '.$item->brand_name.' '.$item->product_name.' - Mã: '.$item->product_code.' chỉ còn 1 tháng bảo hành</span>
                            <span class="text-muted font-size-sm">'.Carbon::parse($item->created_at)->format('d/m/Y').'</span>
                        </div>
                    </div>';
                } else{
                    $output .= '
                    <div class="d-flex align-items-center bg-light-danger rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-danger mr-5">
                            <span class="svg-icon svg-icon-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000" />
                                        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <div class="d-flex flex-column flex-grow-1 mr-2">
                            <span class="font-weight-normel text-dark-75 font-size-sm mb-1">Sản phẩm '.$item->brand_name.' '.$item->product_name.' - Mã: '.$item->product_code.' đã hết bảo hành</span>
                            <span class="text-muted font-size-sm">'.Carbon::parse($item->created_at)->format('d/m/Y').'</span>
                        </div>
                    </div>';
                }
            }
            return $output;
        }
    }
}
