<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Absensi extends Model
{
    protected $table = "absen";
    protected $primaryKey = 'id';

    public function karyawan()
    {
        return $this->hasOne(User::class, 'id', 'id_karyawan')->withDefault();
    }
}
