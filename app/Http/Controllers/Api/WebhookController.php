<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WebhookController extends Controller
{

    public function get_data(Request $request)
    {
        $originalData = file_get_contents('php://input');
        $decodedData = json_decode($originalData, true);

        $file_name = "AttLog.txt";
        // Storage::put($file_name, json_encode($request->all()));
        // Storage::append($file_name, json_encode($decodedData));
        Log::info('Update file');

        Log::info('Webhook received', $decodedData);

        // Process the webhook data as needed

        // Return a response
        return response()->json(['success' => true]);
    }

}
