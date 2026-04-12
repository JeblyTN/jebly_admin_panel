@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <?php if ($id != '') { ?>
                <h3 class="text-themecolor restaurant_name_heading"></h3>
                <?php } else { ?>
                <h3 class="text-themecolor">{{ trans('lang.food_plural') }}</h3>
                <?php } ?>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{!! route('dashboard') !!}">{{ trans('lang.dashboard') }}</a>
                    </li>
                    <?php if ($id != '') { ?>
                    <li class="breadcrumb-item"><a href="{{ route('restaurants.foods', $id) }}">{{ trans('lang.food_plural') }}</a></li>
                    <?php } else { ?>
                    <li class="breadcrumb-item"><a href="{!! route('foods') !!}">{{ trans('lang.food_plural') }}</a></li>
                    <?php } ?>
                    <li class="breadcrumb-item active">{{ trans('lang.food_create') }}</li>
                </ol>
            </div>
        </div>

        <div>
            <div class="card-body">
                <div class="error_top" style="display:none"></div>
                <div class="row restaurant_payout_create">
                    <div class="restaurant_payout_create-inner">
                        <fieldset>
                            <legend>{{ trans('lang.food_information') }}</legend>
                            <div class="form-group row width-100" id="admin_commision_info" style="display:none">
                                <div class="m-3">
                                    <div class="form-text font-weight-bold text-danger h6">
                                        {{ trans('lang.price_instruction') }}
                                    </div>
                                    <div class="form-text font-weight-bold text-danger h6" id="admin_commision"></div>
                                </div>
                            </div>
                            <div class="form-group row width-100">
                                @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
                                    <div class="col-12"> 
                                        <label class="control-label">{{ trans('lang.item_name') }}</label>
                                        <button type="button" class="btn bg-white text-primary generate_btn_wrapper opacity-1 pl-1 mb-2 auto_fill_title"
                                            data-error="{{ trans('lang.ai_name_error') }}"
                                            data-lang="{{ App::getLocale() }}"
                                            data-route="{{ route('ai.title-auto-fill') }}">
                                            <div class="btn-svg-wrapper">
                                                <img width="18" height="18" class="" src="{{ asset('images/svg/blink-icon-orange.svg') }}" alt="">
                                            </div>
                                            <span class="ai-text-animation d-none" role="status">
                                                {{ trans('lang.ai_just_asecond') }}
                                            </span>
                                            <span class="btn-text">{{ trans('lang.ai_generate') }}</span>
                                        </button>
                                        <div class="col-7 outline-wrapper">
                                            <input type="text" class="form-control" id="item_name" required>
                                        </div>
                                    </div>
                                @else
                                    <label class="control-label col-3">{{ trans('lang.item_name') }}</label>
                                    <div class="col-7">
                                        <input type="text" class="form-control" id="item_name" required>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row width-100 desciption-wrapper">
                                @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
                                    <div class="col-12"> 
                                        <label class="control-label">{{ trans('lang.item_description') }}</label>
                                        <button type="button" class="btn bg-white text-primary generate_btn_wrapper opacity-1 pl-1 mb-2 auto_fill_description"
                                            data-error="{{ trans('lang.ai_description_error') }}"
                                            data-lang="{{ App::getLocale() }}"
                                            data-route="{{ route('ai.description-auto-fill') }}">
                                            <div class="btn-svg-wrapper">
                                                <img width="18" height="18" class="" src="{{ asset('images/svg/blink-icon-orange.svg') }}" alt="">
                                            </div>
                                            <span class="ai-text-animation d-none" role="status">
                                                {{ trans('lang.ai_just_asecond') }}
                                            </span>
                                            <span class="btn-text">{{ trans('lang.ai_generate') }}</span>
                                        </button>
                                        <div class="col-7 outline-wrapper">
                                            <textarea rows="8" class="form-control" id="item_description"></textarea>
                                        </div>
                                    </div>
                                @else
                                    <label class="control-label col-3">{{ trans('lang.item_description') }}</label>
                                    <div class="col-7">
                                        <textarea rows="8" class="form-control" id="item_description"></textarea>
                                    </div>    
                                @endif
                            </div>

                             <div class="form-group row width-100">
                                <label class="col-3 control-label">{{ trans('lang.food_image') }}</label>
                                <div class="col-7">
                                    <input type="file" id="product_image">
                                    <div class="placeholder_img_thumb product_image"></div>
                                    <div id="uploding_image"></div>
                                    <div class="form-text text-muted">
                                        {{ trans('lang.food_image_help') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.item_price') }}</label>
                                <div class="col-7">
                                    <input type="text" class="form-control" id="item_price" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.item_discount') }}</label>
                                <div class="col-7">
                                    <input class="form-control item_discount" id="item_discount" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                </div>
                            </div>

                            <div class="form-group row width-50">
                                <label class="col-3 control-label">{{ trans('lang.item_quantity') }}</label>
                                <div class="col-7">
                                    <input type="number" class="form-control item_quantity" id="item_quantity" value="-1">
                                    <div class="form-text text-muted">
                                        {{ trans('lang.item_quantity_help') }}
                                    </div>
                                </div>
                            </div>

                            <div class="variation_wrapper">
                                @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
                                <div class="width-100 text-right">
                                    <button type="button" class="btn bg-white text-primary generate_btn_wrapper opacity-1 pl-1 mb-2 variation_setup_auto_fill"
                                        data-error="{{ trans('lang.ai_name_description_error') }}"
                                        data-lang="{{ App::getLocale() }}"
                                        data-route="{{ route('ai.variation-setup-auto-fill') }}">
                                        <div class="btn-svg-wrapper">
                                            <img width="18" height="18" class="" src="{{ asset('images/svg/blink-icon-orange.svg') }}" alt="">
                                        </div>
                                        <span class="ai-text-animation d-none" role="status">
                                            {{ trans('lang.ai_just_asecond') }}
                                        </span>
                                        <span class="btn-text">{{ trans('lang.ai_generate') }}</span>
                                    </button>
                                </div>
                                @endif
                                <div class="outline-wrapper">
                                    <?php if ($id == '') { ?>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{ trans('lang.item_vendor_id') }}</label>
                                        <div class="col-7">
                                            <select id="item_vendor" class="form-control" required>
                                                <option value="">{{ trans('lang.select_restaurant') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group row width-50">
                                        <label class="col-3 control-label">{{ trans('lang.item_category_id') }}</label>
                                        <div class="col-7">
                                            <select id='item_category' class="form-control" required>
                                                <option value="">{{ trans('lang.select_category') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row width-100" id="attributes_div">
                                        <label class="col-3 control-label">{{ trans('lang.item_attribute_id') }}</label>
                                        <div class="col-7">
                                            <select id='item_attribute' class="form-control chosen-select" required multiple="multiple" onchange="selectAttribute();"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row width-100">
                                        <div class="item_attributes" id="item_attributes"></div>
                                        <div class="item_variants" id="item_variants"></div>
                                        <input type="hidden" id="attributes" value="" />
                                        <input type="hidden" id="variants" value="" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-check width-100">
                                <input type="checkbox" class="food_publish" id="food_publish">
                                <label class="col-3 control-label" for="food_publish">{{ trans('lang.food_publish') }}</label>
                            </div>

                            <div class="form-check width-100">
                                <input type="checkbox" class="food_nonveg" id="food_nonveg">
                                <label class="col-3 control-label" for="food_nonveg">{{ trans('lang.non_veg') }}</label>
                            </div>
                            
                            <div class="form-check width-100">
                                <input type="checkbox" class="food_take_away_option" id="food_take_away_option">
                                <label class="col-3 control-label" for="food_take_away_option">{{ trans('lang.food_take_away') }}</label>
                            </div>
                        </fieldset>
                        <fieldset class="product-taxes d-none">
                            <legend>{{ trans('lang.tax_settings') }}</legend>
                            <div class="form-group row">
                                <label class="col-3 control-label">{{ trans('lang.select_taxes') }}</label>
                                <div class="col-7">
                                    <select id="taxes" class="form-control chosen-select" multiple="multiple"></select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="ingredients-wrapper">
                            <legend>{{ trans('lang.ingredients') }}</legend>
                            @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
                            <div class="width-100 text-right">
                                <button type="button" class="btn bg-white text-primary generate_btn_wrapper opacity-1 pl-1 mb-2 ingredients_auto_fill"
                                    data-error="{{ trans('lang.ai_ingredients_error') }}"
                                    data-lang="{{ App::getLocale() }}"
                                    data-route="{{ route('ai.ingredients-auto-fill') }}">
                                    <div class="btn-svg-wrapper">
                                        <img width="18" height="18" class="" src="{{ asset('images/svg/blink-icon-orange.svg') }}" alt="">
                                    </div>
                                    <span class="ai-text-animation d-none" role="status">
                                        {{ trans('lang.ai_just_asecond') }}
                                    </span>
                                    <span class="btn-text">{{ trans('lang.ai_generate') }}</span>
                                </button>
                            </div>
                            @endif
                            <div class="outline-wrapper">
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{ trans('lang.calories') }}</label>
                                    <div class="col-7">
                                        <input type="number" class="form-control food_calories">
                                    </div>
                                </div>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{ trans('lang.grams') }}</label>
                                    <div class="col-7">
                                        <input type="number" class="form-control food_grams">
                                    </div>
                                </div>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{ trans('lang.fats') }}</label>
                                    <div class="col-7">
                                        <input type="number" class="form-control food_fats">
                                    </div>
                                </div>
                                <div class="form-group row width-50">
                                    <label class="col-3 control-label">{{ trans('lang.proteins') }}</label>
                                    <div class="col-7">
                                        <input type="number" class="form-control food_proteins">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="addons-wrapper">
                            <legend>{{ trans('lang.food_add_one') }}</legend>
                            @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
                            <div class="width-100 text-right">
                                <button type="button" class="btn bg-white text-primary generate_btn_wrapper opacity-1 pl-1 mb-2 addons_auto_fill"
                                    data-error="{{ trans('lang.ai_addons_error') }}"
                                    data-lang="{{ App::getLocale() }}"
                                    data-route="{{ route('ai.addons-auto-fill') }}">
                                    <div class="btn-svg-wrapper">
                                        <img width="18" height="18" class="" src="{{ asset('images/svg/blink-icon-orange.svg') }}" alt="">
                                    </div>
                                    <span class="ai-text-animation d-none" role="status">
                                        {{ trans('lang.ai_just_asecond') }}
                                    </span>
                                    <span class="btn-text">{{ trans('lang.ai_generate') }}</span>
                                </button>
                            </div>
                            @endif
                            <div class="outline-wrapper">
                                <div class="form-group add_ons_list extra-row">
                                </div>
                                <div class="form-group row width-100">
                                    <div class="col-7">
                                        <button type="button" onclick="addOneFunction()" class="btn btn-primary" id="add_one_btn">{{ trans('lang.food_add_one') }}</button>
                                    </div>
                                </div>
                                <div class="form-group row width-100" id="add_ones_div" style="display:none">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="col-3 control-label">{{ trans('lang.food_title') }}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control add_ons_title">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="col-3 control-label">{{ trans('lang.item_price') }}</label>
                                            <div class="col-7">
                                                <input type="number" class="form-control add_ons_price">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row save_add_one_btn width-100" style="display:none">
                                    <div class="col-7">
                                        <button type="button" onclick="saveAddOneFunction()" class="btn btn-primary">{{ trans('lang.save_add_ones') }}</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="specification-wrapper">
                            <legend>{{ trans('lang.product_specification') }}</legend>
                            @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
                            <div class="width-100 text-right">
                                <button type="button" class="btn bg-white text-primary generate_btn_wrapper opacity-1 pl-1 mb-2 specification_auto_fill"
                                    data-error="{{ trans('lang.ai_specification_error') }}"
                                    data-lang="{{ App::getLocale() }}"
                                    data-route="{{ route('ai.specification-auto-fill') }}">
                                    <div class="btn-svg-wrapper">
                                        <img width="18" height="18" class="" src="{{ asset('images/svg/blink-icon-orange.svg') }}" alt="">
                                    </div>
                                    <span class="ai-text-animation d-none" role="status">
                                        {{ trans('lang.ai_just_asecond') }}
                                    </span>
                                    <span class="btn-text">{{ trans('lang.ai_generate') }}</span>
                                </button>
                            </div>
                            @endif
                            <div class="outline-wrapper">
                                <div class="form-group product_specification extra-row">
                                </div>
                                <div class="form-group row width-100">
                                    <div class="col-7">
                                        <button type="button" onclick="addProductSpecificationFunction()" class="btn btn-primary" id="add_one_btn">
                                            {{ trans('lang.add_product_specification') }}</button>
                                    </div>
                                </div>
                                <div class="form-group row width-100" id="add_product_specification_div" style="display:none">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="col-2 control-label">{{ trans('lang.lable') }}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control add_label">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="col-3 control-label">{{ trans('lang.value') }}</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control add_value">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row save_product_specification_btn width-100" style="display:none">
                                    <div class="col-7">
                                        <button type="button" onclick="saveProductSpecificationFunction()" class="btn btn-primary">{{ trans('lang.save_product_specification') }}</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div class="form-group col-12 text-center btm-btn">
                    <button type="button" class="btn btn-primary  save-form-btn"><i class="fa fa-save"></i>
                        {{ trans('lang.save') }}</button>
                    <?php if ($id != '') { ?>
                    <a href="{{ route('restaurants.foods', $id) }}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
                    <?php } else { ?>
                    <a href="{!! route('foods') !!}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel') }}</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    @includeif('layouts.ai_sidebar')

@endsection

@section('scripts')

    @if (isset($openai_settings) && data_get($openai_settings, 'status') == true)
        <link href="{{ asset('css/AI/ai-sidebar.css') }}" rel="stylesheet">
        <script src="{{ asset('js/AI/product-details-autofill.js') }}"></script>
        <script src="{{ asset('js/AI/variation-setup-auto-fill.js') }}"></script>
        <script src="{{ asset('js/AI/ai-sidebar.js') }}"></script>
        <script src="{{ asset('js/AI/compressor/image-compressor.js')}}"></script>
        <script src="{{ asset('js/AI/compressor/compressor.min.js')}}"></script>
    @endif

    <script>

        var database = firebase.firestore();
        var photo = "";
        var addOnesTitle = [];
        var addOnesPrice = [];
        var sizeTitle = [];
        var sizePrice = [];
        var attributes_list = [];
        var categories_list = [];
        var restaurant_list = [];
        var productImagesCount = 0;
        var product_specification = {};
        var photos = [];
        var product_image_filename = [];
        var variant_photos = [];
        var variant_filename = [];
        var variant_vIds = [];
        var reataurantIDDirec = "<?php echo $id; ?>";
        var itemLimit = -1;
        var restaurant = '';
        
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;
            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
        });

        var refAdminCommission = database.collection('settings').doc("AdminCommission");
        refAdminCommission.get().then(async function(snapshots) {
            var adminCommissionSettings = snapshots.data();
            if (adminCommissionSettings) {
                var commission_type = adminCommissionSettings.commissionType;
                var commission_value = adminCommissionSettings.fix_commission;
                if (commission_type == "Percent") {
                    var commission_text = commission_value + '%';
                } else {
                    if (currencyAtRight) {
                        commission_text = parseFloat(commission_value).toFixed(decimal_degits) + "" +
                            currentCurrency;
                    } else {
                        commission_text = currentCurrency + "" + parseFloat(commission_value).toFixed(
                            decimal_degits);
                    }
                }
                if (adminCommissionSettings.isEnabled) {
                    $("#admin_commision_info").show();
                    $("#admin_commision").html('{{trans("lang.admin_commission")}}: ' + commission_text);
                }
            }
        });

        database.collection('vendor_products').where('vendorID', '==', reataurantIDDirec).get().then(async function(snapshot) {
            totalProductCount = snapshot.docs.length;
        })

        //restaurant_name_heading
        $(document).ready(function() {
            jQuery(document).on("click", ".mdi-cloud-upload", function() {
                var variant = jQuery(this).data('variant');
                var photo_remove = $(this).attr('data-img');
                index = variant_photos.indexOf(photo_remove);
                if (index > -1) {
                    variant_photos.splice(index, 1); // 2nd parameter means remove one item only
                }
                var file_remove = $(this).attr('data-file');
                fileindex = variant_filename.indexOf(file_remove);
                if (fileindex > -1) {
                    variant_filename.splice(fileindex, 1); // 2nd parameter means remove one item only
                }
                variantindex = variant_vIds.indexOf(variant);
                if (variantindex > -1) {
                    variant_vIds.splice(variantindex, 1); // 2nd parameter means remove one item only
                }
                $('[id="file_' + variant + '"]').click();
            });
            jQuery(document).on("click", ".mdi-delete", function() {
                var variant = jQuery(this).data('variant');
                $('[id="variant_' + variant + '_image"]').empty();
                var photo_remove = $(this).attr('data-img');
                index = variant_photos.indexOf(photo_remove);
                if (index > -1) {
                    variant_photos.splice(index, 1); // 2nd parameter means remove one item only
                }
                var file_remove = $(this).attr('data-file');
                fileindex = variant_filename.indexOf(file_remove);
                if (fileindex > -1) {
                    variant_filename.splice(fileindex, 1); // 2nd parameter means remove one item only
                }
                variantindex = variant_vIds.indexOf(variant);
                if (variantindex > -1) {
                    variant_vIds.splice(variantindex, 1); // 2nd parameter means remove one item only
                }
            });
            jQuery("#data-table_processing").show();
            database.collection('vendors').orderBy('title', 'asc').get().then(async function(snapshots) {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    restaurant_list.push(data);
                    $('#item_vendor').append($("<option></option>")
                        .attr("value", data.id)
                        .attr("data-lat", data.latitude)
                        .attr("data-long", data.longitude)
                        .text(data.title));
                    if (reataurantIDDirec == data.id) {
                        $(".restaurant_name_heading").html(data.title);
                    }
                })
            });
            database.collection('vendor_categories').where('publish', '==', true).get().then(async function(
                snapshots) {

                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    categories_list.push(data);
                })
                @if ($id != '')
                    database.collection('vendors').doc("{{ $id }}").get().then(async function(snapshot) {
                        if (snapshot.exists) {
                            var data = snapshot.data();
                            var categoryIDs = []
                            categoryIDs = data.categoryID;

                            categories_list.forEach((val) => {
                                if (categoryIDs.includes(val.id)) {
                                    $('#item_category').append($("<option></option>")
                                        .attr("value", val.id)
                                        .text(val.title));
                                }
                            })
                        }
                    })
                @else
                    categories_list.forEach((val) => {
                        $('#item_category').append($("<option></option>")
                            .attr("value", val.id)
                            .text(val.title));
                    })
                @endif


            });
            var attributes = database.collection('vendor_attributes').orderBy('title', 'asc');
            attributes.get().then(async function(snapshots) {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    attributes_list.push(data);
                    $('#item_attribute').append($("<option></option>")
                        .attr("value", data.id)
                        .text(data.title));
                })
                $("#item_attribute").show().chosen({
                    "placeholder_text": "{{ trans('lang.select_attribute') }}"
                });
            });
            
            jQuery("#data-table_processing").hide();

            $(".save-form-btn").click(async function() {

                var attributes = $('#attributes').val();
                var variants = $('#variants').val();
                var name = $("#item_name").val();
                var price = $("#item_price").val();
                
                <?php if ($id == '') { ?>
                    restaurant = $("#item_vendor option:selected").val();
                <?php } else { ?>
                    restaurant = "<?php echo $id; ?>";
                <?php } ?>
                var category = $("#item_category").val();
                var foodCalories = parseInt($(".food_calories").val());
                var foodGrams = parseInt($(".food_grams").val());
                var foodProteins = parseInt($(".food_proteins").val());
                var foodFats = parseInt($(".food_fats").val());
                var description = $("#item_description").val();
                var foodPublish = $(".food_publish").is(":checked");
                var nonveg = $(".food_nonveg").is(":checked");
                var veg = !nonveg;
                var foodTakeaway = $(".food_take_away_option").is(":checked");
                var discount = $(".item_discount").val();
                var item_quantity = $(".item_quantity").val();
                if (discount == '') {
                    discount = "0";
                }
                if (!foodCalories) {
                    foodCalories = 0;
                }
                if (!foodGrams) {
                    foodGrams = 0;
                }
                if (!foodFats) {
                    foodFats = 0;
                }
                if (!foodProteins) {
                    foodProteins = 0;
                }

                let selectedTaxes = [];
                $('#taxes option:selected').each(function() {
                    let taxData = $(this).attr('data-tax');
                    if (taxData) {
                        selectedTaxes.push(JSON.parse(decodeURIComponent(taxData)));
                    }
                });
                
                var id = database.collection("tmp").doc().id;
                if (name == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{ trans('lang.enter_item_name_error') }}</p>");
                    window.scrollTo(0, 0);
                } else if (price == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{ trans('lang.enter_item_price_error') }}</p>");
                    window.scrollTo(0, 0);
                } else if (restaurant == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{ trans('lang.select_restaurant_error') }}</p>");
                    window.scrollTo(0, 0);
                } else if (category == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{ trans('lang.select_item_category_error') }}</p>");
                    window.scrollTo(0, 0);
                } else if (parseInt(price) < parseInt(discount)) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{ trans('lang.price_should_not_less_then_discount_error') }}</p>");
                    window.scrollTo(0, 0);
                } else if (item_quantity == '' || item_quantity < -1) {
                    $(".error_top").show();
                    $(".error_top").html("");
                    if (item_quantity == '') {
                        $(".error_top").append("<p>{{ trans('lang.enter_item_quantity_error') }}</p>");
                    } else {
                        $(".error_top").append(
                            "<p>{{ trans('lang.invalid_item_quantity_error') }}</p>");
                    }
                    window.scrollTo(0, 0);
                } else if (description == '') {
                    $(".error_top").show();
                    $(".error_top").html("");
                    $(".error_top").append("<p>{{ trans('lang.enter_item_description_error') }}</p>");
                    window.scrollTo(0, 0);
                } else {
                    verifyRestaurant = await checkDocumentAndPlan(restaurant);
                    if (verifyRestaurant.documentVerify == true) {

                        if (parseInt(verifyRestaurant.itemLimit) == -1 || (parseInt(verifyRestaurant.itemLimit) > 0 && parseInt(verifyRestaurant.itemLimit) > parseInt(totalProductCount))) {
                            $(".error_top").hide();
                            //start-item attribute
                            var quantityerror = 0;
                            var priceerror = 0;
                            var attributes = [];
                            var variants = [];
                            if ($("#item_attribute").val().length > 0) {
                                if ($('#attributes').val().length > 0) {
                                    var attributes = $.parseJSON($('#attributes').val());
                                } else {
                                    alert('{{trans("lang.please_add_your_attribute_value")}}');
                                    return false;
                                }
                                if ($("#item_attribute").val().length !== attributes.length) {
                                    alert('{{trans("lang.please_add_your_attribute_value")}}');
                                    return false;
                                }
                            }

                            if ($('#variants').val().length > 0) {
                                var variantsSet = $.parseJSON($('#variants').val());
                                await storeVariantImageData().then(async (vIMG) => {
                                    $.each(variantsSet, function(key, variant) {
                                        var variant_id = uniqid();
                                        var variant_sku = variant;
                                        var variant_price = $('[id="price_' +
                                            variant + '"]').val();
                                        var variant_quantity = $('[id="qty_' +
                                            variant + '"]').val();
                                        var variant_image = $('[id="variant_' +
                                            variant + '_url"]').val();
                                        if (variant_image) {
                                            variants.push({
                                                'variant_id': variant_id,
                                                'variant_sku': variant_sku,
                                                'variant_price': variant_price,
                                                'variant_quantity': variant_quantity,
                                                'variant_image': variant_image
                                            });
                                        } else {
                                            variants.push({
                                                'variant_id': variant_id,
                                                'variant_sku': variant_sku,
                                                'variant_price': variant_price,
                                                'variant_quantity': variant_quantity
                                            });
                                        }
                                        if (variant_quantity = '' ||
                                            variant_quantity < -1 ||
                                            variant_quantity == 0) {
                                            quantityerror++;
                                        }
                                        if (variant_price == "" || variant_price <=
                                            0) {
                                            priceerror++;
                                        }
                                    });
                                }).catch(err => {
                                    jQuery("#data-table_processing").hide();
                                    $(".error_top").show();
                                    $(".error_top").html("");
                                    $(".error_top").append("<p>" + err + "</p>");
                                    window.scrollTo(0, 0);
                                });
                            }


                            var item_attribute = null;
                            if (attributes.length > 0 && variants.length > 0) {
                                if (quantityerror > 0) {
                                    alert(
                                        '{{trans("lang.please_add_your_variants_quantity_it_should_be_greater_than_minus1")}}'
                                    );
                                    return false;
                                }
                                if (priceerror > 0) {
                                    alert('{{trans("lang.please_add_your_variants_price")}}');
                                    return false;
                                }
                                var item_attribute = {
                                    'attributes': attributes,
                                    'variants': variants
                                };
                            }
                            jQuery("#data-table_processing").show();
                            await storeProductImageData().then(async (IMG) => {
                                if (IMG.length > 0) {
                                    photo = IMG[0];
                                }
                                var objects = {
                                    'name': name,
                                    'price': price.toString(),
                                    'quantity': parseInt(item_quantity),
                                    'disPrice': discount.toString(),
                                    'vendorID': restaurant,
                                    'categoryID': category,
                                    'photo': photo,
                                    'calories': foodCalories,
                                    "grams": foodGrams,
                                    'proteins': foodProteins,
                                    'fats': foodFats,
                                    'description': description,
                                    'publish': foodPublish,
                                    'nonveg': nonveg,
                                    'veg': veg,
                                    'addOnsTitle': addOnesTitle,
                                    'addOnsPrice': addOnesPrice,
                                    'takeawayOption': foodTakeaway,
                                    'product_specification': product_specification,
                                    'id': id,
                                    'item_attribute': item_attribute,
                                    'photos': IMG,
                                    'createdAt': firebase.firestore.FieldValue.serverTimestamp(),
                                    'taxSetting': selectedTaxes,
                                };
                                //end-item attribute
                                database.collection('vendor_products').doc(id).set(objects)
                                    .then(async function(result) {
                                        if (reataurantIDDirec) {
                                            window.location.href =
                                                "{{ route('restaurants.foods', $id) }}";
                                        } else {
                                            window.location.href =
                                                '{{ route('foods') }}';
                                        }
                                    });
                            }).catch(err => {
                                jQuery("#data-table_processing").hide();
                                $(".error_top").show();
                                $(".error_top").html("");
                                $(".error_top").append("<p>" + err + "</p>");
                                window.scrollTo(0, 0);
                            });
                        } else {
                            jQuery("#data-table_processing").hide();
                            $(".error_top").show();
                            $(".error_top").html("");
                            $(".error_top").append(
                                "<p>{{ trans('lang.food_limit_exceed_can_not_create_food') }}</p>");
                            window.scrollTo(0, 0);
                        }
                    } else {
                        jQuery("#data-table_processing").hide();
                        $(".error_top").show();
                        $(".error_top").html("");
                        $(".error_top").append(
                            "<p>{{ trans('lang.document_verification_pending_can_not_create_food') }}</p>"
                        );
                        window.scrollTo(0, 0);
                    }
                }
            })
        })
        async function checkDocumentAndPlan(vendorId) {
            var data = '';
            var documentVerify = false;
            var verificationEnabled = false;
            var documentSetting = database.collection('settings').doc("document_verification_settings");
            await documentSetting.get().then(async function(snapshots) {
                var data = snapshots.data();
                if (data.isRestaurantVerification == true) {
                    verificationEnabled = true;
                }
            });
            await database.collection('users').where('vendorID', '==', vendorId).where('role', '==', 'vendor').get().then(async function(snapshot) {
                if (snapshot.docs.length > 0) {
                    var userData = snapshot.docs[0].data();
                    if (verificationEnabled == true) {
                        if (userData.isDocumentVerify == true) {
                            documentVerify = true;
                        }
                    } else {
                        documentVerify = true;
                    }

                    var subscriptionModel = false;
                    var subscriptionBusinessModel = database.collection('settings').doc("restaurant");
                    await subscriptionBusinessModel.get().then(async function(snapshots) {
                        var subscriptionSetting = snapshots.data();
                        if (subscriptionSetting.subscription_model == true) {
                            subscriptionModel = true;
                        }
                    });
                    if (subscriptionModel) {
                        if (userData.hasOwnProperty('subscription_plan')) {
                            itemLimit = userData.subscription_plan.itemLimit;
                        }
                    }
                    data = {
                        'itemLimit': itemLimit,
                        'documentVerify': documentVerify
                    }
                }
            })
            return data;
        }
        var storageRef = firebase.storage().ref('images');

        function handleFileSelect(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            new Compressor(f, {
                quality: <?php echo env('IMAGE_COMPRESSOR_QUALITY', 0.8); ?>,
                success(result) {
                    f = result;
                    reader.onload = (function(theFile) {
                        return function(e) {
                            var filePayload = e.target.result;
                            var val = f.name;
                            var ext = val.split('.')[1];
                            var docName = val.split('fakepath')[1];
                            var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                            var timestamp = Number(new Date());
                            var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                            var uploadTask = storageRef.child(filename).put(theFile);
                            uploadTask.on('state_changed', function(snapshot) {
                                var progress = (snapshot.bytesTransferred / snapshot
                                    .totalBytes) * 100;
                                console.log('Upload is ' + progress + '% done');
                                jQuery("#uploding_image").text("Image is uploading...");
                            }, function(error) {}, function() {
                                uploadTask.snapshot.ref.getDownloadURL().then(function(
                                    downloadURL) {
                                    jQuery("#uploding_image").text(
                                        "{{trans('lang.upload_is_completed')}}");
                                    photo = downloadURL;
                                    $(".product_image").empty()
                                    $(".product_image").append(
                                        '<img class="rounded" style="width:50px" src="' +
                                        photo + '" alt="image">');
                                });
                            });
                        };
                    })(f);
                    reader.readAsDataURL(f);
                },
                error(err) {
                    console.log(err.message);
                },
            });
        }

        function handleFileSelectProduct(evt) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                    var timestamp = Number(new Date());
                    var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                    var uploadTask = storageRef.child(filename).put(theFile);
                    uploadTask.on('state_changed', function(snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                        console.log('Upload is ' + progress + '% done');
                        $('.product_image').find(".uploding_image_photos").text(
                            "Image is uploading...");
                    }, function(error) {}, function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            jQuery("#uploding_image").text("{{trans('lang.upload_is_completed')}}");
                            if (downloadURL) {
                                productImagesCount++;
                                photos_html = '<span class="image-item" id="photo_' +
                                    productImagesCount +
                                    '"><span class="remove-btn" data-id="' +
                                    productImagesCount + '" data-img="' + downloadURL +
                                    '"><i class="fa fa-remove"></i></span><img width="100px" id="" height="auto" src="' +
                                    downloadURL + '"></span>';
                                $(".product_image").append(photos_html);
                                photos.push(downloadURL);
                            }
                        });
                    });
                };
            })(f);
            reader.readAsDataURL(f);
        }

        function addOneFunction() {
            $("#add_ones_div").show();
            $(".save_add_one_btn").show();
        }

        function addProductSpecificationFunction() {
            $("#add_product_specification_div").show();
            $(".save_product_specification_btn").show();
        }

        function deleteProductSpecificationSingle(index) {
            delete product_specification[index];
            $("#add_product_specification_iteam_" + index).hide();
        }

        function saveProductSpecificationFunction() {
            var optionlabel = $(".add_label").val();
            var optionvalue = $(".add_value").val();
            $(".add_label").val('');
            $(".add_value").val('');
            if (optionlabel != '' && optionlabel != '') {
                product_specification[optionlabel] = optionvalue;
                $(".product_specification").append(
                    '<div class="row" style="margin-top:5px;" id="add_product_specification_iteam_' + optionlabel +
                    '"><div class="col-5"><input class="form-control" type="text" value="' + optionlabel +
                    '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' +
                    optionvalue +
                    '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick=deleteProductSpecificationSingle("' +
                    optionlabel + '")><span class="mdi mdi-delete"></span></button></div></div>');
            } else {
                alert("{{trans('lang.please_enter_label_and_value')}}");
            }
        }

        function saveAddOneFunction() {
            var optiontitle = $(".add_ons_title").val();
            var optionPrice = $(".add_ons_price").val();
            $(".add_ons_price").val('');
            $(".add_ons_title").val('');
            if (optiontitle != '' && optionPrice != '') {
                addOnesPrice.push(optionPrice.toString());
                addOnesTitle.push(optiontitle);
                var index = addOnesTitle.length - 1;
                $(".add_ons_list").append('<div class="row" style="margin-top:5px;" id="add_ones_list_iteam_' + index +
                    '"><div class="col-5"><input class="form-control" type="text" value="' + optiontitle +
                    '" disabled ></div><div class="col-5"><input class="form-control" type="text" value="' +
                    optionPrice +
                    '" disabled ></div><div class="col-2"><button class="btn" type="button" onclick="deleteAddOnesSingle(' +
                    index + ')"><span class="mdi mdi-delete"></span></button></div></div>');
            } else {
                alert("{{trans('lang.enter_title_and_price_error')}}");
            }
        }

        function deleteAddOnesSingle(index) {
            addOnesTitle.splice(index, 1);
            addOnesPrice.splice(index, 1);
            $("#add_ones_list_iteam_" + index).hide();
        }
        $(document).on("click", ".remove-btn", function() {
            var id = $(this).attr('data-id');
            var photo_remove = $(this).attr('data-img');
            $("#photo_" + id).remove();
            index = photos.indexOf(photo_remove);
            if (index > -1) {
                photos.splice(index, 1); // 2nd parameter means remove one item only
            }
        });
        async function storeVariantImageData() {
            var newPhoto = [];
            if (variant_photos.length > 0) {
                await Promise.all(variant_photos.map(async (variantPhoto, index) => {
                    variantPhoto = variantPhoto.replace(/^data:image\/[a-z]+;base64,/, "");
                    var uploadTask = await storageRef.child(variant_filename[index]).putString(
                        variantPhoto, 'base64', {
                            contentType: 'image/jpg'
                        });
                    var downloadURL = await uploadTask.ref.getDownloadURL();
                    $('[id="variant_' + variant_vIds[index] + '_url"]').val(downloadURL);
                    newPhoto.push(downloadURL);
                }));
            }
            return newPhoto;
        }

        function handleVariantFileSelect(evt, vid) {
            var f = evt.target.files[0];
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    var filePayload = e.target.result;
                    var val = f.name;
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var timestamp = Number(new Date());
                    var filename = (f.name).replace(/C:\\fakepath\\/i, '')
                    var filename = 'variant_' + vid + '_' + timestamp + '.' + ext;
                    variant_filename.push(filename);
                    variant_photos.push(filePayload);
                    variant_vIds.push(vid);
                    $('[id="variant_' + vid + '_image"]').empty();
                    $('[id="variant_' + vid + '_image"]').html('<img class="rounded" style="width:50px" src="' +
                        filePayload + '" alt="image"><i class="mdi mdi-delete" data-variant="' + vid +
                        '" data-img="' + filePayload + '" data-file="' + filename + '"></i>');
                    $('#upload_' + vid).attr('data-img', filePayload);
                    $('#upload_' + vid).attr('data-file', filename);
                };
            })(f);
            reader.readAsDataURL(f);
        }
        $("#item_vendor").change(async function() {
            var restaurant = $("#item_vendor").val();
            await database.collection('vendors').doc(restaurant).get().then(async function(snapshot) {
                if (snapshot.exists) {
                    var data = snapshot.data();
                    var categoryIDs = [];
                    categoryIDs = data.categoryID;
                    $('#item_category').empty();
                    categories_list.forEach((val) => {
                        if (categoryIDs.includes(val.id)) {
                            $('#item_category').append($("<option></option>")
                                .attr("value", val.id)
                                .text(val.title));
                        }
                    })
                }
            })
            $("#attributes_div").show();
            $("#item_attribute_chosen").css({
                'width': '100%'
            });
            var selected_vendor = this.value;


            database.collection('settings').doc('globalSettings').get().then(async function(snapshots) {
                let globalTax = snapshots.data();
                let vendorLatitude = $("#item_vendor option:selected").data('lat');
                let vendorLongitude = $("#item_vendor option:selected").data('long');
                let countryName = '';
                if(vendorLatitude && vendorLongitude){
                    countryName = await getCountryFromLatLng(vendorLatitude,vendorLongitude);
                }
                if(globalTax.taxScope == "product" && countryName){
                    $(".product-taxes").removeClass('d-none');
                    $('#taxes').chosen('destroy').empty();
                    database.collection('tax').where('enable','==',true).where('scope','==','product').where('country','==',countryName).get().then(async function(snapshots) {
                        if(snapshots.docs.length > 0){
                            snapshots.docs.forEach((listval) => {
                                var data = listval.data();
                                let taxText = data.title + ' (';
                                if (data.type === 'percentage') {
                                    taxText += data.tax + '%';
                                } else {
                                    if (currencyAtRight) {
                                        taxText += parseFloat(data.tax).toFixed(decimal_degits) + ' ' + currentCurrency;
                                    } else {
                                        taxText += currentCurrency + parseFloat(data.tax).toFixed(decimal_degits);
                                    }
                                }
                                taxText += ')';
                                $('#taxes').append(
                                    $('<option></option>')
                                        .attr('value', data.id)
                                        .attr('data-tax', encodeURIComponent(JSON.stringify(data)))
                                        .text(taxText)
                                );
                            })
                            $('#taxes').chosen({
                                width: '100%',
                                placeholder_text_multiple: '{{ trans('lang.select_taxes') }}',
                            });
                        }else{
                            $(".product-taxes").addClass('d-none');        
                        }
                    });
                }else{
                    $(".product-taxes").addClass('d-none');
                }
            });

        });

        function change_categories(selected_vendor) {
            restaurant_list.forEach((vendor) => {
                if (vendor.id == selected_vendor) {
                    $('#item_category').html('');
                    $('#item_category').append($('<option value="">{{ trans('lang.select_category') }}</option>'));
                    categories_list.forEach((data) => {
                        if (data.id) {
                            $('#item_category').html($("<option></option>")
                                .attr("value", data.id)
                                .text(data.title));
                        }
                    })
                }
            });
        }

        function selectAttribute(item_attribute = '') {
            if (item_attribute) {
                var item_attribute = $.parseJSON(atob(item_attribute));
            }
            var html = '';
            $("#item_attribute").find('option:selected').each(function() {
                var $this = $(this);
                var selected_options = [];
                if (item_attribute) {
                    $.each(item_attribute.attributes, function(index, attribute) {
                        if ($this.val() == attribute.attribute_id) {
                            selected_options.push(attribute.attribute_options);
                        }
                    });
                }
                html += '<div class="row" id="attr_' + $this.val() + '">';
                html += '<div class="col-md-3">';
                html += '<label>' + $this.text() + '</label>';
                html += '</div>';
                html += '<div class="col-lg-9">';
                html += '<input type="text" class="form-control" id="attribute_options_' + $this.val() + '" value="' + selected_options + '" placeholder="Add attribute values" data-role="tagsinput" onchange="variants_update(\'' + btoa(JSON.stringify(item_attribute)) + '\')">';
                html += '</div>';
                html += '</div>';
            });
            $("#item_attributes").html(html);
            $("#item_attributes input[data-role=tagsinput]").tagsinput();
            if ($("#item_attribute").val().length == 0) {
                $("#attributes").val('');
                $("#variants").val('');
                $("#item_variants").html('');
            }
        }

        function variants_update(item_attributeX = '') {
            if (item_attributeX) {
                var item_attributeX = $.parseJSON(atob(item_attributeX));
            }
            var html = '';
            var item_attribute = $("#item_attribute").map(function(idx, ele) {
                return $(ele).val();
            }).get();
            if (item_attribute.length > 0) {
                var attributes = [];
                var attributeSet = [];
                $.each(item_attribute, function(index, attribute) {
                    var attribute_options = $("#attribute_options_" + attribute).val();
                    if (attribute_options) {
                        var attribute_options = attribute_options.split(',');
                        attribute_options = $.map(attribute_options, function(value) {
                            return value.replace(/[^0-9a-zA-Z a]/g, '');
                        });
                        attributeSet.push(attribute_options);
                        attributes.push({
                            'attribute_id': attribute,
                            'attribute_options': attribute_options
                        });
                    }
                });
                $('#attributes').val(JSON.stringify(attributes));
                var variants = getCombinations(attributeSet);
                $('#variants').val(JSON.stringify(variants));

                if (attributeSet.length > 0) {
                    html += '<table class="table table-bordered">';
                    html += '<thead class="thead-light">';
                    html += '<tr>';
                    html += '<th class="text-center"><span class="control-label">{{trans("lang.variant")}}</span></th>';
                    html += '<th class="text-center"><span class="control-label">{{trans("lang.variant_price")}}</span></th>';
                    html += '<th class="text-center"><span class="control-label">{{trans("lang.variant_quantity")}}</span></th>';
                    html += '<th class="text-center"><span class="control-label">{{trans("lang.variant_image")}}</span></th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody>';
                    $.each(variants, function(index, variant) {
                        var variant_price = 1;
                        var variant_qty = -1;
                        var variant_image = variant_image_url = '';
                        if (item_attributeX) {
                            var variant_info = $.map(item_attributeX.variants, function(v, i) {
                                if (v.variant_sku == variant) {
                                    return v;
                                }
                            });
                            if (variant_info[0]) {
                                variant_price = variant_info[0].variant_price;
                                variant_qty = variant_info[0].variant_quantity;
                                if (variant_info[0].variant_image) {
                                    variant_image = '<img class="rounded" style="width:50px" src="' + variant_info[0].variant_image + '" alt="image"><i class="mdi mdi-delete" data-variant="' + variant + '" data-status="old"></i>';
                                    variant_image_url = variant_info[0].variant_image;
                                }
                            }
                        }
                        html += '<tr>';
                        html += '<td><label for="" class="control-label">' + variant + '</label></td>';
                        html += '<td>';
                        html += '<input type="number" id="price_' + variant + '" value="' + variant_price + '" min="0" class="form-control">';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="number" id="qty_' + variant + '" value="' + variant_qty + '" min="-1" class="form-control">';
                        html += '</td>';
                        html += '<td>';
                        html += '<div class="variant-image">';
                        html += '<div class="upload">';
                        html += '<div class="image" id="variant_' + variant + '_image">' + variant_image + '</div>';
                        html += '<div class="icon"><i class="mdi mdi-cloud-upload" data-variant="' + variant + '" id="upload_' + variant + '"></i></div>';
                        html += '</div>';
                        html += '<div id="variant_' + variant + '_process"></div>';
                        html += '<div class="input-file">';
                        html += '<input type="file" id="file_' + variant + '" onChange="handleVariantFileSelect(event,\'' + variant + '\')" class="form-control" style="display:none;">';
                        html += '<input type="hidden" id="variant_' + variant + '_url" value="' + variant_image_url + '">';
                        html += '</div>';
                        html += '</div>';
                        html += '</td>';
                        html += '</tr>';
                    });
                    html += '</tbody>';
                    html += '</table>';
                }
            }
            $("#item_variants").html(html);
        }

        function getCombinations(arr) {
            if (arr.length) {
                if (arr.length == 1) {
                    return arr[0];
                } else {
                    var result = [];
                    var allCasesOfRest = getCombinations(arr.slice(1));
                    for (var i = 0; i < allCasesOfRest.length; i++) {
                        for (var j = 0; j < arr[0].length; j++) {
                            result.push(arr[0][j] + '-' + allCasesOfRest[i]);
                        }
                    }
                    return result;
                }
            }
        }

        function uniqid(prefix = "", random = false) {
            const sec = Date.now() * 1000 + Math.random() * 1000;
            const id = sec.toString(16).replace(/\./g, "").padEnd(14, "0");
            return `${prefix}${id}${random? `.${Math.trunc(Math.random()*100000000)}`:""}`;
        }
        async function storeProductImageData() {
            var newPhoto = [];
            if (photos.length > 0) {
                await Promise.all(photos.map(async (productPhoto, index) => {
                    productPhoto = productPhoto.replace(/^data:image\/[a-z]+;base64,/, "");
                    var uploadTask = await storageRef.child(product_image_filename[index]).putString(
                        productPhoto, 'base64', {
                            contentType: 'image/jpg'
                        });
                    var downloadURL = await uploadTask.ref.getDownloadURL();
                    newPhoto.push(downloadURL);
                }));
            }
            return newPhoto;
        }
        $("#product_image").resizeImg({
            callback: function(base64str) {
                var val = $('#product_image').val().toLowerCase();
                var ext = val.split('.')[1];
                var docName = val.split('fakepath')[1];
                var filename = $('#product_image').val().replace(/C:\\fakepath\\/i, '')
                var timestamp = Number(new Date());
                var filename = filename.split('.')[0] + "_" + timestamp + '.' + ext;
                product_image_filename.push(filename);
                productImagesCount++;
                photos_html = '<span class="image-item" id="photo_' + productImagesCount +
                    '"><span class="remove-btn" data-id="' + productImagesCount + '" data-img="' + base64str +
                    '"><i class="fa fa-remove"></i></span><img class="rounded" width="50px" id="" height="auto" src="' +
                    base64str + '"></span>'
                $(".product_image").append(photos_html);
                photos.push(base64str);
                $("#product_image").val('');
            }
        });

        function handleVariantSelect(vid) {
            $("#file_" + vid).resizeImg({
                callback: function(base64str) {
                    var val = $("#file_" + vid).val().toLowerCase();
                    var ext = val.split('.')[1];
                    var docName = val.split('fakepath')[1];
                    var filename = $("#file_" + vid).val().replace(/C:\\fakepath\\/i, '')
                    var timestamp = Number(new Date());
                    var filename = 'variant_' + vid + '_' + timestamp + '.' + ext;
                    //upload base64str encoded string as a image to firebase
                    var uploadTask = storageRef.child(filename).putString(base64str.split(',')[1], "base64", {
                        contentType: 'image/' + ext
                    })
                    uploadTask.on('state_changed', function(snapshot) {
                        var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                    }, function(error) {}, function() {
                        uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                            var oldurl = $('[id="variant_' + vid + '_url"]').val();
                            if (oldurl) {
                                firebase.storage().refFromURL(oldurl).delete();
                            }
                            $('[id="variant_' + vid + '_process"]').text("Upload is completed");
                            $('[id="variant_' + vid + '_image"]').empty();
                            $('[id="variant_' + vid + '_url"]').val(downloadURL);
                            $('[id="variant_' + vid + '_image"]').html(
                                '<img class="rounded" style="width:50px" src="' +
                                downloadURL +
                                '" alt="image"><i class="mdi mdi-delete" data-variant="' +
                                vid + '"></i>');
                            setTimeout(function() {
                                $('[id="variant_' + vid + '_process"]').empty();
                            }, 1000);
                        });
                    });
                }
            });
        }
    </script>
@endsection
