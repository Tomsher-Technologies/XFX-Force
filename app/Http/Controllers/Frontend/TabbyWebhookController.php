<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TabbyWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('TABBY WEBHOOK', $request->all());

        return response()->json([
            'success' => true
        ]);
    }
}