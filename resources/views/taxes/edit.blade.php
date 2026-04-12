@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{trans('lang.tax')}}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">{{trans('lang.dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{!! route('tax') !!}">{{trans('lang.tax_plural')}}</a></li>
                    <li class="breadcrumb-item active">{{trans('lang.tax_edit')}}</li>
                </ol>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card pb-4">
                <div class="card-body">
                    <div class="row daes-top-sec mb-3">
                    </div>
                    <div class="error_top"></div>
                    <div class="row restaurant_payout_create">
                        <div class="restaurant_payout_create-inner">
                            <fieldset>
                                <legend>{{trans('lang.tax_edit')}}</legend>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.tax_title')}}<span
                                                class="required-field"></span></label>
                                    <div class="col-7">
                                        <input type="text" class="form-control tax_title">
                                        <div class="form-text text-muted">
                                            {{ trans("lang.tax_title_help") }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.tax_type')}}<span
                                                class="required-field"></span></label>
                                    <div class="col-7">
                                        <select class="form-control tax_type">
                                            <option value="fix">
                                                {{trans('lang.fix')}}
                                            </option>
                                            <option value="percentage">
                                                {{trans('lang.percentage')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.tax_scope')}}<span
                                                class="required-field"></span></label>
                                    <div class="col-7">
                                        <select class="form-control tax_scope">
                                            <option value="">
                                                {{trans('lang.select_tax_scope')}}
                                            </option>
                                            <option value="admin_commission">
                                                {{trans('lang.admin_commission_tax')}}
                                            </option>
                                            <option value="delivery">
                                                {{trans('lang.delivery_wise_tax')}}
                                            </option>
                                            <option value="order">
                                                {{trans('lang.order_wise_tax')}}
                                            </option>
                                            <option value="packaging">
                                                {{trans('lang.packaging_wise_tax')}}
                                            </option>
                                            <option value="platform">
                                                {{trans('lang.platform_wise_tax')}}
                                            </option>
                                            <option value="product">
                                                {{trans('lang.product_wise_tax')}}
                                            </option>
                                            <option value="vendor_subscription">
                                                {{trans('lang.vendor_subscription_tax')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row width-50 country_div">
                                    <label class="col-3 control-label">{{trans('lang.country')}}<span
                                                class="required-field"></span></label>
                                    <div class="col-7">
                                        <select name="country" id="country" class="form-control tax_country">
                                            @foreach($countries_data as $country)
                                                <option
                                                        value="{{$country->countryName}}">{{$country->countryName}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text text-muted">
                                            {{ trans("lang.tax_country_help") }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{trans('lang.tax_amount')}}<span
                                                class="required-field"></span></label>
                                    <div class="col-7">
                                        <input type="number" class="form-control tax_amount" min="0">
                                        <div class="form-text text-muted w-50">
                                            {{ trans("lang.tax_amount_help") }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row width-100">
                                    <div class="form-check">
                                        <input type="checkbox" class="tax_active" id="tax_active">
                                        <label class="col-3 control-label" for="tax_active">{{trans('lang.enable')}}</label>
                                    </div>
                                    <div class="col-9 form-text text-muted">
                                        {{ trans("lang.admin_vendor_tax_help") }}
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary  edit-setting-btn"><i class="fa fa-save"></i> {{
                trans('lang.save')}}
                    </button>
                    <a href="{!! route('tax') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{
                trans('lang.cancel')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var id = "<?php echo $id;?>";
        var database = firebase.firestore();
        var ref = database.collection('tax').where("id", "==", id);
        var append_list = '';

        $(document.body).on('change', '.tax_scope', function() {
            if(jQuery(this).val() == "admin_commission" || jQuery(this).val() == "vendor_subscription"){
                $(".country_div").hide();
            }else{
                $(".country_div").show();
            }
        });

        $(document).ready(function () {
            $('.tax_menu').addClass('active');
            jQuery("#data-table_processing").show();
            ref.get().then(async function (snapshots) {
                var data = snapshots.docs[0].data();
                $(".tax_title").val(data.title);
                $(".tax_scope").val(data.scope);
                $(".tax_type").val(data.type);
                $(".tax_country").val(data.country);
                if(data.scope == "admin_commission" || data.scope == "vendor_subscription"){
                    $(".country_div").hide();
                }
                $('.tax_amount').val(data.tax);
                if (data.enable) {
                    $('.tax_active').prop('checked', true);
                }
                jQuery("#data-table_processing").hide();
            });
            $(".edit-setting-btn").click(function () {
                var title = $(".tax_title").val();
                var country = $(".tax_country").val();
                var type = $(".tax_type :selected").val();
                var scope = $(".tax_scope :selected").val();
                var tax = $(".tax_amount").val();
                var enable = $(".tax_active").is(':checked');
                
                if (title == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.tax_title_error')}}</p>");
                    window.scrollTo(0, 0);
                }else if (scope == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.tax_scope_error')}}</p>");
                    window.scrollTo(0, 0);
                } else if (tax == '' || tax <= 0) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{trans('lang.tax_amount_error')}}</p>");
                    window.scrollTo(0, 0);
                } else {
                    jQuery("#overlay").show();
                    database.collection('tax').doc(id).update({
                        'title': title,
                        'country': (scope == "admin_commission" || scope == "vendor_subscription" ? null : country),
                        'tax': tax,
                        'type': type,
                        'enable': enable,
                        'scope': scope,
                    }).then(function (result) {
                        jQuery("#overlay").hide();
                        window.location.href = '{{ route("tax")}}';
                    });
                }
            })
        })
    </script>
@endsection
