<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'qrcode_barang',
        'jumlah',
        'spesifikasi',
        'keterangan',
        'harga',
        'diskon',
        'total',
        'tanggal_awal',
        'tanggal_akhir',
        'klasifikasi_id',
        'subklasifikasi_id',
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
        return $getId = DB::table('barangs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function klasifikasi()
    {
        return $this->belongsTo(Klasifikasi::class);
    }

    public function subklasifikasi()
    {
        return $this->belongsTo(SubKlasifikasi::class);
    }

    public function input()
    {
        return $this->hasMany(Input::class);
    }

    public function subsub()
    {
        return $this->belongsTo(Subsub::class);
    }
}