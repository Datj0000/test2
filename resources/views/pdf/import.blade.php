<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Phiếu nhập kho hàng</title>
    <style>
        body{
            font-family: DejaVu Sans;
            font-size: 14px;
        }
        @page {
            size: A4;
        }
        .footer-left {
            text-align:center;
            padding-top:24px;
            position:relative;
            height: 150px;
            width:50%;
            color:#000;
            float:left;
            font-size: 12px;
            bottom:1px;
        }
        .footer-right {
            text-align:center;
            padding-top:24px;
            position:relative;
            height: 150px;
            width:50%;
            color:#000;
            font-size: 12px;
            float:right;
            bottom:1px;
        }
        .TableData {
            background:#ffffff;
            font: 11px;
            width:100%;
            border-collapse:collapse;
            font-size:12px;
            border:thin solid #d3d3d3;
        }
        .TableData TH {
            text-align: center;
            font-weight: bold;
            color: #000;
            border: solid 1px #ccc;
            height: 24px;
            padding: 7px 0;
        }
        .TableData TR {
            height: 24px;
            border:thin solid #d3d3d3;
        }
        .TableData TR TD {
            padding-right: 2px;
            padding-left: 2px;
            border:thin solid #d3d3d3;
        }
        .TableData TR:hover {
            background: rgba(0,0,0,0.05);
        }
        .TableData .tong {
            text-align: right;
            font-weight:bold;
            padding-right: 4px;
        }
        img{
            width: 15%;
            /*opacity: .2;*/
            position: fixed;
            top: 0%;
            left: -1%;
            filter: grayscale(100%);
        }
    </style>
</head>
<body>
    <div>
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('asset/media/logos/logo2.png'))) }}">
        <center>
        <h3>CÔNG TY CỔ PHẦN GIẢI PHÁP SỐ FUNNY DEV</h3>
        Số 2, Trần Nhân Tông, Phường Thanh Sơn, Uông Bí, Quảng Ninh<br>
        Website: www.maytinhuongbi24h.vn - MST: 5702056524 - Hotline: 1900.633.918
        </center>
        <p style="float: right; font-size:13px">Ngày {{date('d',strtotime($import['created_at']))}} tháng {{date('m',strtotime($import['created_at']))}} năm {{date('Y',strtotime($import['created_at']))}}</p>
        <br>
        <br>
        <h3>
            <center>PHIẾU NHẬP KHO HÀNG<br>
                <span style="font-size:13px">Số: {{$import['code']}}</span><br>
                <span> -------oOo-------</span>
            </center>
        </h3>
        <h4>Thông tin nhà cung cấp</h4>
        <table>
            <tbody>
                <tr>
                    <td style="width: 350px">Tên nhà cung cấp: {{$supplier->name}}</td>
                    <td>Điện thoại: {{$supplier->phone}}</td>
                </tr>
                <tr>
                    <td>Email: {{$supplier->email}}</td>
                    <td>Địa chỉ: {{$supplier->address}}</td>
                </tr>
                <tr>
                    <td>MST: {{$supplier->mst}}</td>
                    <td>Thanh toán: {{$supplier->information}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <h4>Đơn hàng</h4>
    <table class="TableData">
        <thead>
        <tr>
            <th>STT</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
        @php
            $i = 1;
            $total = 0;
        @endphp
        @foreach($details as $item)
            @php
                $subtotal = $item->import_price * $item->quantity;
                $total += $subtotal;
            @endphp
            <tr>
                <td align='center'>{{$i++}}</td>
                <td>{{$item->category_name }} {{ $item->product_name}}</td>
                <td align='center'>{{$item->quantity}}</td>
                <td align='right'>{{number_format($item->import_price, 0, ',', '.')}}đ</td>
                <td align='right'>{{number_format($subtotal, 0, ',', '.')}}đ</td>
            <tr>
        @endforeach
            <tr>
                <td colspan="4" class="tong">Tổng cộng</td>
                <td class="cotSo" align='right'>{{number_format($total, 0, ',', '.')}}đ</td>
            </tr>
            <tr>
                <td colspan="4" class="tong">Phí ship</td>
                <td class="cotSo" align='right'>{{number_format($import->fee_ship, 0, ',', '.')}}đ</td>
            </tr>
            <tr>
                <td colspan="4" class="tong">Thành tiền</td>
                <td class="cotSo" align='right'>{{number_format($total + $import->fee_ship, 0, ',', '.')}}đ</td>
            </tr>
        </table>
        <div class="footer-left">
            Người nhà cung cấp <br>
            <span style="font-style:italic; font-size:13px">(Ký và ghi rõ họ tên)</span>
        </div>
        <div class="footer-right">
            Người lập phiếu <br>
            <span style="font-style:italic; font-size:12px">(Ký và ghi rõ họ tên)</span>
        </div>
    </div>
</body>
</html>
