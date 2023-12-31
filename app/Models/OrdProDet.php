<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class OrdProDet extends Model
{
    use HasFactory;

    protected $table    ='ord_produccion_det';
    protected $fillable = [
        'idOrp',
        'empId',
        'idOrpd',
        'orpdPrdCod',
        'orpdPrdDes',
        'orpdCant',
        'orpdCantDis',
        'orpdTotP',
        'orpdObs',
        'orpdidEta'
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
