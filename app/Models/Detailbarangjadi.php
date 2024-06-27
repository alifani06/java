<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Detailbarangjadi extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'input_id',
        'barang_id',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'harga',
        'total',
  
  
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function getId()
    {
        return $getId = DB::table('inputs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function subsub()
    {
        return $this->belongsTo(Subsub::class);
    }

    public function input()
    {
        return $this->belongsTo(Input::class);
    }


}
