<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function setLanguage(Request $request, string $locale): RedirectResponse
    {
        $available_locales = config('app.available_locales');

        if (in_array($locale, $available_locales, true)) {
            $request->session()->put('locale', $locale);
        }

        return redirect()->back();
    }
}
