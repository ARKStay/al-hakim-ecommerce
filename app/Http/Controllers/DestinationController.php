<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DestinationController extends Controller
{
    /**
     * Menampilkan daftar provinsi dari API Raja Ongkir
     *
     * @return \Illuminate\View\View
     */
    // Tampil halaman pilih kota/kabupaten berdasarkan provinsi yang dipilih
    public function getCities($provinceId)
    {
        // Mengambil data kota berdasarkan ID provinsi dari API Raja Ongkir
        $response = Http::withHeaders([

            //headers yang diperlukan untuk API Raja Ongkir
            'Accept' => 'application/json',
            'key' => config('rajaongkir.api_key'),

        ])->get("https://rajaongkir.komerce.id/api/v1/destination/city/{$provinceId}");

        if ($response->successful()) {

            // Mengambil data kota dari respons JSON
            // Jika 'data' tidak ada, inisialisasi dengan array kosong
            return response()->json($response->json()['data'] ?? []);
        }
    }

    // Tampil halaman pilih kecamatan berdasarkan kabupaten yang dipilih
    public function getDistricts($cityId)
    {
        // Mengambil data kecamatan berdasarkan ID kota dari API Raja Ongkir
        $response = Http::withHeaders([

            //headers yang diperlukan untuk API Raja Ongkir
            'Accept' => 'application/json',
            'key' => config('rajaongkir.api_key'),

        ])->get("https://rajaongkir.komerce.id/api/v1/destination/district/{$cityId}");

        if ($response->successful()) {

            // Mengambil data kecamatan dari respons JSON
            // Jika 'data' tidak ada, inisialisasi dengan array kosong
            return response()->json($response->json()['data'] ?? []);
        }
    }
    // Tampil Ongkos Kirim berdasarkan pilihan kota/kabupaten dan kecamatan
    public function checkOngkir(Request $request)
    {
        $response = Http::withHeaders([

            //headers yang diperlukan untuk API Raja Ongkir
            'Accept' => 'application/json',
            'key'    => config('rajaongkir.api_key'),

        ])->withOptions([

            //query parameters untuk API Raja Ongkir
            'query' => [
                'origin'      => 2601, // ID kecamatan Pajangan (ganti sesuai kebutuhan)
                'destination' => $request->input('district_id'), // ID kecamatan tujuan
                'weight'      => $request->input('weight'), // Berat dalam gram
                'courier'     => $request->input('courier'), // Kode kurir (jne, tiki, pos)
            ]
        ])->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost');

        if ($response->successful()) {

            // Mengambil data ongkos kirim dari respons JSON
            // Jika 'data' tidak ada, inisialisasi dengan array kosong
            return $response->json()['data'] ?? [];
        }
    }
}