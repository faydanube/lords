<?php

namespace App\Http\Controllers;

use App\Models\Lord;
use Illuminate\Http\Request;

class LordController extends Controller
{
    public function store(Request $request)
    {
        $expired_at = now()->addDay()->startOfDay();

        if (empty($request->lord) || strlen($request->lord) !== 32) {
            return response()->json([
                'msg'  => 'Invalid fingerprint, my lord.',
                'time' => $expired_at->timestamp,
            ]);
        }

        $lord = Lord::select('id', 'expired_at')
            ->where('fingerprint', $request->lord)
            ->where('expired_at', '>', now())
            ->first();

        if (!$lord) {
            $lord = Lord::create([
                'fingerprint' => $request->lord,
                'is_sync'     => false,
                'expired_at'  => $expired_at,
            ]);

            return response()->json([
                'msg'  => 'Store success, my lord.',
                'time' => $expired_at->timestamp,
            ]);
        }

        return response()->json([
            'msg'  => 'All work is done today, my lord.',
            'time' => $expired_at->timestamp,
        ]);
    }
}
