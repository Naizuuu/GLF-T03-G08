<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AutomatasController extends Controller
{
    function index()
    {
        return view('automatas.home');
    }

    function automata_afd()
    {
        return view('automatas.proceso.automata_afd');
    }

    function automata_ap()
    {
        return view('automatas.proceso.automata_ap');
    }
}
