@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Driver Cash Balances</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('drivers') }}">{{ trans('lang.driver_plural') }}</a></li>
                <li class="breadcrumb-item active">Cash Balances</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
        @endif

        <div class="admin-top-section">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex top-title-section pb-4 justify-content-between">
                        <div class="d-flex top-title-left align-self-center">
                            <span class="icon mr-3"><img src="{{ asset('images/driver.png') }}"></span>
                            <h3 class="mb-0">Driver Cash Balances</h3>
                            <span id="driver_count" class="counter ml-3"></span>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <a href="{{ route('drivers.deposits') }}" class="btn btn-info ml-2">
                                <i class="mdi mdi-cash-multiple mr-1"></i> Cash Deposits
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header border-0">
                            <h3 class="text-dark-2 mb-1 h4">Driver Cash Balance Overview</h3>
                            <p class="mb-0 text-muted">Drivers who collect COD payments accumulate a cash balance. Block threshold is configurable per driver.</p>
                        </div>
                        <div class="card-body">
                            <div id="loading" class="text-center py-4">
                                <i class="mdi mdi-loading mdi-spin mdi-48px text-muted"></i>
                                <p class="mt-2 text-muted">Loading drivers...</p>
                            </div>
                            <div class="table-responsive m-t-10" id="table-container" style="display:none;">
                                <table id="cashBalanceTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Driver</th>
                                            <th>Phone</th>
                                            <th>Cash Balance (TND)</th>
                                            <th>Platform Debt (TND)</th>
                                            <th>Limit (TND)</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="driver-rows"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Set Limit Modal -->
<div class="modal fade" id="limitModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Cash Limit</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="limitForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Cash Limit (TND)</label>
                        <input type="number" name="limit" id="limitInput" class="form-control" min="0" step="10" required>
                        <small class="text-muted">Driver is blocked when cashBalance reaches this amount.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Limit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var GLOBAL_CASH_LIMIT = 300;

// Fetch globalSettings cash limit
database.collection('settings').doc('globalSettings').get().then(function(doc) {
    if (doc.exists && doc.data().driverCashBalanceLimit) {
        GLOBAL_CASH_LIMIT = parseFloat(doc.data().driverCashBalanceLimit);
    }
});

database.collection('users').where('role', '==', 'driver').get().then(function(snapshot) {
    var rows = '';
    var count = 0;

    snapshot.forEach(function(doc) {
        var d           = doc.data();
        var name        = (d.firstName || '') + ' ' + (d.lastName || '');
        var phone       = d.phoneNumber || '-';
        var cash        = parseFloat(d.cashBalance  || 0).toFixed(2);
        var debt        = parseFloat(d.platformDebt || 0).toFixed(2);
        var limit       = parseFloat(d.cashBalanceLimit || GLOBAL_CASH_LIMIT).toFixed(2);
        var isBlocked   = d.blockedReason === 'cash_limit_reached';
        var isActive    = d.isActive !== false;

        // Progress bar
        var pct    = Math.min(100, Math.round((parseFloat(cash) / parseFloat(limit)) * 100));
        var barCls = pct < 60 ? 'success' : pct < 85 ? 'warning' : 'danger';
        var progress = '<div class="progress" style="min-width:100px;height:8px;">' +
            '<div class="progress-bar bg-' + barCls + '" style="width:' + pct + '%" title="' + pct + '%"></div>' +
            '</div><small class="text-muted">' + pct + '%</small>';

        // Status badge
        var statusBadge;
        if (isBlocked) {
            statusBadge = '<span class="badge badge-danger">Blocked – Cash Limit</span>';
        } else if (!isActive) {
            statusBadge = '<span class="badge badge-warning">Inactive</span>';
        } else {
            statusBadge = '<span class="badge badge-success">Active</span>';
        }

        // Actions
        var resetBtn = '<form method="POST" action="{{ url("drivers") }}/' + doc.id + '/reset-cash" style="display:inline;" onsubmit="return confirm(\'Reset cash balance to 0 and unblock driver?\');">' +
            '@csrf' +
            '<button type="submit" class="btn btn-sm btn-warning mr-1" title="Reset Balance"><i class="mdi mdi-refresh"></i> Reset</button>' +
            '</form>';

        var unblockBtn = isBlocked
            ? '<form method="POST" action="{{ url("drivers") }}/' + doc.id + '/unblock" style="display:inline;" onsubmit="return confirm(\'Unblock this driver?\');">' +
              '@csrf' +
              '<button type="submit" class="btn btn-sm btn-success mr-1" title="Unblock"><i class="mdi mdi-lock-open"></i> Unblock</button>' +
              '</form>'
            : '';

        var limitBtn = '<button class="btn btn-sm btn-info" onclick="openLimitModal(\'' + doc.id + '\',' + limit + ')" title="Set Limit"><i class="mdi mdi-tune"></i> Limit</button>';

        rows += '<tr>' +
            '<td>' + name.trim() + '</td>' +
            '<td>' + phone + '</td>' +
            '<td><strong>' + cash + '</strong></td>' +
            '<td>' + debt + '</td>' +
            '<td>' + limit + '</td>' +
            '<td>' + progress + '</td>' +
            '<td>' + statusBadge + '</td>' +
            '<td>' + resetBtn + unblockBtn + limitBtn + '</td>' +
            '</tr>';
        count++;
    });

    document.getElementById('driver-rows').innerHTML = rows;
    document.getElementById('driver_count').textContent = '(' + count + ')';
    document.getElementById('loading').style.display = 'none';
    document.getElementById('table-container').style.display = '';

    if ($.fn.DataTable) {
        $('#cashBalanceTable').DataTable({
            order: [[2, 'desc']],
            columnDefs: [{ orderable: false, targets: [5, 7] }],
            pageLength: 25,
            language: datatableLang
        });
    }
}).catch(function(err) {
    document.getElementById('loading').innerHTML = '<div class="alert alert-danger">Failed to load drivers: ' + err.message + '</div>';
});

function openLimitModal(driverId, currentLimit) {
    document.getElementById('limitForm').action = '{{ url("drivers") }}/' + driverId + '/set-limit';
    document.getElementById('limitInput').value = currentLimit;
    $('#limitModal').modal('show');
}
</script>
@endsection
