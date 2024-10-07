<?php

namespace App\Models\OMS;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class OrdenWeb extends Model
{
    use HasFactory;

    protected $table = 'vent_ordenes';
    
    protected $fillable = [
       'opedId',
        'empId',
        'cliId',// Agregado cliId
        'opedparentid',
        'opedstatus',
        'opedmoneda',
        'opedversion',
        'opedfechaCreacion', // Cambio de date a timestamp
        'opedpreciosIncluyenImpuestos',
       'opeddescuentoTotal',
       'opeddescuentoImpuesto',
       'opedenvioTotal',
       'opedenvioImpuesto',
       'opedimpuestoCarrito',
       'opedtotal',
       'opedtotalImpuesto',
      'opedclaveOrden',
      'opedMetodoPago',
      'opedtituloMetodoPago',
      'opeddireccionIpCliente',
      'opedEntrega',
      'opePlace',
      'userAgentCliente',
      'opedcarritoHash',
      'opedidExt'
       
        
    ];

  /*  public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    public function lineas()
    {
        return $this->hasMany(LineaOrden::class);
    }
*/
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
