<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LokasiController extends Controller
{
    public function getProvinsi()
    {
        try {
            // API V2 menggunakan rute /destination/province
            $response = Http::withoutVerifying()
                ->withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
                ->get(env('RAJAONGKIR_BASE_URL') . '/destination/province');

            // API V2 membungkus datanya di dalam array ['data']
            return response()->json($response->json()['data'] ?? []);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getKota($provinsi_id)
    {
        try {
            // API V2 mengubah rute kota menjadi /destination/city/{id}
            $response = Http::withoutVerifying()
                ->withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
                ->get(env('RAJAONGKIR_BASE_URL') . '/destination/city/' . $provinsi_id);

            return response()->json($response->json()['data'] ?? []);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
