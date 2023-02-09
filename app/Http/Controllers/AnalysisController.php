<?php

namespace App\Http\Controllers;

use App\Models\Analysis;

class AnalysisController extends Controller
{
    public function index()
    {
        $day   = Analysis::day();
        $month = Analysis::month();
        $total = Analysis::total();

        return view('analysis', compact('day','month','total'));
    }

    public function show($month)
    {
        $list = Analysis::byMonth($month);

        dump($list);
    }
}
