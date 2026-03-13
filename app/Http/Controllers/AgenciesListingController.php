<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AgenciesListingController extends Controller
{
    /**
     * Display the public agencies listing page (data loaded via AJAX).
     */
    public function index(): View
    {
        return view('agencies.index');
    }
}
