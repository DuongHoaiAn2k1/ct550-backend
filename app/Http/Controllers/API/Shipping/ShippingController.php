<?php

namespace App\Http\Controllers\API\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function getFee(Request $request)
    {
        $url = 'https://services.giaohangtietkiem.vn/services/shipment/fee';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token' => '11ebf132b7946a07fc2978ec870c3e4dfeb400f5',
        ])->get($url, $request->all());

        return response()->json($response->json());
    }
}
