@extends('layouts.app')
@section('content')
<div id="main-wrapper" class="page-wrapper" style="min-height: 207px;">
    <div class="container-fluid">
        <div class="card mb-3 business-analytics">  
            <div class="card-body">
                <div class="row flex-between align-items-center g-2 mb-3 order_stats_header">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            {{trans('lang.dashboard_business_analytics')}}</h4>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--8" >
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4 earnings_count" id="earnings_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_earnings')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/total_earning.png') }}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--1" onclick="location.href='{!! route('restaurants') !!}'">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4 vendor_count" id="vendor_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_restaurants')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/restaurant_icon.png') }}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--5" onclick="location.href='{!! route('orders') !!}'">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4" id="order_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_orders')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/active_restaurant.png') }}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--24" onclick="location.href='{!! route('foods') !!}'">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4" id="product_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_products')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/inactive_restaurant.png') }}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--14" >
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4" id="admincommission_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.admin_commission')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/price.png') }}"></span>
                            </div>
                        </div>
                    </div>    
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--6" onclick="location.href='{!! route('users') !!}'">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4" id="users_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_clients')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/new_restaurant.png') }}"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-box-with-icon bg--15" onclick="location.href='{!! route('drivers') !!}'">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="card-box-with-content">
                                    <h4 class="text-dark-2 mb-1 h4" id="driver_count"></h4>
                                    <p class="mb-0 small text-dark-2">{{trans('lang.dashboard_total_drivers')}}</p>
                                </div>
                                <span class="box-icon ab"><img src="{{ asset('images/total_order.png') }}"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row business-analytics_list">
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status pending" href="{{ route('orders','status=order-placed') }}">
                            <div class="data">
                                <i class="mdi mdi-lan-pending"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_placed')}}</h6>
                            </div>
                            <span class="count" id="placed_count"></span> </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status confirmed"  href="{!! route('orders','status=order-confirmed') !!}">
                            <div class="data">
                                <i class="mdi mdi-check-circle"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_confirmed')}}</h6>
                            </div>
                            <span class="count" id="confirmed_count"></span> </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status packaging"  href="{!! route('orders','status=order-shipped') !!}">
                            <div class="data">
                                <i class="mdi mdi-clipboard-outline"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_shipped')}}</h6>
                            </div>
                            <span class="count" id="shipped_count"></span> </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status delivered" href="{!! route('orders','status=order-completed') !!}">
                            <div class="data">
                                <i class="mdi mdi-check-circle-outline"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_completed')}}</h6>
                            </div>
                            <span class="count" id="completed_count"></span>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status canceled" href="{!! route('orders','status=order-canceled') !!}">
                            <div class="data">
                                <i class="mdi mdi-window-close"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_canceled')}}</h6>
                            </div>
                            <span class="count" id="canceled_count"></span>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status failed" href="{!! route('orders','status=order-failed') !!}">
                            <div class="data">
                                <i class="mdi mdi-alert-circle-outline"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_failed')}}</h6>
                            </div>
                            <span class="count" id="failed_count"></span>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <a class="order-status failed" href="{!! route('orders','status=order-pending') !!}">
                            <div class="data">
                                <i class="mdi mdi-car-connected"></i>
                                <h6 class="status">{{trans('lang.dashboard_order_pending')}}</h6>
                            </div>
                            <span class="count" id="pending_count"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.total_sales')}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="position-relative">
                            <canvas id="sales-chart" height="200"></canvas>
                        </div>
                        <div class="d-flex flex-row justify-content-end">
                            <span class="mr-2"> <i class="fa fa-square" style="color:#2EC7D9"></i> {{trans('lang.dashboard_this_year')}} </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.service_overview')}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex-row">
                            <canvas id="visitors" height="222"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header no-border">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">{{trans('lang.sales_overview')}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="flex-row">
                            <canvas id="commissions" height="222"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row daes-sec-sec mb-3">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border d-flex justify-content-between">
                        <h3 class="card-title">{{trans('lang.restaurant_plural')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('restaurants')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                       <div class="table-responsive px-3"> 
                        <table class="table table-striped table-valign-middle" id="restaurantTable">
                            <thead>
                            <tr>
                                <th style="text-align:center">{{trans('lang.restaurant_image')}}</th>
                                <th>{{trans('lang.restaurant')}}</th>
                                <th>{{trans('lang.restaurant_review_review')}}</th>
                                <th>{{trans('lang.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody id="append_list">
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border d-flex justify-content-between">
                        <h3 class="card-title">{{trans('lang.top_drivers')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('drivers')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                      <div class="table-responsive px-3">  
                        <table class="table table-striped table-valign-middle" id="driverTable">
                            <thead>
                            <tr>
                                <th style="text-align:center">{{trans('lang.restaurant_image')}}</th>
                                <th>{{trans('lang.driver')}}</th>
                                <th>{{trans('lang.order_completed')}}</th>
                                <th>{{trans('lang.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody id="append_list_top_drivers">
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row daes-sec-sec">
        <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border d-flex justify-content-between">
                        <h3 class="card-title">{{trans('lang.recent_orders')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('orders')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                       <div class="table-responsive px-3"> 
                        <table class="table table-striped table-valign-middle" id="orderTable">
                            <thead>
                            <tr>
                                <th style="text-align:center">{{trans('lang.order_id')}}</th>
                                <th>{{trans('lang.restaurant')}}</th>
                                <th>{{trans('lang.total_amount')}}</th>
                                <th>{{trans('lang.quantity')}}</th>
                            </tr>
                            </thead>
                            <tbody id="append_list_recent_order">
                            </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header no-border d-flex justify-content-between">
                        <h3 class="card-title">{{trans('lang.recent_payouts')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('payoutRequests.restaurants')}}" class="btn btn-tool btn-sm"><i class="fa fa-bars"></i> </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                      <div class="table-responsive px-3">  
                        <table class="table table-striped table-valign-middle" id="recentPayoutsTable">
                            <thead>
                            <tr>
                                <th>{{ trans('lang.restaurant')}}</th>
                                <th>{{trans('lang.paid_amount')}}</th>
                                <th>{{trans('lang.date')}}</th>
                                <th>{{trans('lang.restaurants_payout_note')}}</th>
                            </tr>
                            </thead>
                            <tbody id="append_list_recent_payouts">
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
@endsection
@section('scripts')
<script src="{{asset('js/chart.js')}}"></script>
<script>
    jQuery("#data-table_processing").show();
    var db = firebase.firestore();
    var currency = db.collection('settings');
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    $(document).ready(function () {
        db.collection('restaurant_orders').orderBy("createdAt",'desc').get().then(
            (snapshot) => {   
                jQuery("#order_count").empty(); 
                jQuery("#order_count").text(snapshot.docs.length);
            });
        db.collection('vendor_products').get().then(
            (snapshot) => {
                jQuery("#product_count").empty();
                jQuery("#product_count").text(snapshot.docs.length);
            });
        db.collection('users').where("role", "==", "customer").orderBy("createdAt",'desc').get().then((snapshot) => {
            jQuery("#users_count").empty();
            jQuery("#users_count").append(snapshot.docs.length);
        });
        db.collection('users').where("role", "==", "driver").orderBy("createdAt",'desc').get().then((snapshot) => {
            jQuery("#driver_count").empty();
            jQuery("#driver_count").append(snapshot.docs.length);
        }); 
        db.collection('vendors').where('title','!=',"").get().then( 
            (snapshot) => {
                jQuery("#vendor_count").empty();
                jQuery("#vendor_count").text(snapshot.docs.length)
                setVisitors();
            });
        getTotalEarnings();
        db.collection('restaurant_orders').where('status', 'in', ["Order Placed"]).get().then(
            (snapshot) => {
                jQuery("#placed_count").empty();
                jQuery("#placed_count").text(snapshot.docs.length);
            });
        db.collection('restaurant_orders').where('status', 'in', ["Order Accepted", "Driver Accepted"]).get().then(
            (snapshot) => {
                jQuery("#confirmed_count").empty();
                jQuery("#confirmed_count").text(snapshot.docs.length);
            });
        db.collection('restaurant_orders').where('status', 'in', ["Order Shipped", "In Transit"]).get().then(
            (snapshot) => {
                jQuery("#shipped_count").empty();
                jQuery("#shipped_count").text(snapshot.docs.length);
            });
        db.collection('restaurant_orders').where('status', 'in', ["Order Completed"]).get().then(
            (snapshot) => {
                jQuery("#completed_count").empty();
                jQuery("#completed_count").text(snapshot.docs.length);
            });
        db.collection('restaurant_orders').where('status', 'in', ["Order Rejected"]).get().then(
            (snapshot) => {
                jQuery("#canceled_count").empty();
                jQuery("#canceled_count").text(snapshot.docs.length);
            });
        db.collection('restaurant_orders').where('status', 'in', ["Driver Rejected"]).get().then(
            (snapshot) => {
                jQuery("#failed_count").empty();
                jQuery("#failed_count").text(snapshot.docs.length);
            });
        db.collection('restaurant_orders').where('status', 'in', ["Driver Pending"]).get().then(
            (snapshot) => {
                jQuery("#pending_count").empty();
                jQuery("#pending_count").text(snapshot.docs.length);
            });
        var placeholder = db.collection('settings').doc('placeHolderImage');
        placeholder.get().then(async function (snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
        })
        var offest = 1;
        var pagesize = 10;
        var start = null;
        var end = null;
        var endarray = [];
        var inx = parseInt(offest) * parseInt(pagesize);
        var append_listvendors = document.getElementById('append_list');
        append_listvendors.innerHTML = '';
        let ref = db.collection('vendors');
        ref.orderBy('reviewsCount', 'desc').limit(inx).get().then(async (snapshots) => {
            var html = '';
            html = await buildHTML(snapshots);
            if (html != '') {
                append_listvendors.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
            }
            $('#restaurantTable').DataTable({
                order: [],
                columnDefs: [
                    {orderable: false, targets: [0, 2, 3]},
                ],
                "language": datatableLang,
                responsive: true,
                paging: false,
                info: false 
            });
        });
        var offest = 1;
        var pagesize = 10;
        var start = null;
        var end = null;
        var endarray = [];
        var inx = parseInt(offest) * parseInt(pagesize);
        var append_listrecent_order = document.getElementById('append_list_recent_order');
        append_list.innerHTML = '';
        ref = db.collection('restaurant_orders');
        ref.orderBy('createdAt', 'desc').where('status', 'in', ["Order Placed", "Order Accepted", "Driver Pending", "Driver Accepted", "Order Shipped", "In Transit"]).limit(inx).get().then(async (snapshots) => {
            var html = '';
            html = await buildOrderHTML(snapshots);
            if (html != '') {
                append_listrecent_order.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
            }
            $('#orderTable').DataTable({
                order: [],
                "language": datatableLang,
                responsive: true,
                paging: false,
                info: false
            });
        });
        var offest = 1;
        var pagesize = 10;
        var start = null;
        var end = null;
        var endarray = [];
        var inx = parseInt(offest) * parseInt(pagesize);
        var append_listtop_drivers = document.getElementById('append_list_top_drivers');
        append_listtop_drivers.innerHTML = '';
        ref = db.collection('users');
        ref.where('role', '==', 'driver').orderBy('orderCompleted', 'desc').limit(inx).get().then(async (snapshots) => {
            var html = '';
            html = await buildDriverHTML(snapshots);
            if (html != '') {
                append_listtop_drivers.innerHTML = html;
                start = snapshots.docs[snapshots.docs.length - 1];
                endarray.push(snapshots.docs[0]);
            }
            $('#driverTable').DataTable({
                order: [],
                columnDefs: [
                    {orderable: false, targets: [0, 3]},
                ],
                "language": datatableLang,
                responsive: true,
                paging: false,
                info: false
            });
        });
        var append_list_recent_payouts = document.getElementById('append_list_recent_payouts');
        append_list_recent_payouts.innerHTML = '';
        db.collection('payouts').where('paymentStatus', '==', 'Success').orderBy('paidDate', 'desc').limit(10).get().then(async (snapshots) => {
            var html = '';
            html = await buildRecentPayoutsHTML(snapshots);
            if (html != '') {
                append_list_recent_payouts.innerHTML = html;
            }
            setTimeout(function(){
                $('#recentPayoutsTable').DataTable({
                    columnDefs: [
                        {
                            targets: 2,
                            type: 'date',
                            render: function (data) {
                                return data;
                            } 
                        },
                        {
                            targets: 1,
                            type: 'num-fmt',
                            render: function (data, type, row, meta) {
                                if (type === 'display') {
                                    return data;
                                }
                                return parseFloat(data.replace(/[^0-9.-]+/g, ""));
                            }
                        },
                    ],
                    order: [['2', 'desc']],
                    "language": datatableLang,
                    responsive: true,
                    paging: false,
                    info: false
                });
            },1500);
        });
    });
    
    async function getTotalEarnings() {

        const months = Array(12).fill(0);
        const currentYear = new Date().getFullYear();

        let totalEarning = 0;
        let adminCommissionTotal = 0;

        // Fetch all completed orders
        await db.collection('restaurant_orders').where('status', 'in', ["Order Completed"]).get().then(orderSnapshots => {
                orderSnapshots.docs.forEach(doc => {

                    let order = doc.data();
                    
                    let order_subtotal = 0;
                    let total_discount = 0;
                    let total_tax_amount = 0;
                    let tip_amount = parseFloat(order.tip_amount || 0);
                    let deliveryCharge = parseFloat(order.deliveryCharge || 0);
                    let platformFee = parseFloat(order.platformFee || 0);
                    let packagingCharge = parseFloat(order.vendor.packagingCharge || 0);

                    //  Calculate subtotal and product extras
                    for (let i = 0; i < order.products.length; i++) {
                        let product = order.products[i];
                        let itemGross = (parseFloat(product.price) + parseFloat(product.extras_price || 0)) * parseInt(product.quantity);
                        order_subtotal += itemGross;
                    }

                    // Total discounts
                    let order_discount = parseFloat(order.discount || 0);
                    let special_discount = parseFloat(order.specialDiscount?.special_discount || 0);
                        total_discount = order_discount + special_discount;

                    // Calculate item-level taxes (if product-level)
                    if (order.taxScope === "product") {
                        let itemSubtotal = order_subtotal;
                        order.products.forEach(product => {
                            let itemGross = (parseFloat(product.price) + parseFloat(product.extras_price || 0)) * parseInt(product.quantity);
                            let itemDiscount = (itemSubtotal > 0) ? (itemGross / itemSubtotal) * total_discount : 0;
                            let itemTaxable = Math.max(0, itemGross - itemDiscount);
                            let itemTaxes = product.taxSetting || [];
                            itemTaxes.forEach(tax => {
                                if (tax.enable) {
                                    let taxAmount = 0;
                                    if (tax.type === "percentage") {
                                        taxAmount = (tax.tax / 100) * itemTaxable;
                                    } else {
                                        taxAmount = tax.tax;
                                    }
                                    total_tax_amount += parseFloat(taxAmount);
                                }
                            });
                        });
                    } 

                    // Order-level taxes (if order-level)
                    if (order.taxScope === "order") {
                        let orderTaxable = Math.max(0, order_subtotal - total_discount);
                        (order.taxSetting || []).forEach(tax => {
                            if (tax.enable) {
                                let taxAmount = 0;
                                if (tax.type === "percentage") {
                                    taxAmount = (tax.tax / 100) * orderTaxable;
                                } else {
                                    taxAmount = tax.tax;
                                }
                                total_tax_amount += parseFloat(taxAmount);
                            }
                        });
                    }

                    // Delivery, packaging, platform taxes
                    let extraCharges = [
                        {amount: deliveryCharge, taxes: order.driverDeliveryTax || []},
                        {amount: packagingCharge, taxes: order.packagingTax || []},
                        {amount: platformFee, taxes: order.platformTax || []},
                    ];

                    extraCharges.forEach(scope => {
                        scope.taxes?.forEach(tax => {
                            if (tax.enable) {
                                let taxAmount = 0;
                                if (tax.type === "percentage") {
                                    taxAmount = (tax.tax / 100) * scope.amount;
                                } else {
                                    taxAmount = tax.tax;
                                }
                                total_tax_amount += parseFloat(taxAmount);
                            }
                        });
                    });

                    //Final subtotal after discounts
                    order_subtotal = order_subtotal - total_discount;
                    
                    // Commission base
                    let commission = 0;
                    let commissionBase = order_subtotal + deliveryCharge + platformFee + total_tax_amount;
                    if (order.adminCommissionType && order.adminCommission) {
                        let commissionValue = parseFloat(order.adminCommission || 0);
                        if (!isNaN(commissionValue) && commissionValue > 0) {
                            if (order.adminCommissionType === 'Percent') {
                                commission = (commissionBase * commissionValue) / 100;
                            } else {
                                commission = commissionValue;
                            }
                        }
                    }
                    adminCommissionTotal += commission;

                    // Final total
                    let order_total = order_subtotal + deliveryCharge + tip_amount + packagingCharge + platformFee + total_tax_amount;

                    // Total earning
                    totalEarning += order_total;

                    // Monthly graph
                    if (order.createdAt) {
                        let date = order.createdAt.toDate();
                        if (date.getFullYear() === currentYear) {
                            months[date.getMonth()] += order_total;
                        }
                    }

                });
            });

        // Format currency
        const formattedTotal = currencyAtRight
            ? parseFloat(totalEarning).toFixed(decimal_degits) + currentCurrency
            : currentCurrency + parseFloat(totalEarning).toFixed(decimal_degits);

        const formattedCommission = currencyAtRight
            ? parseFloat(adminCommissionTotal).toFixed(decimal_degits) + currentCurrency
            : currentCurrency + parseFloat(adminCommissionTotal).toFixed(decimal_degits);

        // Update dashboard
        $("#earnings_count, #earnings_count_graph, #total_earnings_header, .earnings_over_time").text(formattedTotal);
        $("#admincommission_count, #admincommission_count_graph").text(formattedCommission);

        const labels = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
        renderChart($('#sales-chart'), months, labels);

        setCommision();
        jQuery("#data-table_processing").hide();
    }

    function buildHTML(snapshots) {
        var html = '';
        var count = 1;
        var rating = 0;
        snapshots.docs.forEach((listval) => {
            val = listval.data();
            val.id = listval.id;
            var route = '<?php echo route("restaurants.edit", ":id");?>';
            route = route.replace(':id', val.id);
            var routeview = '<?php echo route("restaurants.view", ":id");?>';
            routeview = routeview.replace(':id', val.id);
            html = html + '<tr>';
            if (val.photo == '' && val.photo == null) {
                html = html + '<td class="text-center"><img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + placeholderImage + '" alt="image"></td>';
            } else {
                html = html + '<td class="text-center"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + val.photo + '" alt="image"></td>';
            }
            html = html + '<td data-url="' + routeview + '" class="redirecttopage">' + val.title + '</td>';
            if (val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = Math.round(parseFloat(val.reviewsSum) / parseInt(val.reviewsCount));
            } else {
                rating = 0;
            }
            html = html + '<td><ul class="rating" data-rating="' + rating + '">';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '<li class="rating__item"></li>';
            html = html + '</ul></td>';
            html = html + '<td><a href="' + route + '" > <span class="mdi mdi-lead-pencil" title="{{trans('lang.edit')}}"></span></a></td>';
            html = html + '</tr>';
            rating = 0;
            count++;
        });
        return html;
    }
    function buildDriverHTML(snapshots) {
        var html = '';
        var count = 1;
        snapshots.docs.forEach((listval) => {
            val = listval.data();
            val.id = listval.id;
            var driverroute = '<?php echo route("drivers.edit", ":id");?>';
            driverroute = driverroute.replace(':id', val.id);
            var driverviewroute = '<?php echo route("drivers.view", ":id");?>';
            driverviewroute = driverviewroute.replace(':id', val.id);
            html = html + '<tr>';
            if (val.profilePictureURL == '' && val.profilePictureURL == null) {
                html = html + '<td class="text-center"><img class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + placeholderImage + '" alt="image"></td>';
            } else {
                html = html + '<td class="text-center"><img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="img-circle img-size-32 mr-2" style="width:60px;height:60px;" src="' + val.profilePictureURL + '" alt="image"></td>';
            }
            html = html + '<td data-url="' + driverviewroute + '" class="redirecttopage">' + val.firstName + ' ' + val.lastName + '</td>';
            html = html + '<td data-url="' + driverroute + '" class="redirecttopage">' + val.orderCompleted + '</td>';
            html = html + '<td data-url="' + driverroute + '" class="redirecttopage"><span class="mdi mdi-lead-pencil" title="{{trans('lang.edit')}}"></span></td>';
            html = html + '</tr>';
            count++;
        });
        return html;
    }
    async function buildRecentPayoutsHTML(snapshots) {
        var intRegex = /^\d+$/;
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        var html = '';
        var count = 1;
        snapshots.docs.forEach((listval) => {
            val = listval.data();
            val.id = listval.id;
            getRestaurantName(val.vendorID);
            var price = val.amount;
            if (intRegex.test(price) || floatRegex.test(price)) {
                price = parseFloat(price).toFixed(2);
            } else {
                price = 0;
            }
            if (currencyAtRight) {
                price_val = parseFloat(price).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                price_val = currentCurrency + "" + parseFloat(price).toFixed(decimal_degits);
            }
            html = html + '<tr class="payout_'+val.id+'">';
            var route = '{{route("restaurants.view",":id")}}';
            route = route.replace(':id', val.vendorID);   
            html = html + '<td data-url="'+route+'" class="redirecttopage restname_'+val.vendorID+'" ></td>';
            html = html + '<td class="text-red">(' + price_val + ')</td>';
            var date = val.paidDate.toDate().toDateString();
            var time = val.paidDate.toDate().toLocaleTimeString('en-US');
            html = html + '<td class="dt-time">' + date + ' ' + time + '</td>';
            if (val.note != undefined && val.note != '') {
                html = html + '<td>' + val.note + '</td>';
            } else {
                html = html + '<td></td>';
            }
            html = html + '</tr>';
        });
        return html;
    }
    function getRestaurantName(vendorId) {
        database.collection('vendors').doc(vendorId).get().then(async function (snapshots) {
            if(snapshots.exists){
                var data = snapshots.data();
                $(".restname_"+vendorId).text(data.title);
            }
        });
    }
    function buildOrderHTML(snapshots) {
        var html = '';
        var count = 1;
        snapshots.docs.forEach((listval) => {
            val = listval.data();
            val.id = listval.id;
            var route = '<?php echo route("orders.edit", ":id"); ?>';
            route = route.replace(':id', val.id);
            var vendorroute = '<?php echo route("restaurants.view", ":id");?>';
            vendorroute = vendorroute.replace(':id', val.vendorID);
            html = html + '<tr>';
            html = html + '<td data-url="' + route + '" class="redirecttopage">' + val.id + '</td>';
            var price = 0; 
                var quan = 0;
            val.products.forEach((product)=> {
                if(product.quantity != 0){
                    quan = quan + product.quantity;
                }
            })
            html = html + '<td data-url="' + vendorroute + '" class="redirecttopage">' + val.vendor.title + '</td>';
            var price =  buildHTMLProductstotal(val);
            html = html + '<td data-url="' + route + '" class="redirecttopage">' + price + '</td>';
            html = html + '<td data-url="' + route + '" class="redirecttopage"><i class="fa fa-shopping-cart"></i> ' + quan + '</td>';
            html = html + '</a></tr>';
            count++;
        });
        return html;
    }
    function renderChart(chartNode, data, labels) {
        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        };
        var mode = 'index';
        var intersect = true;
        return new Chart(chartNode, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        backgroundColor: '#2EC7D9',
                        borderColor: '#2EC7D9',
                        data: data
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect,
                    callbacks: {
                            label: function (tooltipItem, chartData) {
                                let datasetLabel = chartData.datasets[tooltipItem.datasetIndex].label || '';
                                let value = tooltipItem.yLabel;
                                if (currencyAtRight) {
                                    return datasetLabel + ": " + value.toFixed(2) + currentCurrency;
                                } else {
                                    return datasetLabel + ": " + currentCurrency + value.toFixed(2);
                                }
                            }
                        }
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                if (currencyAtRight) {
                                    return value.toFixed(2) + currentCurrency;
                                } else {
                                    return currentCurrency + value.toFixed(2);
                                }
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
                }
            }
        })
    }
    $(document).ready(function () {
        $(document.body).on('click', '.redirecttopage', function () {
            var url = $(this).attr('data-url');
            window.location.href = url;
        });
    });
    function buildHTMLProductstotal(snapshotsProducts) {
        let order_subtotal = 0;
        let total_discount = 0;
        let total_tax_amount = 0;
        let tip_amount = parseFloat(snapshotsProducts.tip_amount || 0);
        let deliveryCharge = parseFloat(snapshotsProducts.deliveryCharge || 0);
        let platformFee = parseFloat(snapshotsProducts.platformFee || 0);
        let packagingCharge = parseFloat(snapshotsProducts.vendor.packagingCharge || 0);

        //  Calculate subtotal and product extras
        for (let i = 0; i < snapshotsProducts.products.length; i++) {
            let product = snapshotsProducts.products[i];
            let itemGross = (parseFloat(product.price) + parseFloat(product.extras_price || 0)) * parseInt(product.quantity);
            order_subtotal += itemGross;
        }

        // Total discounts
        let order_discount = parseFloat(snapshotsProducts.discount || 0);
        let special_discount = parseFloat(snapshotsProducts.specialDiscount?.special_discount || 0);
            total_discount = order_discount + special_discount;

        // Calculate item-level taxes (if product-level)
        if (snapshotsProducts.taxScope === "product") {
            let itemSubtotal = order_subtotal;
            snapshotsProducts.products.forEach(product => {
                let itemGross = (parseFloat(product.price) + parseFloat(product.extras_price || 0)) * parseInt(product.quantity);
                let itemDiscount = (itemSubtotal > 0) ? (itemGross / itemSubtotal) * total_discount : 0;
                let itemTaxable = Math.max(0, itemGross - itemDiscount);
                let itemTaxes = product.taxSetting || [];
                itemTaxes.forEach(tax => {
                    if (tax.enable) {
                        let taxAmount = 0;
                        if (tax.type === "percentage") {
                            taxAmount = (tax.tax / 100) * itemTaxable;
                        } else {
                            taxAmount = tax.tax;
                        }
                        total_tax_amount += taxAmount;
                    }
                });
            });
        } 

        // Order-level taxes (if order-level)
        if (snapshotsProducts.taxScope === "order") {
            let orderTaxable = Math.max(0, order_subtotal - total_discount);
            (snapshotsProducts.taxSetting || []).forEach(tax => {
                if (tax.enable) {
                    let taxAmount = 0;
                    if (tax.type === "percentage") {
                        taxAmount = (tax.tax / 100) * orderTaxable;
                    } else {
                        taxAmount = tax.tax;
                    }
                    total_tax_amount += taxAmount;
                }
            });
        }

        // Delivery, packaging, platform taxes
        let extraCharges = [
            {amount: deliveryCharge, taxes: snapshotsProducts.driverDeliveryTax || []},
            {amount: packagingCharge, taxes: snapshotsProducts.packagingTax || []},
            {amount: platformFee, taxes: snapshotsProducts.platformTax || []},
        ];

        extraCharges.forEach(scope => {
            scope.taxes?.forEach(tax => {
                if (tax.enable) {
                    let taxAmount = 0;
                    if (tax.type === "percentage") {
                        taxAmount = (tax.tax / 100) * scope.amount;
                    } else {
                        taxAmount = tax.tax;
                    }
                    total_tax_amount += taxAmount;
                }
            });
        });

        //Final subtotal after discounts
        order_subtotal = order_subtotal - total_discount;

        // Final total
        let order_total = order_subtotal + deliveryCharge + tip_amount + packagingCharge + platformFee + total_tax_amount;

        if (currencyAtRight) {
            order_total_val = parseFloat(order_total).toFixed(decimal_degits) + '' + currentCurrency;
        } else {
            order_total_val = currentCurrency + '' + parseFloat(order_total).toFixed(decimal_degits);
        }

        return order_total_val;
    }
    function setVisitors() {
        const data = {
            labels: [
                "{{trans('lang.dashboard_total_restaurants')}}",
                "{{trans('lang.dashboard_total_orders')}}",
                "{{trans('lang.dashboard_total_products')}}",
                "{{trans('lang.dashboard_total_clients')}}",
                "{{trans('lang.dashboard_total_drivers')}}",
            ],
            datasets: [{
                data: [jQuery("#vendor_count").text(), jQuery("#order_count").text(), jQuery("#product_count").text(), jQuery("#users_count").text(), jQuery("#driver_count").text()],
                backgroundColor: [
                    '#218be1',
                    '#B1DB6F',
                    '#7360ed',
                    '#FFAB2E',
                    '#FF683A',
                ],
                hoverOffset: 4
            }]
        };
        return new Chart('visitors', {
            type: 'doughnut',
            data: data,
            options: {
                maintainAspectRatio: false,
            }
        })
    }
    function setCommision() {
        const data = {
            labels: [
                "{{trans('lang.dashboard_total_earnings')}}",
                "{{trans('lang.admin_commission')}}"
            ],
            datasets: [{
                data: [jQuery("#earnings_count").text().replace(currentCurrency, ""), jQuery("#admincommission_count").text().replace(currentCurrency, "")],
                backgroundColor: [
                    '#feb84d',
                    '#9b77f8',
                    '#fe95d3'
                ],
                hoverOffset: 4
            }]
        };
        return new Chart('commissions', {
            type: 'doughnut',
            data: data,
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    callbacks: {
                        label: function (tooltipItems, data) {
                            return data.labels[tooltipItems.index] + ': ' + currentCurrency + data.datasets[0].data[tooltipItems.index];
                        }
                    }
                }
            }
        })
    }
</script>
@endsection
