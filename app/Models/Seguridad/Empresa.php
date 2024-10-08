<?php

namespace App\Models\Seguridad;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Empresa extends Model
{
    use HasFactory;
    protected $table    ='parm_empresa';
    protected $fillable = [
        'empId',
        'empDes',
        'empDir',
        'empRut',
        'empGiro',
        'empFono',
        'empImg', 
        'empTokenOMS'
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::createFromTimestamp(strtotime($value))
        ->timezone(Config::get('app.timezone'))
        ->toDateTimeString();
    }
        
    public function getUpdatedAtAttribute($value){
        return Carbon::createFromTimestamp(strtotime($value))
        ->timezone(Config::get('app.timezone'))
        ->toDateTimeString();
    }
}
