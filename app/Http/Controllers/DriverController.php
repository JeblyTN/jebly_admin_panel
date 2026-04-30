<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FirestoreHelper;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("drivers.index");
    }

    public function edit($id)
    {
        return view('drivers.edit')->with('id', $id);
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function view($id)
    {
        return view('drivers.view')->with('id', $id);
    }

    public function DocumentList($id)
    {
        return view("drivers.document_list")->with('id', $id);
    }

    public function DocumentUpload($driverId, $id)
    {
        return view("drivers.document_upload", compact('driverId', 'id'));
    }

    public function driverChat($id)
    {
        return view('drivers.chat', compact('id'));
    }

    // ── Cash Balance Management ──────────────────────────────────────────────

    public function cashBalance()
    {
        return view('drivers.cash_balance');
    }

    public function resetCashBalance($id)
    {
        FirestoreHelper::updateDocument('users/' . $id, [
            'cashBalance'  => 0.0,
            'platformDebt' => 0.0,
            'isActive'     => true,
            'blockedReason' => '',
        ]);
        return redirect()->route('drivers.cashBalance')
            ->with('success', 'Cash balance reset and driver unblocked.');
    }

    public function setCashLimit(Request $request, $id)
    {
        $limit = floatval($request->input('limit', 300));
        FirestoreHelper::updateDocument('users/' . $id, [
            'cashBalanceLimit'    => $limit,
            'cashLimitOverridden' => true,
        ]);
        return redirect()->route('drivers.cashBalance')
            ->with('success', 'Cash limit updated.');
    }

    public function unblockDriver($id)
    {
        FirestoreHelper::updateDocument('users/' . $id, [
            'isActive'      => true,
            'blockedReason' => '',
        ]);
        return redirect()->route('drivers.cashBalance')
            ->with('success', 'Driver unblocked successfully.');
    }

    // ── Cash Deposits ────────────────────────────────────────────────────────

    public function deposits()
    {
        return view('drivers.deposits');
    }

    public function confirmDeposit(Request $request, $id)
    {
        $deposit  = FirestoreHelper::getDocument('cashDeposits/' . $id);
        $driverId = $deposit['driverId'] ?? '';
        $amount   = floatval($deposit['amount'] ?? 0);

        FirestoreHelper::updateDocument('cashDeposits/' . $id, [
            'status'      => 'confirmed',
            'confirmedAt' => now()->toIso8601String(),
            'bankRef'     => $request->input('bankRef', ''),
            'notes'       => $request->input('notes', ''),
        ]);

        if ($driverId) {
            $driver          = FirestoreHelper::getDocument('users/' . $driverId);
            $currentCash     = floatval($driver['cashBalance']            ?? 0);
            $currentDebt     = floatval($driver['platformDebt']           ?? 0);
            $totalDeposited  = floatval($driver['totalLifetimeDeposited'] ?? 0);

            FirestoreHelper::updateDocument('users/' . $driverId, [
                'cashBalance'            => max(0.0, $currentCash - $amount),
                'platformDebt'           => max(0.0, $currentDebt - $amount),
                'isActive'               => true,
                'blockedReason'          => '',
                'totalLifetimeDeposited' => $totalDeposited + $amount,
                'lastDepositAt'          => now()->toIso8601String(),
                'lastDepositAmount'      => $amount,
            ]);
        }

        return redirect()->route('drivers.deposits')
            ->with('success', 'Deposit confirmed. Driver balance updated.');
    }

    public function rejectDeposit(Request $request, $id)
    {
        FirestoreHelper::updateDocument('cashDeposits/' . $id, [
            'status' => 'rejected',
            'notes'  => $request->input('notes', ''),
        ]);
        return redirect()->route('drivers.deposits')
            ->with('success', 'Deposit rejected.');
    }
}
