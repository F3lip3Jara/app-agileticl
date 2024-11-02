<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class OrdenProduccion extends Model
{
    use HasFactory;

    protected $table    ='prod_orden';
    protected $fillable = [
        'orpId',
        'empId',
        'prvId',    
        'orpNumOc',
        'orpNumRea',
        'orpFech',
        'orpUsrG',
        'orpObs',
        'orpTurns',
        'orpEst',
        'orpEstPrc',
        'orpHdrCustShortText1', 255, // Etapa
        'orpHdrCustShortText2', 100, // Clase documento
        'orpHdrCustShortText3', 100, // 
        'orpHdrCustShortText4', 100, // 
        'orpHdrCustShortText5', 100, // 
        'orpHdrCustShortText6', 100, // 
        'orpHdrCustShortText7', 100, // 
        'orpHdrCustShortText8', 100, // 
        'orpHdrCustShortText9', 100, // 
        'orpHdrCustShortText10', 20, // 
        'orpHdrCustShortText11', 20, // 
        'orpHdrCustShortText12', 20, // 
        'orpHdrCustShortText13', 20, // 
        'orpHdrCustLongText1' // 
     
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
