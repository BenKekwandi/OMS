<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * 
 *
 * @property int $id
 * @property int $order_days
 * @property int $availability
 * @property int $brand_id
 * @property int $supplier_id
 * @property string $reference_number
 * @property string|null $image
 * @property string|null $other_features
 * @property float $discount
 * @property float $net_price
 * @property float $rrp_price
 * @property string|null $rrp_explanation
 * @property int|null $warehouse_id
 * @property string|null $serial_number
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brands $brand
 * @property-read \App\Models\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereNetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereOrderDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereOtherFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereRrpExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereRrpPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereWarehouseId($value)
 * @method static \Database\Factories\OffersFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Offers extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'brand_id',
        'supplier_id',
        'reference_number',
        'discount',
        'net_price',
        'rrp_price',
        'rrp_explanation',
        'image',
        'other_features',
        'warehouse_id',
        'order_days',
        'serial_number',
        'availability',
        'status',
        'created_at',
    ];

    public function brand()
    {
        return $this->belongsTo(Brands::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            return Storage::disk('public')->url($value);
        }

        return Storage::disk('public')->url('models/1711243438_65ff80ae18c91.png');
    }

    public function getCreatedAtAttribute($value)
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
                return 'Proposed';
            case 3:
                return 'Confirmed';
            case 4:
                return 'Expired';
            case 5:
                return 'Cancelled';
            default:
                return $value;
        }
    }

    public function getAvailabilityAttribute($value)
    {
        switch ($value) {
            case 1:
                return 'In shop';
            case 2:
                return 'To order';
            case 3:
                return 'In stock';
            default:
                return $value;
        }
    }


}
