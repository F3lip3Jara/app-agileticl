<?php

namespace App\Models\Sd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class SdMovStocks extends Model
{
    use HasFactory;
    protected $table    ='sd_stocks_mov';
    protected $fillable = [
        'stockMovId',
        'empId',
        'stockMovTip',
        'stockMovQty',
        'prdId',
        'stockMovHdrCustShortText1',
        'stockMovHdrCustShortText2',
        'stockMovHdrCustShortText3',
        'stockMovHdrCustShortText4',
        'stockMovHdrCustShortText5',
        'stockMovHdrCustShortText6',
        'created_at',
        'updated_at',
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

    public function scopeFilter($query, $filter) { 
        foreach($filter as $item){                             
            $column = $item->column;
            if( count( $item->values ) > 0 && $column != ""){  
                foreach($item->values as $values){
                    $query->orWhere($column, 'like', '%' . $values. '%');
                }                  
            }else{
                
                if($column != "" && count( $item->values ) > 0 ){
                    $query->where($item->column, 'LIKE', '%' . $item->values[0]. '%');
                }
               
            }      
         
        }
        
    }
}