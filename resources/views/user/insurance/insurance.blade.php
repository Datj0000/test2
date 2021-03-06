@extends('index')
@section('content')
    <style>
        .dropdown-menu2 {
            width: 100%;
            padding: .5rem 0;
            margin: .125rem 0 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: .25rem;
            padding-left: 20px;
            cursor: pointer;
        }
        .dropdown-menu2 li,
        .dropdown-menu2 a{
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dropdown-menu2 li:active,
        .dropdown-menu2 li:hover,
        .dropdown-menu2 a:active,
        .dropdown-menu2 a:hover{
            color: #717fe0;
        }
        .hide{
            display: none;
        }
        .cart__shape {
            width: 5rem;
            height: 5rem;
            border: 1px solid #fff4de;
        }

        .cart__img {
            width: 100%;
            height: 100%
        }

        .wrap-num-product {
            width: 140px;
            height: 45px;
            border: 1px solid #e6e6e6;
            border-radius: 3px;
            overflow: hidden;
            float: left;
        }

        .btn-num-product-up,
        .btn-num-product-down {
            width: 45px;
            height: 100%;
            cursor: pointer;
        }

        .num-product {
            width: calc(100% - 90px);
            height: 100%;
            border-left: 1px solid #e6e6e6;
            border-right: 1px solid #e6e6e6;
            background-color: #f7f7f7;
        }

        input.num-product {
            -moz-appearance: textfield;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            border: none;
        }

        input.num-product::-webkit-outer-spin-button,
        input.num-product::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            outline: none;
            border: none;
        }
        .hov-btn3:hover {
            border-color: #717fe0;
            background-color: #717fe0;
            color: #fff;
        }

        .hov-btn3:hover i {
            color: #fff;
        }

        .flex-w,.flex-c-m {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            -ms-align-items: center;
            align-items: center;
        }
        .txt-center {text-align: center;}
        .flex-w {
            -webkit-flex-wrap: wrap;
            -moz-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            -o-flex-wrap: wrap;
            flex-wrap: wrap;
        }
    </style>
<div class="card card-custom">
    <div class="card-header flex-wrap py-5">
        <div class="card-title">
            <h3 class="card-label">Danh s??ch ????n b???o h??nh
                <span class="d-block text-muted pt-2 font-size-sm">Qu???n l?? danh s??ch ????n b???o h??nh</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <span class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#exampleModalPopovers">
                <span class="svg-icon svg-icon-md">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                         height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <circle fill="#000000" cx="9" cy="15" r="6" />
                            <path
                                d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>Th??m m???i</span>
        </div>
    </div>
    {{-- Add --}}
    <div class="modal fade" id="exampleModalPopovers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="True">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Th??m ????n b???o h??nh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="True" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="form_add_insurance">
                        <div class=" card-body">
                            <div class="form-group">
                                <label>H??nh th???c b???o h??nh:</label>
                                <select name="method" id="insurance_method" class="form-control">
                                    <option value disabled selected hidden>Ch???n h??nh th???c b???o h??nh</option>
                                    <option value="0">Kh??ch b???o h??nh</option>
                                    <option value="1">T??? b???o h??nh</option>
                                </select>
                            </div>
                            <div class="form-group fee hide">
                                <label>Ph?? b???o h??nh:</label>
                                <input type="number" name="fee" class="form-control form-control-solid" id="insurance_fee" placeholder="Ph?? b???o h??nh" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==20) return false;"/>
                            </div>
                            <div class="form-group fee hide">
                                <label>Ghi ch??:</label>
                                <input type="text" name="note" class="form-control form-control-solid" id="insurance_note" placeholder="Ghi ch??" />
                            </div>
                            <div class="form-group import hide">
                                <label>Nh???p h??ng:</label>
                                <input type="hidden" name="import" id="import_id">
                                <input autocomplete="off" type="text" class="form-control form-control-solid" id="import_name" placeholder="T??m ki???m theo m?? nh???p h??ng" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==255) return false;"/>
                                <div id="search_import"></div>
                            </div>
                            <div class="form-group order hide">
                                <label>????n h??ng:</label>
                                <input type="hidden" name="order" id="order_id">
                                <input autocomplete="off" type="text" class="form-control form-control-solid" id="order_name" placeholder="T??m ki???m theo m?? ????n h??ng" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==255) return false;"/>
                                <div id="search_order"></div>
                            </div>
                            <div class="form-group product hide">
                                <label>S???n ph???m:</label>
                                <input type="hidden" name="product" id="product_id">
                                <input autocomplete="off" type="text" class="form-control form-control-solid" id="product_name" placeholder="T??m ki???m theo m?? s???n ph???m ho???c t??n s???n ph???m" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==255) return false;"/>
                                <div id="search_product"></div>
                            </div>
                            <div id="load_insurance"></div>
                        </div>
                        <div class="card-footer">
                            <button id="create_insurance" type="button" class="btn btn-primary mr-2">Th??m m???i</button>
                            <button type="reset" class="btn btn-secondary">Nh???p l???i</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit --}}
    <div class="modal fade" id="exampleModalPopovers2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="True">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">S???a ????n b???o h??nh <span id="text_code"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="True" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" id="form_edit_insurance">
                        <div class=" card-body">
                            <input type="hidden" id="edit_insurance_id">
                            <div class="form-group">
                                <label>H??nh th???c b???o h??nh:</label>
                                <select disabled name="method" id="edit_insurance_method" class="form-control">
                                    <option value disabled selected hidden>Ch???n h??nh th???c b???o h??nh</option>
                                    <option value="0">Kh??ch b???o h??nh</option>
                                    <option value="1">T??? b???o h??nh</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Ph?? b???o h??nh:</label>
                                <input type="number" name="fee" class="form-control form-control-solid" id="edit_insurance_fee" placeholder="Ph?? b???o h??nh" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==20) return false;"/>
                            </div>
                            <div class="form-group">
                                <label>Ghi ch??:</label>
                                <input type="text" name="note" class="form-control form-control-solid" id="edit_insurance_note" placeholder="Ghi ch??" />
                            </div>
                            <div class="form-group import hide">
                                <label>Nh???p h??ng:</label>
                                <input type="hidden" name="import" id="edit_import_id">
                                <input autocomplete="off" type="text" class="form-control form-control-solid" id="edit_import_name" placeholder="T??m ki???m theo m?? nh???p h??ng" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==255) return false;"/>
                                <div id="edit_search_import"></div>
                            </div>
                            <div class="form-group order hide">
                                <label>????n h??ng:</label>
                                <input type="hidden" name="order" id="edit_order_id">
                                <input autocomplete="off" type="text" class="form-control form-control-solid" id="edit_order_name" placeholder="T??m ki???m theo m?? ????n h??ng" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==255) return false;"/>
                                <div id="edit_search_order"></div>
                            </div>
                            <div class="form-group">
                                <label>S???n ph???m:</label>
                                <input type="hidden" name="product" id="edit_product_id">
                                <input autocomplete="off" type="text" class="form-control form-control-solid" id="edit_product_name" placeholder="T??m ki???m theo m?? s???n ph???m ho???c t??n s???n ph???m" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==255) return false;"/>
                                <div id="edit_search_product"></div>
                            </div>
                            <div id="load_edit_insurance"></div>
                        </div>
                        <div class="card-footer">
                            <button id="update_insurance" type="button" class="btn btn-primary mr-2">C???p nh???t</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-separate table-head-custom table-checkable display nowrap" cellspacing="0" width="100%" id="kt_datatable">
            <thead>
            <tr>
                <th>STT</th>
                <th>M?? b???o h??nh</th>
                <th>Lo???i</th>
                <th>Tr???ng th??i</th>
                <th>Ghi ch??</th>
                <th>Ch???c n??ng</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script type="Text/javascript">
    load_insurance('insurance');
    function load_btn(insurance){
        $('.btn-num-product-down').click(function() {
            var session_id = $(this).data('session_id');
            var numProduct = Number($(this).next().val());
            if (numProduct > 1) $(this).next().val(numProduct - 1);
            if (numProduct > 1) {
                var product_quantity = numProduct - 1;
            } else {
                var product_quantity = 1;
            }
            update_insurance(insurance,session_id, product_quantity);
        });
        $('.btn-num-product-up').click(function() {
            var session_id = $(this).data('session_id');
            var numProduct = Number($(this).prev().val());
            $(this).prev().val(numProduct + 1);
            var max_product_quantity = $('.product_quantity_' + session_id).val();
            var product_quantity = numProduct + 1;
            if (product_quantity > max_product_quantity) {
                update_insurance(insurance,session_id, max_product_quantity);
                Swal.fire({
                    icon: "warning",
                    title: "C???nh b??o",
                    text: "Ch??? c?? th??? th??m " + max_product_quantity + " s???n ph???m!",
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                update_insurance(insurance,session_id, product_quantity);
            }
        });
    }
    function add_insurance(type, code, method){
        axios.post('add-insur',{
            type: type,
            code: code,
            method: method
        })
        .then(function(response) {
            if(response.data == 1){
                if(type == 'insurance'){
                    load_insurance('insurance');
                } else {
                    load_insurance('edit_insurance');
                }
            }else if(response.data == 0) {
                swal.fire({
                    icon: "error",
                    title: "Th???t b???i",
                    text: "S???n ph???m ???? ???????c th??m r???i!",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
    function load_insurance(insurance){
        axios.post('load-insur',{
            insurance: insurance,
        })
        .then(function(response) {
            if(insurance == 'insurance'){
                $("#load_insurance").html(response.data);
                $('#table_insurance').DataTable({
                    "ordering": false,
                    "responsive": true,
                    "searching": false,
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false
                });
                load_btn('insurance');
            } else {
                $("#load_edit_insurance").html(response.data);
                $('#table_edit_insurance').DataTable({
                    "ordering": false,
                    "responsive": true,
                    "searching": false,
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": false,
                    "bAutoWidth": false
                });
                load_btn('edit_insurance');
            }

        });
    }
    function edit_insurance(method, id){
        axios.get('edit-insur/' + method +'-' + id)
        .then(function (response) {
            load_insurance('edit_insurance');
        });
    }
    function update_insurance(type, session_id, product_quantity) {
        axios({
            url: 'update-insur',
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
            },
            data: {
                type: type,
                session_id: session_id,
                product_quantity: product_quantity
            },
        })
        .then(function (response) {
            if(type == 'insurance'){
                load_insurance('insurance');
            } else {
                load_insurance('edit_insurance');
            }
        });
    }
    $(document).ready(function() {
        $('#insurance_method').change(function() {
            var query = $(this).val();
            if (query == 1) {
                $('.import').removeClass('hide');
                $('.order').addClass('hide');
            } else if(query == 0) {
                $('.import').addClass('hide');
                $('.order').removeClass('hide');
            }
            $('.product').removeClass('hide');
            $('.fee').removeClass('hide');
        });
        $('#import_name').keyup(function() {
            var value = $(this).val();
            if (value != '') {
                axios({
                    url: "autocomplete-import",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                    },
                    data: {
                        value: value
                    },
                })
                .then(function (response) {
                    $('#search_import').fadeIn();
                    $('#search_import').html(response.data);
                    $('.li_search_import').click(function() {
                        $('#import_id').val($(this).data('id'));
                        $('#import_name').val($(this).text());
                        $('#search_import').fadeOut();
                    });
                });
            } else {
                $('#search_import').fadeOut();
            }
        });
        $('#order_name').keyup(function() {
            var value = $(this).val();
            if (value != '') {
                axios({
                    url: "autocomplete-order",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                    },
                    data: {
                        value: value
                    },
                })
                    .then(function (response) {
                        $('#search_order').fadeIn();
                        $('#search_order').html(response.data);
                        $('.li_search_order').click(function() {
                            $('#order_id').val($(this).data('id'));
                            $('#order_name').val($(this).text());
                            $('#search_order').fadeOut();
                        });
                    });
            } else {
                $('#search_order').fadeOut();
            }
        });
        $('#product_name').keyup(function() {
            var value = $(this).val();
            var order_id = $('#order_id').val();
            var import_id = $('#import_id').val();
            if ((value != '') && (order_id != '' || import_id != '')) {
                axios({
                    url: "autocomplete-product",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                    },
                    data: {
                        order_id: order_id,
                        import_id: import_id,
                        value: value
                    },
                })
                .then(function (response) {
                    $('#search_product').fadeIn();
                    $('#search_product').html(response.data);
                    $('.li_search_product').click(function() {
                        $('#product_name').val('');
                        $('#search_product').fadeOut();
                        add_insurance('insurance',$(this).data('code'),$('#insurance_method').val());
                    });
                });
            } else {
                $('#search_product').fadeOut();
            }
        });
        $('#edit_insurance_method').change(function() {
            var query = $(this).val();
            if (query == 1) {
                $('.import').removeClass('hide');
                $('.order').addClass('hide');
            } else if(query == 0) {
                $('.import').addClass('hide');
                $('.order').removeClass('hide');
            }
            $('.product').removeClass('hide');
            $('.fee').removeClass('hide');
        });
        $('#edit_import_name').keyup(function() {
            var value = $(this).val();
            if (value != '') {
                axios({
                    url: "autocomplete-import",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                    },
                    data: {
                        value: value
                    },
                })
                .then(function (response) {
                    $('#edit_search_import').fadeIn();
                    $('#edit_search_import').html(response.data);
                    $('.li_search_import').click(function() {
                        $('#edit_import_id').val($(this).data('id'));
                        $('#edit_import_name').val($(this).text());
                        $('#edit_search_import').fadeOut();
                    });
                });
            } else {
                $('#edit_search_import').fadeOut();
            }
        });
        $('#edit_order_name').keyup(function() {
            var value = $(this).val();
            if (value != '') {
                axios({
                    url: "autocomplete-order",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                    },
                    data: {
                        value: value
                    },
                })
                .then(function (response) {
                    $('#edit_search_order').fadeIn();
                    $('#edit_search_order').html(response.data);
                    $('.li_search_order').click(function() {
                        $('#edit_order_id').val($(this).data('id'));
                        $('#edit_order_name').val($(this).text());
                        $('#edit_search_order').fadeOut();
                    });
                });
            } else {
                $('#edit_search_order').fadeOut();
            }
        });
        $('#edit_product_name').keyup(function() {
            var value = $(this).val();
            var order_id = $('#edit_order_id').val();
            var import_id = $('#edit_import_id').val();
            if ((value != '') && (order_id != '' || import_id != '')) {
                axios({
                    url: "autocomplete-product",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                    },
                    data: {
                        order_id: order_id,
                        import_id: import_id,
                        value: value
                    },
                })
                .then(function (response) {
                    console.log(response.data);
                    $('#edit_search_product').fadeIn();
                    $('#edit_search_product').html(response.data);
                    $('.li_search_product').click(function() {
                        $('#edit_product_name').val('');
                        $('#edit_search_product').fadeOut();
                        add_insurance('edit_insurance',$(this).data('code'),$('#edit_insurance_method').val());
                    });
                });
            } else {
                $('#edit_search_product').fadeOut();
            }
        });
        var i = 0;
        var table = $('#kt_datatable').DataTable({
            ajax: 'fetchdata-insurance',
            columns: [
                {
                    'data': null,
                    render: function() {
                        return i += 1
                    }
                },
                {
                    'data': 'code'
                },
                {
                    'data': null,
                    sortable: false,
                    render: function(data, type, row) {
                        if(row.method == 0){
                            return `Kh??ch b???o h??nh`;
                        } else {
                            return `T??? b???o h??nh`;
                        }
                    }
                },
                {
                    'data': null,
                    sortable: false,
                    render: function(data, type, row) {
                        if(row.status == 0){
                            return `<span data-id='${row.id}' class="status_insurance label label-lg label-light-warning label-inline cursor-pointer">??ang x??? l??</span>`;
                        } else if(row.status == 1){
                            return `<span class="label label-lg label-light-success label-inline">Th??nh c??ng</span>`;
                        }
                    }
                },
                {
                    'data': 'note'
                },
                {
                    'data': null,
                    sortable: false,
                    width: '75px',
                    render: function(data, type, row) {
                        return `\
                            <span data-toggle="modal" data-target="#exampleModalPopovers2" data-id='${row.id}' class="edit_insurance btn btn-sm btn-clean btn-icon" title="S???a">\
								<i class="la la-edit"></i>\
							</span>\
                            <span data-id='${row.id}' class="destroy_insurance btn btn-sm btn-clean btn-icon" title="Xo??">\
								<i class="la la-trash"></i>\
							</span>\
                            `
                    }
                },
            ],
            responsive: true,
            language: {
                processing: "??ang t???i d??? li???u",
                search: "T??m ki???m:",
                lengthMenu: "Hi???n th??? _MENU_ h??ng",
                info: "Hi???n th??? t??? _START_ ?????n _END_ trong _TOTAL_ h??ng",
                infoEmpty: "Kh??ng c?? d??? li???u",
                loadingRecords: "??ang t???i d??? li???u",
                zeroRecords: "Kh??ng t??m ki???m ???????c d??? li???u",
                emptyTable: "Kh??ng c?? d??? li???u",
            },
        });
        var form = KTUtil.getById('form_add_insurance');
        var validation = FormValidation.formValidation(
            form, {
                fields: {
                    method: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ch???n lo???i b???o h??nh'
                            },
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );
        var form2 = KTUtil.getById('form_edit_insurance');
        var validation2 = FormValidation.formValidation(
            form2, {
                fields: {
                    method: {
                        validators: {
                            notEmpty: {
                                message: 'Vui l??ng ch???n lo???i b???o h??nh'
                            },
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap()
                }
            }
        );
        $('#create_insurance').click(function(e) {
            e.preventDefault();
            var method = $('#insurance_method').val();
            var fee = $('#insurance_fee').val();
            var note = $('#insurance_note').val();
            validation.validate().then(function(status) {
                if (status == 'Valid') {
                    axios({
                        url: 'create-insurance',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                        data: {
                            method: method,
                            fee: fee,
                            note: note,
                        },
                    })
                    .then(function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Th??nh c??ng",
                            text: "T???o ????n b???o h??nh th??nh c??ng th??nh c??ng!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        i = 0;
                        table.ajax.reload();
                    });
                } else {
                    swal.fire({
                        text: "Xin l???i, c?? v??? nh?? ???? ph??t hi???n th???y m???t s??? l???i, vui l??ng th??? l???i .",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "?????ng ??!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function () {
                        KTUtil.scrollTop();
                    });
                }
            });
        });
        $(document).on('click', '.edit_insurance', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            axios({
                url: 'edit-insurance/' + id,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                },
            })
            .then(function (response) {
                $('#text_code').text(response.data.code);
                $('#edit_insurance_id').val(response.data.id);
                $('#edit_insurance_method').val(response.data.method);
                if(response.data.method == 0){
                    $('.order').removeClass('hide');
                } else {
                    $('.import').removeClass('hide');
                }
                $('#edit_insurance_fee').val(response.data.fee);
                $('#edit_insurance_note').val(response.data.note);
                edit_insurance(response.data.method,response.data.id);
                validation2.validate();
            });
        });
        $('#update_insurance').click(function(e) {
            e.preventDefault();
            var id = $('#edit_insurance_id').val();
            var fee = $('#edit_insurance_fee').val();
            var note = $('#edit_insurance_note').val();
            validation2.validate().then(function(status) {
                if (status == 'Valid') {
                    axios({
                        url: 'update-insurance/'+id,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                        data: {
                            fee: fee,
                            note: note,
                        },
                    })
                    .then(function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Th??nh c??ng",
                            text: "S???a ????n b???o h??nh th??nh c??ng!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        i = 0;
                        table.ajax.reload();
                    });
                } else {
                    swal.fire({
                        text: "Xin l???i, c?? v??? nh?? ???? ph??t hi???n th???y m???t s??? l???i, vui l??ng th??? l???i .",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "?????ng ??!",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-light-primary"
                        }
                    }).then(function () {
                        KTUtil.scrollTop();
                    });
                }
            });
        });
        $(document).on('click', '.destroy_insurance', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "Xo?? ????n b???o h??nh",
                text: "B???n c?? ch???c l?? mu???n x??a ????n b???o h??nh kh??ng?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "?????ng ??!",
                cancelButtonText: "Kh??ng"
            })
            .then(function(result) {
                if (result.value) {
                    axios({
                        url: 'destroy-insurance/' + id,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                    })
                    .then(function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Th??nh c??ng",
                            text: "Xo?? ????n b???o h??nh th??nh c??ng!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        i = 0;
                        table.ajax.reload();
                    });
                }
            });
        });
        $(document).on('change', '.cart_qty', function(e) {
            var type = $(this).data('type');
            var session_id = $(this).data('session_id');
            var max_product_quantity = $('.product_quantity_' + session_id).val();
            var product_quantity = $(this).val();
            if (product_quantity < 1) {
                product_quantity = 1;
            }
            if (product_quantity > max_product_quantity) {
                product_quantity = max_product_quantity;
                Swal.fire({
                    icon: "warning",
                    title: "C???nh b??o",
                    text: "S???n ph???m ch??? c??n " + max_product_quantity + " s???n ph???m!",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            update_cart(type,session_id, product_quantity);
        });
        $(document).on('click', '.status_insurance', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: "X??c nh???n b???o h??nh",
                text: "B???o h??nh th??nh c??ng s???n ph???m?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "?????ng ??!",
                cancelButtonText: "Kh??ng"
            })
            .then(function(result) {
                if (result.value) {
                    axios({
                        url: 'status-insurance/' + id,
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                        },
                    })
                    .then(function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Th??nh c??ng",
                            text: "C???p nh???t ????n b???o h??nh th??nh c??ng!",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        i = 0;
                        table.ajax.reload();
                    });
                }
            });
        });
        $(document).on('click', '.destroy_insur', function(e) {
            e.preventDefault();
            var session_id = $(this).data('session_id');
            var type = $(this).data('type');
            axios({
                url: 'destroy-cart',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name = "csrf-token" ]').attr('content')
                },
                data: {
                    type: type,
                    session_id: session_id
                }
            })
            .then(function () {
                if(type == 'insurance'){
                    load_cart('insurance');
                } else {
                    load_cart('edit_insurance');
                }
            });
        });
    })
</script>
@endsection
