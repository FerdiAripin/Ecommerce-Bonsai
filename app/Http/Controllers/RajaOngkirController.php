<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RajaOngkirController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        $response = app('rajaongkir')->provinces();

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch provinces'], 500);
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $response = app('rajaongkir')->cities($request->province_id);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch cities'], 500);
    }

    /**
     * Calculate shipping cost
     */
    public function getShippingCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin' => 'required|numeric',
            'destination' => 'required|numeric',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $response = app('rajaongkir')->cost(
            $request->origin,
            $request->destination,
            $request->weight,
            $request->courier
        );

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to calculate shipping cost'], 500);
    }
}
