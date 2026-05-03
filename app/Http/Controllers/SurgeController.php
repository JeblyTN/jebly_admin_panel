<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FirestoreHelper;

class SurgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('surge.index');
    }

    public function toggle(Request $request)
    {
        $active = $request->boolean('active', false);
        $now = date('c');

        $data = [
            'active' => $active,
            'multiplier' => $active ? (float)($request->input('multiplier', 1.5)) : 1.0,
            'labelFr' => $active ? ($request->input('labelFr', 'Forte demande')) : '',
            'label' => $active ? ($request->input('labelFr', 'Forte demande')) : '',
            'zone' => $request->input('zone', 'all'),
            'activatedAt' => $active ? $now : null,
            'activatedByAdminId' => auth()->id() ?? 'admin',
        ];

        $scheduledMinutes = (int)$request->input('scheduledMinutes', 0);
        if ($active && $scheduledMinutes > 0) {
            $data['scheduledEnd'] = date('c', strtotime("+{$scheduledMinutes} minutes"));
        } else {
            $data['scheduledEnd'] = null;
        }

        FirestoreHelper::setDocument('surgeStatus/current', $data);

        // Log to surgeHistory
        $historyId = uniqid('surge_');
        $historyData = array_merge($data, ['timestamp' => $now, 'action' => $active ? 'activated' : 'deactivated']);
        FirestoreHelper::setDocument("surgeHistory/{$historyId}", $historyData);

        return response()->json(['success' => true, 'active' => $active]);
    }

    public function createRule(Request $request)
    {
        $ruleId = uniqid('rule_');
        $data = [
            'name' => $request->input('name', 'Surge Rule'),
            'multiplier' => (float)$request->input('multiplier', 1.5),
            'active' => true,
            'triggerType' => $request->input('triggerType', 'schedule'),
            'zone' => $request->input('zone', 'all'),
            'days' => $request->input('days', []),
            'startTime' => $request->input('startTime', ''),
            'endTime' => $request->input('endTime', ''),
            'minActiveDrivers' => (int)$request->input('minActiveDrivers', 0),
            'maxActiveDrivers' => (int)$request->input('maxActiveDrivers', 0),
            'deleted' => false,
            'createdAt' => date('c'),
        ];

        FirestoreHelper::setDocument("surgeRules/{$ruleId}", $data);

        return response()->json(['success' => true, 'id' => $ruleId]);
    }

    public function deleteRule($id)
    {
        FirestoreHelper::setDocument("surgeRules/{$id}", ['deleted' => true, 'active' => false]);
        return response()->json(['success' => true]);
    }

    public function toggleRule(Request $request, $id)
    {
        $active = $request->boolean('active', false);
        FirestoreHelper::setDocument("surgeRules/{$id}", ['active' => $active]);
        return response()->json(['success' => true]);
    }
}
