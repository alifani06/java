<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hargajual extends Model
{
    use HasFactory;
    // use LogsActivity;

    protected $fillable = [
    'produk_id', 
    'member_harga', 
    'non_member_harga', 
    'hargajual',
    'diskon_member', 
    'diskon_non_member', 

  
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
        return $getId = DB::table('hargajuals')->orderBy('id', 'DESC')->take(1)->get();
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

        
}