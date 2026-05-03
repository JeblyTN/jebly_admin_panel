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
                <li class="breadcrumb-item active">Livreurs &amp; Frais de livraison</li>
            </ol>
        </div>
    </div>

    <div class="card-body">
        <div class="row restaurant_payout_create">
            <div class="restaurant_payout_create-inner">

                <fieldset>
                    <legend>Limites de solde cash (livreurs)</legend>
                    <div class="form-group row width-100">
                        <label class="col-4 control-label">Solde cash max (général)</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="driverCashBalanceLimit" placeholder="Ex: 200">
                        </div>
                    </div>
                    <div class="form-group row width-100">
                        <label class="col-4 control-label">Seuil minimum (alerte)</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="driverCashLimitMin" placeholder="Ex: 50">
                        </div>
                    </div>
                    <div class="form-group row width-100">
                        <label class="col-4 control-label">Seuil maximum (blocage)</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="driverCashLimitMax" placeholder="Ex: 300">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mt-4">
                    <legend>Répartition des frais de livraison</legend>
                    <div class="form-group row width-100">
                        <label class="col-4 control-label">Part plateforme (%)</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="platformDeliveryFeePercent" placeholder="Ex: 30" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-group row width-100">
                        <label class="col-4 control-label">Part livreur (%)</label>
                        <div class="col-7">
                            <input type="number" class="form-control" id="driverDeliveryFeePercent" placeholder="Ex: 70" min="0" max="100">
                        </div>
                    </div>
                </fieldset>

            </div>
        </div>

        <div class="form-group col-12 text-center mt-3">
            <button type="button" class="btn btn-primary" id="save-driver-delivery-btn">
                <i class="fa fa-save"></i> Enregistrer
            </button>
        </div>

        <div id="driver-delivery-save-msg" class="alert alert-success col-8 offset-2 mt-2" style="display:none;">
            Paramètres enregistrés avec succès.
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const docRef = database.collection('settings').doc('globalSettings');
    const fields = ['driverCashBalanceLimit', 'driverCashLimitMin', 'driverCashLimitMax', 'platformDeliveryFeePercent', 'driverDeliveryFeePercent'];

    // Load current values
    docRef.get().then(doc => {
        if (!doc.exists) return;
        const d = doc.data();
        fields.forEach(f => {
            const el = document.getElementById(f);
            if (el && d[f] !== undefined) el.value = d[f];
        });
    });

    // Save
    document.getElementById('save-driver-delivery-btn').addEventListener('click', function () {
        const data = {};
        fields.forEach(f => {
            const el = document.getElementById(f);
            if (el) data[f] = parseFloat(el.value) || 0;
        });
        docRef.set(data, { merge: true }).then(() => {
            const msg = document.getElementById('driver-delivery-save-msg');
            msg.style.display = 'block';
            setTimeout(() => msg.style.display = 'none', 3000);
        });
    });
})();
</script>
@endsection
