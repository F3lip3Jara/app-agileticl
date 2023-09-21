<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class MenuRol extends Model
{
    use HasFactory;
    protected $table    ='menu_roles';
    protected $fillable = [
        'idMol',
        'idRol',
        'molDes',
        'molIcon',
        'idOpt',
        'optDes',
        'optLink',
        'optSub',
        'optSDes',
        'optSLink'

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
