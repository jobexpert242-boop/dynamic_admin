<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (!in_array($locale, ['en', 'bn'])) {
            abort(400);
        }
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return redirect()->back();
    }
}
