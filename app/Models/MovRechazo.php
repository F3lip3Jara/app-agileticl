<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class MovRechazo extends Model
{
    use HasFactory;

    protected $table    ='mot_rechazo';
    protected $fillable = [
        'idMot',
        'empId',
        'motDes',
        'idEta'
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

    public function scopeBuscarpor($query, $tipo, $buscar) {     	
        return  $query->whereIn($tipo,$buscar);
    }

}
