@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Livreurs &amp; Frais de livraison</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('settings/app/globals') }}">{{ trans('lang.app_setting') }}</a></li>
                <li class="breadcrumb-item active">Livreurs &amp; Frais de livraison</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card-body">
            <div class="error_top" style="display:none"></div>
            <div id="success_top" class="alert alert-success" style="display:none"></div>

            <div class="row restaurant_payout_create">
                <div class="restaurant_payout_create-inner page-inn col-12">
                    <fieldset>
                        <legend><i class="mr-3 mdi mdi-truck-delivery"></i>Param&egrave;tres livreurs et frais de livraison</legend>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">Limite solde esp&egrave;ces par d&eacute;faut (TND)</label>
                            <div class="col-7">
                                <input type="number" id="driverCashBalanceLimit" class="form-control" min="0" step="10" value="300">
                                <div class="form-text text-muted">Le livreur est bloqu&eacute; lorsque son solde esp&egrave;ces atteint cette limite.</div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">Limite minimum (TND)</label>
                            <div class="col-7">
                                <input type="number" id="driverCashLimitMin" class="form-control" min="0" step="10" value="50">
                                <div class="form-text text-muted">Valeur minimale autoris&eacute;e pour la limite de solde.</div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">Limite maximum (TND)</label>
                            <div class="col-7">
                                <input type="number" id="driverCashLimitMax" class="form-control" min="0" step="50" value="1000">
                                <div class="form-text text-muted">Valeur maximale autoris&eacute;e pour la limite de solde.</div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">Part plateforme frais de livraison (%)</label>
                            <div class="col-7">
                                <input type="number" id="platformDeliveryFeePercent" class="form-control" min="0" max="100" step="1" value="30">
                                <div class="form-text text-muted">Pourcentage des frais de livraison revenant &agrave; la plateforme.</div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <label class="col-5 control-label">Part livreur frais de livraison (%)</label>
                            <div class="col-7">
                                <input type="number" id="driverDeliveryFeePercent" class="form-control" min="0" max="100" step="1" value="70">
                                <div class="form-text text-muted">Pourcentage des frais de livraison revenant au livreur.</div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <div class="col-12">
                                <div id="percent_warning" class="alert alert-warning" style="display:none">
                                    <i class="mdi mdi-alert mr-2"></i>La somme des parts plateforme + livreur doit &eacute;galer 100%.
                                </div>
                            </div>
                        </div>

                        <div class="form-group row width-100">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" id="save-btn">
                                    <i class="mdi mdi-content-save mr-1"></i> Enregistrer les param&egrave;tres
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var database = firebase.firestore();
    var globalSettingsRef = database.collection('settings').doc('globalSettings');

    // Load current values from Firestore
    $(document).ready(function() {
        jQuery("#data-table_processing").show();
        globalSettingsRef.get().then(function(doc) {
            jQuery("#data-table_processing").hide();
            if (doc.exists) {
                var data = doc.data();
                if (data.driverCashBalanceLimit !== undefined) {
                    $('#driverCashBalanceLimit').val(data.driverCashBalanceLimit);
                }
                if (data.driverCashLimitMin !== undefined) {
                    $('#driverCashLimitMin').val(data.driverCashLimitMin);
                }
                if (data.driverCashLimitMax !== undefined) {
                    $('#driverCashLimitMax').val(data.driverCashLimitMax);
                }
                if (data.platformDeliveryFeePercent !== undefined) {
                    $('#platformDeliveryFeePercent').val(data.platformDeliveryFeePercent);
                }
                if (data.driverDeliveryFeePercent !== undefined) {
                    $('#driverDeliveryFeePercent').val(data.driverDeliveryFeePercent);
                }
            }
        }).catch(function(err) {
            jQuery("#data-table_processing").hide();
            console.error('Error loading settings:', err);
        });

        // Percent sum validation
        $('#platformDeliveryFeePercent, #driverDeliveryFeePercent').on('input', function() {
            var platform = parseFloat($('#platformDeliveryFeePercent').val()) || 0;
            var driver = parseFloat($('#driverDeliveryFeePercent').val()) || 0;
            if (platform + driver !== 100) {
                $('#percent_warning').show();
            } else {
                $('#percent_warning').hide();
            }
        });
    });

    // Save settings
    $('#save-btn').click(function() {
        var driverCashBalanceLimit = parseFloat($('#driverCashBalanceLimit').val());
        var driverCashLimitMin = parseFloat($('#driverCashLimitMin').val());
        var driverCashLimitMax = parseFloat($('#driverCashLimitMax').val());
        var platformDeliveryFeePercent = parseFloat($('#platformDeliveryFeePercent').val());
        var driverDeliveryFeePercent = parseFloat($('#driverDeliveryFeePercent').val());

        // Validation
        if (isNaN(driverCashBalanceLimit) || driverCashBalanceLimit < 0) {
            $(".error_top").show().html("<p>Veuillez saisir une limite de solde valide.</p>");
            window.scrollTo(0, 0);
            return;
        }
        if (isNaN(driverCashLimitMin) || driverCashLimitMin < 0) {
            $(".error_top").show().html("<p>Veuillez saisir une limite minimum valide.</p>");
            window.scrollTo(0, 0);
            return;
        }
        if (isNaN(driverCashLimitMax) || driverCashLimitMax <= driverCashLimitMin) {
            $(".error_top").show().html("<p>La limite maximum doit &ecirc;tre sup&eacute;rieure &agrave; la limite minimum.</p>");
            window.scrollTo(0, 0);
            return;
        }
        if (isNaN(platformDeliveryFeePercent) || isNaN(driverDeliveryFeePercent)) {
            $(".error_top").show().html("<p>Veuillez saisir des pourcentages valides.</p>");
            window.scrollTo(0, 0);
            return;
        }
        if (platformDeliveryFeePercent + driverDeliveryFeePercent !== 100) {
            $(".error_top").show().html("<p>La somme des parts plateforme + livreur doit &eacute;galer 100%.</p>");
            window.scrollTo(0, 0);
            return;
        }

        $(".error_top").hide();
        jQuery("#data-table_processing").show();
        $('#save-btn').prop('disabled', true);

        globalSettingsRef.update({
            'driverCashBalanceLimit': driverCashBalanceLimit,
            'driverCashLimitMin': driverCashLimitMin,
            'driverCashLimitMax': driverCashLimitMax,
            'platformDeliveryFeePercent': platformDeliveryFeePercent,
            'driverDeliveryFeePercent': driverDeliveryFeePercent,
        }).then(function() {
            jQuery("#data-table_processing").hide();
            $('#save-btn').prop('disabled', false);
            $('#success_top').show().html('<i class="mdi mdi-check-circle mr-2"></i>Param&egrave;tres enregistr&eacute;s avec succ&egrave;s !');
            window.scrollTo(0, 0);
            setTimeout(function() { $('#success_top').fadeOut(); }, 4000);
        }).catch(function(err) {
            jQuery("#data-table_processing").hide();
            $('#save-btn').prop('disabled', false);
            $(".error_top").show().html("<p>Erreur lors de l'enregistrement : " + err.message + "</p>");
            window.scrollTo(0, 0);
        });
    });
</script>
@endsection
