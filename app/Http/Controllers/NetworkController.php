<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class NetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('networks.index');
    }
}
