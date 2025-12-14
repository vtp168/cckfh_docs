<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'deviceToken' => 'required|string',
        ]);

        // If using auth: get user id; otherwise null
        //$userId = optional($request->user())->id ?? null;
        $userId = Auth::id();

        $token = $request->input('deviceToken');

        $device = DeviceToken::updateOrCreate(
            ['token' => $token],
            ['user_id' => $userId, 'platform' => 'android', 'last_seen_at' => now()]
        );

        return response()->json(['status' => 'ok', 'device' => $device], 200);
    }

    // Optional: delete token when user logs out / uninstall
    public function destroy(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        DeviceToken::where('token', $request->token)->delete();
        return response()->json(['status' => 'deleted']);
    }
}
