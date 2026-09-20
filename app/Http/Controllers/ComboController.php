<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Contracts\View\View;

class ComboController extends Controller
{
    /**
     * Display the curated combo bundles.
     */
    public function index(): View
    {
        $combos = Combo::where('is_active', true)->get();

        return view('combos.index', compact('combos'));
    }
}
