<?php

namespace App\Http\Controllers;

use App\Models\Klasifikasi;
use Illuminate\Http\Request;

class KategoriDropdownController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        $klasifikasis = Klasifikasi::findOrFail($request->id);
        $subFiltered = $klasifikasis->subs->pluck('sub_kategori', 'id');
        return response()->json($subFiltered);
    }
}
