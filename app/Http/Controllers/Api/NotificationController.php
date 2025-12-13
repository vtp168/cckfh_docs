<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;
use App\Models\DeviceToken;

class NotificationController extends Controller
{
    protected FirebaseNotificationService $firebase;

    public function __construct(FirebaseNotificationService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function sendToUserTokens(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $tokens = DeviceToken::where('user_id', $request->user_id)->pluck('token')->toArray();
        if (empty($tokens)) return response()->json(['error' => 'no tokens'], 422);

        $reports = $this->firebase->sendToTokens($tokens, $request->title, $request->body, $request->input('data', []));
        return response()->json(['reports' => $reports]);
    }

    public function sendToAll(Request $request)
    {
        $request->validate(['title'=>'required','body'=>'required']);
        $tokens = DeviceToken::pluck('token')->toArray();

        $reports = $this->firebase->sendToTokens($tokens, $request->title, $request->body, $request->input('data', []));
        return response()->json(['reports' => $reports]);
    }

    public function sendToTopic(Request $request)
    {
        $request->validate(['topic'=>'required','title'=>'required','body'=>'required']);
        $result = $this->firebase->sendToTopic($request->topic, $request->title, $request->body, $request->input('data', []));
        return response()->json($result);
    }
}
