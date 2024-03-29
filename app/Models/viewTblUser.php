<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class viewTblUser extends Model
{
    use HasFactory;
    protected $table    ='tblusuarios';

    protected $fillable = [
        'id',
        'empId',
        'empresa',
        'rolId',
        'name',
        'email',
        'rolDes',
        'emploNom',
        'emploApe',
        'emploFecNac',
        'gerDes',
        'activado',
        'reinicio',
        'created_at',
        'gerId'
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
