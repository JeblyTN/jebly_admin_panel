@extends('layouts.app')
@section('content')
<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Weekly Restaurant Payouts</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ trans('lang.dashboard') }}</a></li>
                <li class="breadcrumb-item active">Weekly Payouts</li>
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
                            <span class="icon mr-3"><i class="mdi mdi-cash-multiple" style="font-size:32px;color:#5bc236;"></i></span>
                            <h3 class="mb-0">Weekly Restaurant Payouts</h3>
                            <span id="payout_count" class="counter ml-3"></span>
                        </div>
                        <div class="d-flex top-title-right align-self-center">
                            <form method="POST" action="{{ route('weeklyPayouts.generate') }}"
                                  onsubmit="return confirm('Generate payouts for all restaurants with pending weekly accrual?\nThis will reset their weekly counters.')">
                                @csrf
                                <button type="submit" class="btn btn-info ml-2">
                                    <i class="mdi mdi-refresh mr-1"></i> Generate Payouts
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body p-3">
                        <h6 class="text-muted mb-1">Pending Payouts</h6>
                        <h4 id="totalPending" class="mb-0 text-warning">—</h4>
                        <small id="pendingCount" class="text-muted"></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body p-3">
                        <h6 class="text-muted mb-1">Paid This Month</h6>
                        <h4 id="totalPaidMonth" class="mb-0 text-success">—</h4>
                        <small id="paidCount" class="text-muted"></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body p-3">
                        <h6 class="text-muted mb-1">On Hold</h6>
                        <h4 id="totalOnHold" class="mb-0 text-danger">—</h4>
                        <small id="holdCount" class="text-muted"></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs + Table -->
        <div class="table-list">
            <div class="row">
                <div class="col-12">
                    <div class="card border">
                        <div class="card-header border-0">
                            <ul class="nav nav-tabs card-header-tabs" id="filterTabs">
                                <li class="nav-item"><a class="nav-link active" href="#" data-filter="all">All</a></li>
                                <li class="nav-item"><a class="nav-link" href="#" data-filter="pending">Pending</a></li>
                                <li class="nav-item"><a class="nav-link" href="#" data-filter="paid">Paid</a></li>
                                <li class="nav-item"><a class="nav-link" href="#" data-filter="on_hold">On Hold</a></li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div id="loading" class="text-center py-4">
                                <i class="mdi mdi-loading mdi-spin mdi-48px text-muted"></i>
                                <p class="mt-2 text-muted">Loading payouts...</p>
                            </div>
                            <div class="table-responsive m-t-10" id="table-container" style="display:none;">
                                <table id="payoutsTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Restaurant</th>
                                            <th>Period</th>
                                            <th>Amount (TND)</th>
                                            <th>Orders</th>
                                            <th>Status</th>
                                            <th>Bank Ref</th>
                                            <th>Paid At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="payout-rows"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mark Paid Modal -->
<div class="modal fade" id="markPaidModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Payout as Paid</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="markPaidForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-1">Restaurant: <strong id="modalRestaurantName"></strong></p>
                    <p class="mb-3">Amount: <strong id="modalAmount"></strong> TND</p>
                    <div class="form-group">
                        <label>Bank Reference <span class="text-danger">*</span></label>
                        <input type="text" name="bankRef" id="bankRefInput" class="form-control" required placeholder="e.g. VIR-2025-001">
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hold Modal -->
<div class="modal fade" id="holdModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Put Payout On Hold</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="holdForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Restaurant: <strong id="holdRestaurantName"></strong></p>
                    <div class="form-group">
                        <label>Reason / Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Reason for hold..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Put On Hold</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
var allPayouts   = [];
var currentFilter = 'all';

function fmt(n)   { return parseFloat(n || 0).toFixed(3); }
function fmtDate(ts) {
    if (!ts) return '—';
    var d = ts.toDate ? ts.toDate() : new Date(ts);
    return d.toLocaleDateString('fr-TN');
}
function fmtDateTime(ts) {
    if (!ts) return '—';
    var d = ts.toDate ? ts.toDate() : new Date(ts);
    return d.toLocaleString('fr-TN');
}
function statusBadge(s) {
    var map   = { pending: 'warning', paid: 'success', on_hold: 'danger' };
    var label = { pending: 'Pending', paid: 'Paid', on_hold: 'On Hold' };
    return '<span class="badge badge-' + (map[s] || 'secondary') + '">' + (label[s] || s) + '</span>';
}

function renderTable(payouts) {
    var tbody = document.getElementById('payout-rows');
    if (!payouts.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No payouts found for this filter.</td></tr>';
        return;
    }
    tbody.innerHTML = payouts.map(function(p) {
        var actions = '—';
        if (p.status === 'pending') {
            actions  = '<button class="btn btn-success btn-sm mr-1" onclick="openMarkPaid(\'' + p._id + '\',\'' + (p.restaurantName || '').replace(/'/g, '') + '\',' + (p.amount || 0) + ')">' +
                       '<i class="mdi mdi-check"></i> Mark Paid</button>' +
                       '<button class="btn btn-warning btn-sm" onclick="openHold(\'' + p._id + '\',\'' + (p.restaurantName || '').replace(/'/g, '') + '\')">' +
                       '<i class="mdi mdi-pause"></i> Hold</button>';
        }
        return '<tr>' +
            '<td>' + (p.restaurantName || '—') + '</td>' +
            '<td>' + fmtDate(p.periodStart) + ' – ' + fmtDate(p.periodEnd) + '</td>' +
            '<td><strong>' + fmt(p.amount) + '</strong></td>' +
            '<td>' + (p.orderCount || 0) + '</td>' +
            '<td>' + statusBadge(p.status) + '</td>' +
            '<td>' + (p.bankRef || '—') + '</td>' +
            '<td>' + fmtDateTime(p.paidAt) + '</td>' +
            '<td>' + actions + '</td>' +
            '</tr>';
    }).join('');
}

function updateSummary(payouts) {
    var now = new Date();
    var monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
    var pendingSum = 0, pendingCnt = 0;
    var paidSum = 0, paidCnt = 0;
    var holdSum = 0, holdCnt = 0;

    payouts.forEach(function(p) {
        if (p.status === 'pending') { pendingSum += parseFloat(p.amount || 0); pendingCnt++; }
        if (p.status === 'paid') {
            var pd = p.paidAt ? (p.paidAt.toDate ? p.paidAt.toDate() : new Date(p.paidAt)) : null;
            if (pd && pd >= monthStart) { paidSum += parseFloat(p.amount || 0); paidCnt++; }
        }
        if (p.status === 'on_hold') { holdSum += parseFloat(p.amount || 0); holdCnt++; }
    });

    document.getElementById('totalPending').textContent   = fmt(pendingSum) + ' TND';
    document.getElementById('pendingCount').textContent   = pendingCnt + ' payout(s)';
    document.getElementById('totalPaidMonth').textContent = fmt(paidSum) + ' TND';
    document.getElementById('paidCount').textContent      = paidCnt + ' this month';
    document.getElementById('totalOnHold').textContent    = fmt(holdSum) + ' TND';
    document.getElementById('holdCount').textContent      = holdCnt + ' payout(s)';
}

function applyFilter() {
    var filtered = currentFilter === 'all'
        ? allPayouts
        : allPayouts.filter(function(p) { return p.status === currentFilter; });
    renderTable(filtered);
}

// Load payouts using the pre-initialized `database` variable from layout
database.collection('weeklyPayouts').orderBy('createdAt', 'desc').limit(200)
    .get()
    .then(function(snapshot) {
        allPayouts = [];
        snapshot.forEach(function(doc) {
            allPayouts.push(Object.assign({ _id: doc.id }, doc.data()));
        });

        document.getElementById('loading').style.display = 'none';
        document.getElementById('table-container').style.display = '';
        document.getElementById('payout_count').textContent = '(' + allPayouts.length + ')';

        updateSummary(allPayouts);
        applyFilter();

        if ($.fn.DataTable) {
            $('#payoutsTable').DataTable({
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [7] }],
                pageLength: 25,
                language: datatableLang
            });
        }
    })
    .catch(function(err) {
        document.getElementById('loading').innerHTML =
            '<div class="alert alert-danger">Failed to load payouts: ' + err.message + '</div>';
    });

// Filter tabs
document.querySelectorAll('#filterTabs .nav-link').forEach(function(tab) {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('#filterTabs .nav-link').forEach(function(t) { t.classList.remove('active'); });
        tab.classList.add('active');
        currentFilter = tab.dataset.filter;
        // Destroy DataTable before re-rendering, then re-init
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#payoutsTable')) {
            $('#payoutsTable').DataTable().destroy();
        }
        applyFilter();
        if ($.fn.DataTable) {
            $('#payoutsTable').DataTable({
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: [7] }],
                pageLength: 25,
                language: datatableLang
            });
        }
    });
});

function openMarkPaid(id, name, amount) {
    document.getElementById('modalRestaurantName').textContent = name;
    document.getElementById('modalAmount').textContent = parseFloat(amount).toFixed(3);
    document.getElementById('bankRefInput').value = '';
    document.getElementById('markPaidForm').action = '{{ url("weekly-payouts") }}/' + id + '/mark-paid';
    $('#markPaidModal').modal('show');
}

function openHold(id, name) {
    document.getElementById('holdRestaurantName').textContent = name;
    document.getElementById('holdForm').action = '{{ url("weekly-payouts") }}/' + id + '/hold';
    $('#holdModal').modal('show');
}
</script>
@endsection
