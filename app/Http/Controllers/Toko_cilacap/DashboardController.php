<?php

namespace App\Http\Controllers\Toko_tegal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function index()
    {
        return view('toko_tegal.index');
    }
}