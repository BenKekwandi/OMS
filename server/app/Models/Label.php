<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

/**
 * 
 *
 * @property int $id
 * @property int $shipment_id
 * @property int $status
 * @property int $kind
 * @property string $file
 * @property float|null $amount
 * @property string|null $tracking_number
 * @property string|null $postmen_id
 * @property \Illuminate\Support\Carbon|null $expected_collection_at
 * @property \Illuminate\Support\Carbon|null $expected_delivery_at
 * @property string|null $response
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Shipment $shipment
 * @method static \Database\Factories\LabelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Label newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Label newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Label query()
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereExpectedCollectionAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereExpectedDeliveryAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereKind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label wherePostmenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Label extends Model
{
    use HasFactory;

    protected $table = 'labels';

    protected $primaryKey = 'id';

    protected $fillable = [
        'shipment_id',
        'kind',
        'status',
        'file',
        'amount',
        'tracking_number',
        'postmen_id',
        'expected_collection_at',
        'expected_delivery_at',
        'response',
    ];

    protected $casts = [
        'shipment_id' => 'integer',
        'kind' => 'integer',
        'amount' => 'float',
        'status' => 'integer',
        'expected_collection_at' => 'datetime',
        'expected_delivery_at' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function label_invoice()
    {
        return $this->hasMany(LabelInvoice::class, 'label_id');
    }

     public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }
     public function getUpdatedAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }
    public function getExpectedCollectionAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }

    public function getExpectedDeliveryAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }

    public function getKindAttribute($value)
    {
        switch ($value) {
            case 1:
                return 'manual';
            case 2:
                return 'automatic';
            default:
                return $value;
        }
    }
}
