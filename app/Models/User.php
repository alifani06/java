<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;
    protected $fillable = [
        'karyawan_id',
        'kode_user',
        'qrcode_user',
        'password',
        'cek_hapus',
        'menu',
        'level',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getId()
    {
        return $getId = DB::table('users')->orderBy('id', 'DESC')->take(1)->get();
    }


    protected function menu(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


    public function isAdmin()
    {
        if ($this->level == 'admin') {
            return true;
        }
        return false;
    }

    public function isTokoslawi()
    {
        if ($this->level == 'toko_slawi') {
            return true;
        }
        return false;
    }

    public function isTokobanjaran()
    {
        if ($this->level == 'toko_banjaran') {
            return true;
        }
        return false;
    }
    public function isTokobumiayu()
    {
        if ($this->level == 'toko_bumiayu') {
            return true;
        }
        return false;
    }
    public function isTokotegal()
    {
        if ($this->level == 'toko_tegal') {
            return true;
        }
        return false;
    }

    public function isTokopemalang()
    {
        if ($this->level == 'toko_pemalang') {
            return true;
        }
        return false;
    }
    public function isTokocilacap()
    {
        if ($this->level == 'toko_cilacap') {
            return true;
        }
        return false;
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}