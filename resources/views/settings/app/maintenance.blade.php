@extends('layouts.app')
@section('content')
    <div class="page-wrapper">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">{{ trans('lang.maintenance_mode') }}</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('lang.maintenance_mode') }}</li>
                </ol>
            </div>
        </div>
        <div class="card-body">
            <div class="error_top" style="display:none"></div>
            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner">
                    <fieldset>
                        <legend>{{ trans('lang.maintenance_mode') }}</legend>
                        <div class="form-check width-100">
                            <input type="checkbox" class="form-check-inline" id="isMaintenanceMode">
                            <label class="col-5 control-label"
                                for="isMaintenanceMode">{{ trans('lang.enable_maintenance_mode_web') }}</label>
                        </div>
                        <div class="form-check width-100">
                            <input type="checkbox" class="form-check-inline" id="isMaintenanceModeForRestaurant">
                            <label class="col-5 control-label"
                                for="isMaintenanceModeForRestaurant">{{ trans('lang.enable_maintenance_mode_restaurant') }}</label>
                        </div>
                        <div class="form-check width-100">
                            <input type="checkbox" class="form-check-inline" id="isMaintenanceModeForDriverApp">
                            <label class="col-5 control-label"
                                for="isMaintenanceModeForDriverApp">{{ trans('lang.enable_maintenance_mode_driver_app') }}</label>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="form-group col-12 text-center btm-btn">
            <button type="button" class="btn btn-primary edit-setting-btn"><i class="fa fa-save"></i>
                {{ trans('lang.save') }}</button>
            <a href="{{ url('/dashboard') }}" class="btn btn-default"><i class="fa fa-undo"></i>{{ trans('lang.cancel') }}
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var database = firebase.firestore();

        var refGlobal = database.collection('settings').doc("maintenance_mode_settings");

        const $maintenanceWeb = $('#isMaintenanceMode');
        const $maintenanceRestaurant = $('#isMaintenanceModeForRestaurant');
        const $maintenanceDriverApp = $('#isMaintenanceModeForDriverApp');
        const $saveBtn = $('.edit-setting-btn');

        // Load current values when page loads
        $(document).ready(function () {
            $(".page-overlay").show(); // show loader while loading

            refGlobal.get().then((doc) => {
                if (doc.exists) {
                    const data = doc.data();

                    $maintenanceWeb.prop('checked', data.customerApp === true);
                    $maintenanceRestaurant.prop('checked', data.restaurantApp === true);
                    $maintenanceDriverApp.prop('checked', data.driverApp === true);
                }
            }).catch((error) => {
                console.error("Error loading settings:", error);
                toastr.error("Failed to load settings");
            }).finally(() => {
                $(".page-overlay").hide(); // hide loader
            });
        });

        // Save button click
        $saveBtn.on('click', function () {
        

            $(".page-overlay").show(); // show full-screen loader

            const updatedData = {
                customerApp: $maintenanceWeb.is(':checked'),
                restaurantApp: $maintenanceRestaurant.is(':checked'),
                driverApp: $maintenanceDriverApp.is(':checked'),
            };

            refGlobal.update(updatedData)
                .then(() => {
                    // Success - you can show a small toast if you have toastr/sweetalert
                    // Or just do nothing - silent success
                    console.log("Maintenance mode settings saved");
                })
                .catch((error) => {
                    console.error("Error saving settings:", error);
                    toastr.error("Failed to save settings");
                })
                .finally(() => {
                    $(".page-overlay").hide(); // always hide loader
                });
        });

       
    </script>
@endsection
