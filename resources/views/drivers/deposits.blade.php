@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Cash Deposits</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('drivers.cashBalance') }}">Cash Balances</a></li>
                <li class="breadcrumb-item active">Cash Deposits</li>
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
                            <span class="icon mr-3"><i class="mdi mdi-cash-multiple mdi-36px text-primary"></i></span>
                            <h3 class="mb-0">Driver Cash Deposits</h3>
                            <span id="deposit_count" class="counter ml-3"></span>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <a href="{{ route('drivers.cashBalance') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left mr-1"></i> Back to Cash Balances
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
                            <h3 class="text-dark-2 mb-1 h4">Pending & Recent Deposits</h3>
                            <p class="mb-0 text-muted">Confirm deposits when the driver has transferred cash. This reduces their cashBalance accordingly.</p>
                        </div>
                        <div class="card-body">
                            <div id="loading" class="text-center py-4">
                                <i class="mdi mdi-loading mdi-spin mdi-48px text-muted"></i>
                                <p class="mt-2 text-muted">Loading deposits...</p>
                            </div>
                            <div class="table-responsive m-t-10" id="table-container" style="display:none;">
                                <table id="depositsTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Driver</th>
                                            <th>Amount (TND)</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Bank Ref</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="deposit-rows"></tbody>
                                </table>
                            </div>
                            <div id="empty-state" style="display:none;" class="text-center py-5">
                                <i class="mdi mdi-check-circle mdi-48px text-success"></i>
                                <p class="mt-2 text-muted">No deposits found.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Deposit Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="mdi mdi-check mr-2"></i>Confirm Deposit</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="confirmForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info" id="confirm-summary"></div>
                    <div class="form-group">
                        <label>Bank Reference / Transaction ID</label>
                        <input type="text" name="bankRef" class="form-control" placeholder="e.g. VIR-2024-0042">
                    </div>
                    <div class="form-group">
                        <label>Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any notes about this deposit..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="mdi mdi-check mr-1"></i>Confirm Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="mdi mdi-close mr-2"></i>Reject Deposit</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Reason for rejection</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Explain why this deposit is rejected..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="mdi mdi-close mr-1"></i>Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var driverCache = {};

function getDriverName(driverId) {
    return new Promise(function(resolve) {
        if (driverCache[driverId]) {
            resolve(driverCache[driverId]);
        } else {
            database.collection('users').doc(driverId).get().then(function(doc) {
                var name = doc.exists ? ((doc.data().firstName || '') + ' ' + (doc.data().lastName || '')).trim() : 'Unknown';
                driverCache[driverId] = name;
                resolve(name);
            }).catch(function() { resolve('Unknown'); });
        }
    });
}

function formatDate(ts) {
    if (!ts) return '-';
    var d = ts.toDate ? ts.toDate() : new Date(ts);
    return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
}

function statusBadge(status) {
    var map = {
        pending:   '<span class="badge badge-warning">Pending</span>',
        confirmed: '<span class="badge badge-success">Confirmed</span>',
        rejected:  '<span class="badge badge-danger">Rejected</span>',
    };
    return map[status] || '<span class="badge badge-secondary">' + status + '</span>';
}

database.collection('cashDeposits').orderBy('createdAt', 'desc').get().then(async function(snapshot) {
    document.getElementById('loading').style.display = 'none';

    if (snapshot.empty) {
        document.getElementById('empty-state').style.display = '';
        return;
    }

    document.getElementById('table-container').style.display = '';
    document.getElementById('deposit_count').textContent = '(' + snapshot.size + ')';

    var rows = '';
    for (var i = 0; i < snapshot.docs.length; i++) {
        var doc = snapshot.docs[i];
        var d   = doc.data();
        var driverName = await getDriverName(d.driverId || '');
        var amount     = parseFloat(d.amount || 0).toFixed(2);
        var isPending  = (d.status || 'pending') === 'pending';

        var actions = '';
        if (isPending) {
            actions = '<button class="btn btn-sm btn-success mr-1" onclick="openConfirmModal(\'' + doc.id + '\',\'' + driverName + '\',' + amount + ')">' +
                '<i class="mdi mdi-check"></i> Confirm</button>' +
                '<button class="btn btn-sm btn-danger" onclick="openRejectModal(\'' + doc.id + '\')">' +
                '<i class="mdi mdi-close"></i> Reject</button>';
        } else {
            actions = '<span class="text-muted">-</span>';
        }

        rows += '<tr>' +
            '<td>' + driverName + '</td>' +
            '<td><strong>' + amount + '</strong></td>' +
            '<td>' + statusBadge(d.status || 'pending') + '</td>' +
            '<td>' + formatDate(d.createdAt) + '</td>' +
            '<td>' + (d.bankRef || '-') + '</td>' +
            '<td>' + (d.notes || '-') + '</td>' +
            '<td>' + actions + '</td>' +
            '</tr>';
    }

    document.getElementById('deposit-rows').innerHTML = rows;

    if ($.fn.DataTable) {
        $('#depositsTable').DataTable({
            order: [[2, 'asc'], [3, 'desc']],
            columnDefs: [{ orderable: false, targets: [6] }],
            pageLength: 25,
            language: datatableLang
        });
    }
}).catch(function(err) {
    document.getElementById('loading').innerHTML = '<div class="alert alert-danger">Failed to load deposits: ' + err.message + '</div>';
});

function openConfirmModal(depositId, driverName, amount) {
    document.getElementById('confirmForm').action = '{{ url("driver-deposits") }}/' + depositId + '/confirm';
    document.getElementById('confirm-summary').innerHTML =
        'You are confirming a deposit of <strong>' + amount + ' TND</strong> from driver <strong>' + driverName + '</strong>.' +
        ' This will reduce their cash balance by this amount.';
    $('#confirmModal').modal('show');
}

function openRejectModal(depositId) {
    document.getElementById('rejectForm').action = '{{ url("driver-deposits") }}/' + depositId + '/reject';
    $('#rejectModal').modal('show');
}
</script>
@endsection
