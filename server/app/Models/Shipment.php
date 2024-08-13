<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

/**
 * 
 *
 * @property-read \App\Models\OfficeAddress|null $shipFrom
 * @property-read \App\Models\OfficeAddress|null $shipTo
 * @property-read \App\Models\ShipmentAccount|null $shipment_account
 * @method static \Database\Factories\ShipmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment query()
 * @property int $id
 * @property int $shipment_account_id
 * @property string $shipping_type
 * @property bool $automatic_shipping
 * @property int $status
 * @property string $ship_to_title
 * @property string $ship_from_title
 * @property int|null $ship_to_id
 * @property int|null $ship_from_id
 * @property float|null $box_weight
 * @property float|null $box_width
 * @property float|null $box_height
 * @property float|null $box_depth
 * @property \Illuminate\Support\Carbon $deadline
 * @property \Illuminate\Support\Carbon $pick_up_time
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereAutomaticShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereBoxDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereBoxHeigth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereBoxWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereBoxWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment wherePickUpTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipFromTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipToTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShipmentAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereShippingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Orders> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|Shipment whereBoxHeight($value)
 * @mixin \Eloquent
 */
class Shipment extends Model
{
    use HasFactory;

    protected $table = 'shipments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'shipment_account_id',
        'shipping_type',
        'automatic_shipping',
        'status',
        'ship_to_title',
        'ship_from_title',
        'ship_to_id',
        'ship_from_id',
        'box_weight',
        'box_width',
        'box_height',
        'box_depth',
        'pick_up_time',
        'deadline',
        'collected_at',
        'delivered_at'
    ];

    protected $casts = [
        'shipment_account_id' => 'integer',
        'ship_to_id' => 'integer',
        'automatic_shipping' => 'boolean',
        'ship_from_id' => 'integer',
        'status' => 'integer',
        'pick_up_time' => 'datetime',
        'deadline' => 'datetime',
        'collected_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function shipment_account()
    {
        return $this->belongsTo(ShipmentAccount::class, "shipment_account_id");
    }
    public function shipTo()
    {
        return $this->belongsTo(OfficeAddress::class);
    }

    public function shipFrom()
    {
        return $this->belongsTo(OfficeAddress::class);
    }

    public function label()
    {
        return $this->hasOne(Label::class, 'shipment_id', 'id');
    }


    public function orders()
    {
        return $this->belongsToMany(Orders::class, 'order_shipments', 'shipment_id', 'order_id');
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }

     public function getDeliveredAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }
    public function getCollectedAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }

    public function getDeadlineAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }
    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 1:
                return 'New';
            case 2:
                return 'Label Created';
            case 3:
                return 'Collected';
            case 4:
                return 'Delivered';
            case 5:
                return 'Delivered To Customer';
            default:
                return $value;
        }
    }

}
