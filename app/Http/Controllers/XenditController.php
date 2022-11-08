<?php

namespace App\Http\Controllers;

use Xendit\Xendit;
use Illuminate\Http\Request;
use App\Models\MemberTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class XenditController extends Controller
{
    public function __construct()
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));
    }

    public function xenditCallback()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = file_get_contents("php://input");
            Log::info('===CALLBACK XENDIT INVOICE===');
            Log::info($data);
            $data = json_decode($data);
            Log::info('===CALLBACK XENDIT EXTERNAL===');
            Log::info($data->external_id);
            Log::info($data->status);

            $xenditCallback = MemberTransaction::where([
                'transaction_id' => $data->external_id
            ])->first();
            $xenditCallback->status = $data->status;
            $xenditCallback->save();

            return response()->json("callback success", 200);
        } else {
            abort(400);
        }
    }
}
