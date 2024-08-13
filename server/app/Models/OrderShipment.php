<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read \App\Models\Orders|null $Order
 * @property-read \App\Models\Shipment|null $Shipment
 * @method static \Database\Factories\OrderShipmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment query()
 * @property int $id
 * @property int $order_id
 * @property int $shipment_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderShipment whereUpdatedAt($value)
 * @property-read \App\Models\Orders $order
 * @property-read \App\Models\Shipment $shipment
 * @mixin \Eloquent
 */
class OrderShipment extends Model
{
    use HasFactory;


    protected $table = 'order_shipments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'shipment_id',
        'status',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'shipment_id' => 'integer',
        'status' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}
