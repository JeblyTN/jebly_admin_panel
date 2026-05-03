@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Surge Pricing</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item active">Surge Pricing</li>
            </ol>
        </div>
        <div>
        </div>
    </div>
    <div class="container-fluid">

    {{-- Current Status Card --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statut actuel</h5>
                </div>
                <div class="card-body">
                    <div id="surge-status-badge" class="mb-3">
                        <span class="badge badge-secondary" style="font-size:1rem;">Chargement...</span>
                    </div>
                    <div id="surge-status-details" class="text-muted small"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Activate / Deactivate Forms --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Activer Surge</h5>
                </div>
                <div class="card-body">
                    <form id="activate-form">
                        @csrf
                        <input type="hidden" name="active" value="1">
                        <div class="form-group">
                            <label>Multiplicateur</label>
                            <input type="number" class="form-control" name="multiplier" step="0.1" min="1.0" value="1.5">
                        </div>
                        <div class="form-group">
                            <label>Label (FR)</label>
                            <input type="text" class="form-control" name="labelFr" value="Forte demande">
                        </div>
                        <div class="form-group">
                            <label>Durée programmée (minutes, 0 = indéfini)</label>
                            <input type="number" class="form-control" name="scheduledMinutes" value="0" min="0">
                        </div>
                        <div class="form-group">
                            <label>Zone</label>
                            <input type="text" class="form-control" name="zone" value="all">
                        </div>
                        <button type="submit" class="btn btn-success">Activer Surge</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Désactiver Surge</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Désactivez immédiatement le surge pricing actif.</p>
                    <form id="deactivate-form">
                        @csrf
                        <input type="hidden" name="active" value="0">
                        <button type="submit" class="btn btn-danger">Désactiver Surge</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert placeholder --}}
    <div id="surge-alert" class="d-none mb-3"></div>

    {{-- Surge Rules --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Règles Surge</h5>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRuleModal">
                        + Ajouter une règle
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="rules-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Multiplicateur</th>
                                    <th>Type</th>
                                    <th>Horaire</th>
                                    <th>Zone</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="rules-tbody">
                                <tr><td colspan="7" class="text-center">Chargement...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Surge History --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Historique Surge (20 derniers)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="history-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Multiplicateur</th>
                                    <th>Zone</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <tbody id="history-tbody">
                                <tr><td colspan="5" class="text-center">Chargement...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> {{-- close container-fluid --}}
</div> {{-- close page-wrapper --}}

{{-- Add Rule Modal --}}
<div class="modal fade" id="addRuleModal" tabindex="-1" role="dialog" aria-labelledby="addRuleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRuleModalLabel">Ajouter une règle Surge</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-rule-form">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nom de la règle</label>
                        <input type="text" class="form-control" name="name" value="Surge Rule" required>
                    </div>
                    <div class="form-group">
                        <label>Multiplicateur</label>
                        <input type="number" class="form-control" name="multiplier" step="0.1" min="1.0" value="1.5" required>
                    </div>
                    <div class="form-group">
                        <label>Type de déclencheur</label>
                        <select class="form-control" name="triggerType" id="rule-trigger-type">
                            <option value="schedule">Horaire (schedule)</option>
                            <option value="auto">Automatique (auto)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Zone</label>
                        <input type="text" class="form-control" name="zone" value="all">
                    </div>

                    {{-- Schedule fields --}}
                    <div id="schedule-fields">
                        <div class="form-group">
                            <label>Jours</label>
                            <div>
                                @foreach(['Mon' => 'Lun', 'Tue' => 'Mar', 'Wed' => 'Mer', 'Thu' => 'Jeu', 'Fri' => 'Ven', 'Sat' => 'Sam', 'Sun' => 'Dim'] as $val => $label)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="days[]" value="{{ $val }}" id="day-{{ $val }}">
                                        <label class="form-check-label" for="day-{{ $val }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Heure de début</label>
                                <input type="time" class="form-control" name="startTime">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Heure de fin</label>
                                <input type="time" class="form-control" name="endTime">
                            </div>
                        </div>
                    </div>

                    {{-- Auto fields --}}
                    <div id="auto-fields" style="display:none;">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nb min de chauffeurs actifs</label>
                                <input type="number" class="form-control" name="minActiveDrivers" value="0" min="0">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nb max de chauffeurs actifs</label>
                                <input type="number" class="form-control" name="maxActiveDrivers" value="0" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer la règle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    // Route URL templates (id placeholder replaced in JS)
    var toggleRuleUrlTemplate = '{{ route('surge.toggleRule', ['id' => '__ID__']) }}';
    var deleteRuleUrlTemplate = '{{ route('surge.deleteRule', ['id' => '__ID__']) }}';
    var toggleUrl = '{{ route('surge.toggle') }}';
    var createRuleUrl = '{{ route('surge.createRule') }}';

    // Helper: show alert
    function showAlert(msg, type) {
        var el = document.getElementById('surge-alert');
        el.className = 'alert alert-' + type;
        el.textContent = msg;
        el.classList.remove('d-none');
        setTimeout(function () { el.classList.add('d-none'); }, 4000);
    }

    // Helper: fetch POST JSON
    function postJson(url, formData, cb) {
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: formData
        })
        .then(function (r) {
            if (!r.ok) {
                return r.json().then(function (err) {
                    showAlert('Erreur serveur: ' + (err.error || r.statusText), 'danger');
                    return null;
                });
            }
            return r.json();
        })
        .then(function (res) { if (res) cb(res); })
        .catch(function (err) { showAlert('Erreur: ' + err.message, 'danger'); });
    }

    // ---- Real-time status via Firestore onSnapshot ----
    database.collection('surgeStatus').doc('current').onSnapshot(function (doc) {
        var badge = document.getElementById('surge-status-badge');
        var details = document.getElementById('surge-status-details');
        if (!doc.exists) {
            badge.innerHTML = '<span class="badge badge-secondary" style="font-size:1rem;">Inactif</span>';
            details.innerHTML = '';
            return;
        }
        var d = doc.data();
        if (d.active) {
            badge.innerHTML = '<span class="badge badge-success" style="font-size:1rem;">SURGE ACTIF &times;' + (d.multiplier || '') + '</span>';
            var info = [];
            if (d.activatedAt) info.push('Activé le : ' + new Date(d.activatedAt).toLocaleString());
            if (d.zone) info.push('Zone : ' + d.zone);
            if (d.labelFr) info.push('Label : ' + d.labelFr);
            if (d.scheduledEnd) info.push('Fin programmée : ' + new Date(d.scheduledEnd).toLocaleString());
            details.innerHTML = info.join(' &bull; ');
        } else {
            badge.innerHTML = '<span class="badge badge-secondary" style="font-size:1rem;">Inactif</span>';
            details.innerHTML = '';
        }
    });

    // ---- Activate form ----
    document.getElementById('activate-form').addEventListener('submit', function (e) {
        e.preventDefault();
        postJson(toggleUrl, new FormData(this), function (res) {
            if (res.success) {
                showAlert('Surge activé avec succès.', 'success');
            } else {
                showAlert('Erreur lors de l\'activation.', 'danger');
            }
        });
    });

    // ---- Deactivate form ----
    document.getElementById('deactivate-form').addEventListener('submit', function (e) {
        e.preventDefault();
        postJson(toggleUrl, new FormData(this), function (res) {
            if (res.success) {
                showAlert('Surge désactivé avec succès.', 'success');
            } else {
                showAlert('Erreur lors de la désactivation.', 'danger');
            }
        });
    });

    // ---- Trigger type toggle in modal ----
    document.getElementById('rule-trigger-type').addEventListener('change', function () {
        if (this.value === 'auto') {
            document.getElementById('schedule-fields').style.display = 'none';
            document.getElementById('auto-fields').style.display = 'block';
        } else {
            document.getElementById('schedule-fields').style.display = 'block';
            document.getElementById('auto-fields').style.display = 'none';
        }
    });

    // ---- Load surge rules ----
    function loadRules() {
        database.collection('surgeRules').get().then(function (snapshot) {
            var tbody = document.getElementById('rules-tbody');
            var rows = [];
            snapshot.forEach(function (doc) {
                var d = doc.data();
                if (d.deleted) return;
                var scheduleInfo = '';
                if (d.triggerType === 'schedule') {
                    var days = Array.isArray(d.days) ? d.days.join(', ') : '';
                    scheduleInfo = (days ? days + ' ' : '') + (d.startTime || '') + (d.endTime ? ' - ' + d.endTime : '');
                } else if (d.triggerType === 'auto') {
                    scheduleInfo = 'Min: ' + (d.minActiveDrivers || 0) + ' / Max: ' + (d.maxActiveDrivers || 0) + ' chauffeurs';
                }
                var activeLabel = d.active
                    ? '<span class="badge badge-success">Actif</span>'
                    : '<span class="badge badge-secondary">Inactif</span>';
                var toggleUrl = toggleRuleUrlTemplate.replace('__ID__', doc.id);
                var deleteUrl = deleteRuleUrlTemplate.replace('__ID__', doc.id);
                var toggleBtnLabel = d.active ? 'Désactiver' : 'Activer';
                var toggleBtnClass = d.active ? 'btn-warning' : 'btn-success';
                var toggleActive = d.active ? '0' : '1';
                rows.push(
                    '<tr>' +
                    '<td>' + (d.name || '') + '</td>' +
                    '<td>&times;' + (d.multiplier || '') + '</td>' +
                    '<td>' + (d.triggerType || '') + '</td>' +
                    '<td>' + scheduleInfo + '</td>' +
                    '<td>' + (d.zone || '') + '</td>' +
                    '<td>' + activeLabel + '</td>' +
                    '<td>' +
                        '<button class="btn btn-sm ' + toggleBtnClass + ' mr-1" onclick="surgeToggleRule(\'' + toggleUrl + '\', \'' + toggleActive + '\')">' + toggleBtnLabel + '</button>' +
                        '<button class="btn btn-sm btn-danger" onclick="surgeDeleteRule(\'' + deleteUrl + '\')">Supprimer</button>' +
                    '</td>' +
                    '</tr>'
                );
            });
            tbody.innerHTML = rows.length ? rows.join('') : '<tr><td colspan="7" class="text-center">Aucune règle.</td></tr>';
        });
    }

    window.surgeToggleRule = function (url, active) {
        var fd = new FormData();
        fd.append('active', active);
        fd.append('_token', csrfToken);
        postJson(url, fd, function (res) {
            if (res.success) { showAlert('Règle mise à jour.', 'success'); loadRules(); }
        });
    };

    window.surgeDeleteRule = function (url) {
        if (!confirm('Confirmer la suppression ?')) return;
        var fd = new FormData();
        fd.append('_token', csrfToken);
        postJson(url, fd, function (res) {
            if (res.success) { showAlert('Règle supprimée.', 'success'); loadRules(); }
        });
    };

    loadRules();

    // ---- Add Rule form ----
    document.getElementById('add-rule-form').addEventListener('submit', function (e) {
        e.preventDefault();
        postJson(createRuleUrl, new FormData(this), function (res) {
            if (res.success) {
                showAlert('Règle créée avec succès.', 'success');
                $('#addRuleModal').modal('hide');
                loadRules();
            } else {
                showAlert('Erreur lors de la création.', 'danger');
            }
        });
    });

    // ---- Load history ----
    database.collection('surgeHistory')
        .orderBy('timestamp', 'desc')
        .limit(20)
        .get()
        .then(function (snapshot) {
            var tbody = document.getElementById('history-tbody');
            var rows = [];
            snapshot.forEach(function (doc) {
                var d = doc.data();
                var dateStr = d.timestamp ? new Date(d.timestamp).toLocaleString() : '';
                var actionLabel = d.action === 'activated'
                    ? '<span class="badge badge-success">Activé</span>'
                    : '<span class="badge badge-secondary">Désactivé</span>';
                rows.push(
                    '<tr>' +
                    '<td>' + dateStr + '</td>' +
                    '<td>' + actionLabel + '</td>' +
                    '<td>' + (d.multiplier || '') + '</td>' +
                    '<td>' + (d.zone || '') + '</td>' +
                    '<td>' + (d.activatedByAdminId || '') + '</td>' +
                    '</tr>'
                );
            });
            tbody.innerHTML = rows.length ? rows.join('') : '<tr><td colspan="5" class="text-center">Aucun historique.</td></tr>';
        });
})();
</script>
@endsection
