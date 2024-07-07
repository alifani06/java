<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'satuan',
        'harga',
        'diskon',
        'gambar',
        'qrcode_produk',
  
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable('*');
    // }
    
    public static function getId()
    {
        return $getId = DB::table('produks')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function hargajual()
    {
        return $this->hasMany(Hargajual::class);
    }

    public function tokoslawi()
    {
        return $this->hasMany(Tokoslawi::class);
    }

    public function tokobenjaran()
    {
        return $this->hasMany(Tokobenjaran::class);
    }
 
}