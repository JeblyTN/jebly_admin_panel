<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayoutRequestController extends Controller
{
    public function index($id = '')
    {
        return view('payoutRequests.drivers.index')->with('id', $id);
    }

    public function restaurant($id = '')
    {
        return view('payoutRequests.restaurants.index')->with('id', $id);
    }

    public function weeklyPayouts()
    {
        return view('payouts.weekly_payouts');
    }

    public function markPaid(Request $request, $id)
    {
        $helper = new \App\Helpers\FirestoreHelper();
        $payout = $helper->getDocument('weeklyPayouts/' . $id);
        $helper->updateDocument('weeklyPayouts/' . $id, [
            'status'  => 'paid',
            'paidAt'  => now()->toIso8601String(),
            'bankRef' => $request->input('bankRef', ''),
            'notes'   => $request->input('notes', ''),
        ]);

        $restaurantId = $payout['restaurantId'] ?? '';
        if ($restaurantId) {
            $helper->updateDocument('vendors/' . $restaurantId, [
                'lastPayoutAt'     => now()->toIso8601String(),
                'lastPayoutAmount' => (float) ($payout['amount'] ?? 0),
            ]);
        }

        return redirect()->route('weeklyPayouts')->with('success', 'Payout marked as paid.');
    }

    public function holdPayout(Request $request, $id)
    {
        $helper = new \App\Helpers\FirestoreHelper();
        $helper->updateDocument('weeklyPayouts/' . $id, [
            'status' => 'on_hold',
            'notes'  => $request->input('notes', ''),
        ]);
        return redirect()->route('weeklyPayouts')->with('success', 'Payout put on hold.');
    }

    public function generatePayouts()
    {
        $nodePath   = config('firebase.node_path', '/opt/alt/alt-nodejs20/root/usr/bin/node');
        $scriptPath = base_path('scripts/generate_payouts.js');
        $credPath   = base_path('storage/app/firebase/credentials.json');

        $output = shell_exec("{$nodePath} {$scriptPath} {$credPath} 2>&1");

        return redirect()->route('weeklyPayouts')->with('success', 'Payouts generated. ' . trim($output ?? ''));
    }
}
