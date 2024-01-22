<?php

namespace App\Models\Parametros;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Producto extends Model
{
    use HasFactory;

    protected $table    ='parm_producto';
    protected $fillable = [
        'idPrd',
        'prdCod',
        'prdDes',
        'prdObs',
        'prdRap',
        'prdEan',
        'prdTip',
        'prdCost',
        'prdNet',
        'prdBrut',
        'prdInv',
        'prdPes',
        'prdMin',
        'monId',
        'grpId',
        'grpsId',
        'unId',
        'colId',
        'empId'
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
