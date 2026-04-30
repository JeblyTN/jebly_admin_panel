@extends('layouts.app')
@section('content')
<div class="page-wrapper">
  <div class="container-fluid">

    {{-- Header --}}
    <div class="row page-titles">
      <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">Weekly Restaurant Payouts</h3>
      </div>
      <div class="col-md-7 align-self-center text-right d-flex justify-content-end align-items-center">
        @if(session('success'))
          <div class="alert alert-success py-1 px-3 mb-0 mr-3">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('weeklyPayouts.generate') }}"
              onsubmit="return confirm('Generate payouts for all restaurants with pending weekly accrual? This will reset their weekly counters.')">
          @csrf
          <button class="btn btn-info btn-sm"><i class="mdi mdi-refresh"></i> Generate Payouts</button>
        </form>
      </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3" id="summaryCards">
      <div class="col-md-3">
        <div class="card card-outline-warning">
          <div class="card-body p-3">
            <h6 class="text-muted mb-1">Pending Payouts</h6>
            <h4 id="totalPending" class="mb-0 text-warning">—</h4>
            <small id="pendingCount" class="text-muted"></small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-outline-success">
          <div class="card-body p-3">
            <h6 class="text-muted mb-1">Paid This Month</h6>
            <h4 id="totalPaidMonth" class="mb-0 text-success">—</h4>
            <small id="paidCount" class="text-muted"></small>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-outline-danger">
          <div class="card-body p-3">
            <h6 class="text-muted mb-1">On Hold</h6>
            <h4 id="totalOnHold" class="mb-0 text-danger">—</h4>
            <small id="holdCount" class="text-muted"></small>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="filterTabs">
              <li class="nav-item"><a class="nav-link active" href="#" data-filter="all">All</a></li>
              <li class="nav-item"><a class="nav-link" href="#" data-filter="pending">Pending</a></li>
              <li class="nav-item"><a class="nav-link" href="#" data-filter="paid">Paid</a></li>
              <li class="nav-item"><a class="nav-link" href="#" data-filter="on_hold">On Hold</a></li>
            </ul>

            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="payoutsTable">
                <thead class="thead-light">
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
                <tbody id="payoutsBody">
                  <tr><td colspan="8" class="text-center text-muted py-4">Loading...</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- Mark Paid Modal --}}
<div class="modal fade" id="markPaidModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mark Payout as Paid</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form id="markPaidForm" method="POST">
        @csrf
        <div class="modal-body">
          <p>Restaurant: <strong id="modalRestaurantName"></strong></p>
          <p>Amount: <strong id="modalAmount"></strong> TND</p>
          <div class="form-group">
            <label>Bank Reference <span class="text-danger">*</span></label>
            <input type="text" name="bankRef" id="bankRefInput" class="form-control" required placeholder="e.g. VIR-2024-001">
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

{{-- Hold Modal --}}
<div class="modal fade" id="holdModal" tabindex="-1">
  <div class="modal-dialog">
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

@push('scripts')
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-firestore-compat.js"></script>
<script>
const firebaseConfig = {
  apiKey:            "{{ env('VITE_FIREBASE_API_KEY') }}",
  authDomain:        "{{ env('VITE_FIREBASE_AUTH_DOMAIN') }}",
  projectId:         "{{ env('VITE_FIREBASE_PROJECT_ID') }}",
  storageBucket:     "{{ env('VITE_FIREBASE_STORAGE_BUCKET') }}",
  messagingSenderId: "{{ env('VITE_FIREBASE_MESSAGING_SENDER_ID') }}",
  appId:             "{{ env('VITE_FIREBASE_APP_ID') }}"
};
firebase.initializeApp(firebaseConfig);
const db = firebase.firestore();

let allPayouts = [];
let currentFilter = 'all';

function fmt(n) { return parseFloat(n || 0).toFixed(3); }
function fmtDate(ts) {
  if (!ts) return '—';
  const d = ts.toDate ? ts.toDate() : new Date(ts);
  return d.toLocaleDateString('fr-TN');
}
function fmtDateTime(ts) {
  if (!ts) return '—';
  const d = ts.toDate ? ts.toDate() : new Date(ts);
  return d.toLocaleString('fr-TN');
}
function statusBadge(s) {
  const map = { pending:'warning', paid:'success', on_hold:'danger' };
  const label = { pending:'Pending', paid:'Paid', on_hold:'On Hold' };
  return `<span class="badge badge-${map[s]||'secondary'}">${label[s]||s}</span>`;
}

function renderTable(payouts) {
  const tbody = document.getElementById('payoutsBody');
  if (!payouts.length) {
    tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted py-4">No payouts found.</td></tr>';
    return;
  }
  tbody.innerHTML = payouts.map(p => `
    <tr>
      <td>${p.restaurantName || '—'}</td>
      <td>${fmtDate(p.periodStart)} – ${fmtDate(p.periodEnd)}</td>
      <td><strong>${fmt(p.amount)}</strong></td>
      <td>${p.orderCount || 0}</td>
      <td>${statusBadge(p.status)}</td>
      <td>${p.bankRef || '—'}</td>
      <td>${fmtDateTime(p.paidAt)}</td>
      <td>
        ${p.status === 'pending' ? `
          <button class="btn btn-success btn-sm mr-1" onclick="openMarkPaid('${p._id}','${(p.restaurantName||'').replace(/'/g,'&#39;')}',${p.amount})">
            <i class="mdi mdi-check"></i> Mark Paid
          </button>
          <button class="btn btn-warning btn-sm" onclick="openHold('${p._id}','${(p.restaurantName||'').replace(/'/g,'&#39;')}')">
            <i class="mdi mdi-pause"></i> Hold
          </button>` : '—'}
      </td>
    </tr>
  `).join('');
}

function updateSummary(payouts) {
  const now = new Date();
  const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);

  let pendingSum = 0, pendingCnt = 0;
  let paidSum = 0, paidCnt = 0;
  let holdSum = 0, holdCnt = 0;

  payouts.forEach(p => {
    if (p.status === 'pending')  { pendingSum += p.amount || 0; pendingCnt++; }
    if (p.status === 'paid') {
      const pd = p.paidAt ? (p.paidAt.toDate ? p.paidAt.toDate() : new Date(p.paidAt)) : null;
      if (pd && pd >= monthStart) { paidSum += p.amount || 0; paidCnt++; }
    }
    if (p.status === 'on_hold')  { holdSum += p.amount || 0; holdCnt++; }
  });

  document.getElementById('totalPending').textContent    = fmt(pendingSum) + ' TND';
  document.getElementById('pendingCount').textContent    = pendingCnt + ' payout(s)';
  document.getElementById('totalPaidMonth').textContent  = fmt(paidSum) + ' TND';
  document.getElementById('paidCount').textContent       = paidCnt + ' this month';
  document.getElementById('totalOnHold').textContent     = fmt(holdSum) + ' TND';
  document.getElementById('holdCount').textContent       = holdCnt + ' payout(s)';
}

function applyFilter() {
  const filtered = currentFilter === 'all' ? allPayouts : allPayouts.filter(p => p.status === currentFilter);
  renderTable(filtered);
}

// Load payouts
db.collection('weeklyPayouts').orderBy('createdAt', 'desc').limit(200)
  .onSnapshot(snap => {
    allPayouts = snap.docs.map(d => ({ _id: d.id, ...d.data() }));
    updateSummary(allPayouts);
    applyFilter();
  });

// Filter tabs
document.querySelectorAll('#filterTabs .nav-link').forEach(tab => {
  tab.addEventListener('click', e => {
    e.preventDefault();
    document.querySelectorAll('#filterTabs .nav-link').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    currentFilter = tab.dataset.filter;
    applyFilter();
  });
});

// Mark Paid modal
function openMarkPaid(id, name, amount) {
  document.getElementById('modalRestaurantName').textContent = name;
  document.getElementById('modalAmount').textContent = parseFloat(amount).toFixed(3);
  document.getElementById('bankRefInput').value = '';
  document.getElementById('markPaidForm').action = '/weekly-payouts/' + id + '/mark-paid';
  $('#markPaidModal').modal('show');
}

// Hold modal
function openHold(id, name) {
  document.getElementById('holdRestaurantName').textContent = name;
  document.getElementById('holdForm').action = '/weekly-payouts/' + id + '/hold';
  $('#holdModal').modal('show');
}
</script>
@endpush
