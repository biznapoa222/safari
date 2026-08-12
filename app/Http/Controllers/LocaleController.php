<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request, string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, config('safari.languages')), 404);

        $request->session()->put('locale', $locale);

        return back();
    }
}
