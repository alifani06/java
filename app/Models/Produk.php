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
        'klasifikasi_id',
        'subklasifikasi_id',
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

    public function tokotegal()
    {
        return $this->hasMany(Tokotegal::class);
    }
    public function tokopemalang()
    {
        return $this->hasMany(Tokopemalang::class);
    }
    public function tokobumiayu()
    {
        return $this->hasMany(Tokobumiayu::class);
    }
    public function tokocilacap()
    {
        return $this->hasMany(Tokocilacap::class);
    }
    public function detailtoko()
    {
        return $this->hasMany(Detailtoko::class);
    }
    public function detailtokoslawi()
    {
        return $this->hasMany(Detailtokoslawi::class,  'produk_id', 'id');
    }
    public function detailbarangjadi()
    {
        return $this->hasMany(Detailbarangjadi::class,  'produk_id', 'id');
    }
    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class, 'klasifikasi_id');
    }
    public function permintaanproduk()
    {
        return $this->hasMany(Permintaanproduk::class);
    }
    public function subklasifikasi()
    {
        return $this->belongsTo(Subklasifikasi::class, 'subklasifikasi_id');
    }
    public function stok_barangjadi()
    {
        return $this->hasMany(Stok_barangjadi::class);
    }
}