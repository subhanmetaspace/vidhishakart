<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class FundController extends Controller
{
    public function updateTransactionStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $validated = $validator->validated();
        $decryptedData = json_decode($this->decryptData($validated['data']), true);

        if (!isset($decryptedData['merchantid']) || !isset($decryptedData['status'])) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid data structure.'
            ], 400);
        }

        $order = Order::where('order_number', $decryptedData['merchantid'])->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found.'
            ], 404);
        }

        if (strtoupper($decryptedData['status']) === 'SUCCESS') {
            if ($order->payment_status === 'success') {
                return response()->json([
                    'status' => true,
                    'message' => 'Already updated.'
                ]);
            }

            $updateData = ['payment_status' => 'success'];
            if (isset($decryptedData['utr'])) {
                $updateData['utr'] = $decryptedData['utr'];
            }

            $order->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Payment marked as success.'
            ]);
        } else {
            $order->update(['payment_status' => 'cancel']);

            return response()->json([
                'status' => true,
                'message' => 'Payment marked as cancelled.'
            ]);
        }
    }

    function decryptData($crypt, $country = "india")
    {
        $iv  = env("SECRET_IV");
        $key = env("SECRET_KEY");

        $crypt = base64_decode($crypt);
        $padtext = openssl_decrypt($crypt, "AES-256-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

        if ($padtext === false) {
            return false;
        }

        $pad = ord($padtext[strlen($padtext) - 1]);

        if ($pad > strlen($padtext)) {
            return false;
        }

        if (strspn($padtext, $padtext[strlen($padtext) - 1], strlen($padtext) - $pad) != $pad) {
            return false;
        }

        return substr($padtext, 0, -1 * $pad);
    }


    function encryptData($text, $country = "india")
    {
        $iv = env("SECRET_IV");
        $key = env("SECRET_KEY");
        $size = 16;
        $pad = $size - (strlen($text) % $size);
        $padtext = $text . str_repeat(chr($pad), $pad);
        $crypt = openssl_encrypt($padtext, "AES-256-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        return base64_encode($crypt);
    }
}
