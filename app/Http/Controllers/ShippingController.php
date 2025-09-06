<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    public function checkOngkir(Request $request)
    {
        $user = Auth::user();

        if (!$user->district_id) {
            return response()->json(['error' => 'Alamat kecamatan belum diisi'], 400);
        }

        $districtId = $user->district_id;
        $weight = $request->input('weight', 1000);

        $couriers = ['jne', 'jnt', 'pos', 'sap', 'ide', 'rex', 'lion'];
        $allServices = [];

        foreach ($couriers as $courier) {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'key'    => config('rajaongkir.api_key'),
            ])->withOptions([
                'query' => [
                    'origin'      => 1332,
                    'destination' => $districtId,
                    'weight'      => $weight,
                    'courier'     => $courier,
                ]
            ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost');

            if ($response->successful() && isset($response->json()['data'])) {
                foreach ($response->json()['data'] as $service) {
                    $allServices[] = [
                        'courier' => strtoupper($courier),
                        'service' => $service['service'],
                        'etd'     => $service['etd'],
                        'cost'    => $service['cost'],
                    ];
                }
            }
        }

        return response()->json($allServices);
    }
}
