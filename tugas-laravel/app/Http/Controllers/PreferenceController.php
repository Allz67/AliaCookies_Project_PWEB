<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class PreferenceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $currentThemeCookie = $request->cookie('theme');
        $currentFontCookie  = $request->cookie('font_size');

        $themeSelected = $request->input('theme', 'system');
        $fontSelected  = $request->input('font_size', 'md');

        $cookieTheme = Cookie::make('theme', $themeSelected, 43200, null, null, false, false);
        $cookieFont  = Cookie::make('font_size', $fontSelected, 43200, null, null, false, false);

        return response()->json([
            'status'   => 'success',
            'message'  => 'Konfigurasi preferensi tampilan Alia Cookies berhasil diperbarui!',
            'theme'    => $themeSelected,
            'font_size' => $fontSelected
        ])->withCookie($cookieTheme)->withCookie($cookieFont);
    }
}
