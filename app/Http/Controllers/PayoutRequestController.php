<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayoutRequestController extends Controller
{

    public function __construct()
    {
        ->middleware('auth');
    }

    public function index( = '')
    {
        return view('payoutRequests.drivers.index')->with('id', );
    }

    public function restaurant( = '')
    {
        return view('payoutRequests.restaurants.index')->with('id', );
    }

    public function weeklyPayouts()
    {
        return view('payouts.weekly_payouts');
    }

    public function markPaid(Request , )
    {
         = new \App\Helpers\FirestoreHelper();
         = ->getDocument('weeklyPayouts/' . );

        ->updateDocument('weeklyPayouts/' . , [
            'status'  => 'paid',
            'paidAt'  => now()->toIso8601String(),
            'bankRef' => ->input('bankRef', ''),
            'notes'   => ->input('notes', ''),
        ]);

         = ['restaurantId'] ?? '';
        if () {
            ->updateDocument('vendors/' . , [
                'lastPayoutAt'     => now()->toIso8601String(),
                'lastPayoutAmount' => (float) (['amount'] ?? 0),
            ]);
        }

        return redirect()->route('weeklyPayouts')->with('success', 'Payout marked as paid.');
    }

    public function holdPayout(Request , )
    {
         = new \App\Helpers\FirestoreHelper();
        ->updateDocument('weeklyPayouts/' . , [
            'status' => 'on_hold',
            'notes'  => ->input('notes', ''),
        ]);
        return redirect()->route('weeklyPayouts')->with('success', 'Payout put on hold.');
    }

    public function generatePayouts()
    {
           = config('firebase.node_path', '/opt/alt/alt-nodejs20/root/usr/bin/node');
         = base_path('scripts/generate_payouts.js');
           = base_path('storage/app/firebase/credentials.json');

         = shell_exec("   2>&1");

        return redirect()->route('weeklyPayouts')->with('success', 'Payouts generated. ' . trim());
    }
}
