<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tokopemalang extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
    'produk_id', 
    'harga_awal',
    'diskon_awal',
    'member_harga_pml', 
    'non_harga_pml', 
    'member_diskon_pml', 
    'non_diskon_pml', 
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
        return $getId = DB::table('tokopemalangs')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function hargajual()
    {
        return $this->belongsTo(Hargajual::class, 'hargajual_id', 'id');
    }
        
}