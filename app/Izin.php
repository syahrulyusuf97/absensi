<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Izin extends Model
{
    protected $table = "izin";
    protected $primaryKey = 'id';

    public function karyawan()
    {
        return $this->hasOne(User::class, 'id', 'id_karyawan')->withDefault();
    }
}
