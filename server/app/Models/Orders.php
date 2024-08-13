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
 * @property int|null $offer_id
 * @property int $brand_id
 * @property int $customer_id
 * @property int|null $supplier_id
 * @property int|null $shipment_id
 * @property string|null $image
 * @property string|null $other_features
 * @property string $reference_number
 * @property int $matches
 * @property int $is_read
 * @property string|null $confirmed_at
 * @property string|null $expected_arrival
 * @property string|null $actual_arrival
 * @property string|null $shipment_date
 * @property string|null $expected_delivery_at
 * @property string|null $finalized_at
 * @property \Illuminate\Support\Carbon $deadline
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brands|null $brand
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expenses> $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoice
 * @property-read int|null $invoice_count
 * @property-read \App\Models\offers|null $offer
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereActualArrival($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereExpectedArrival($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereExpectedDeliveryAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereFinalizedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereMatches($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOtherFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereShipmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUpdatedAt($value)
 * @method static \Database\Factories\OrdersFactory factory($count = null, $state = [])
 * @property-read \App\Models\Proposal|null $proposal
 * @property string|null $name_for_warranty
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereNameForWarranty($value)
 * @property-read int|null $proposal_count
 * @property-read \App\Models\Shipment|null $shipment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Shipment> $shipments
 * @property-read int|null $shipments_count
 * @mixin \Eloquent
 */
class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'brand_id',
        'offer_id',
        'customer_id',
        'supplier_id',
        'shipment_id',
        'image',
        'other_features',
        'reference_number',
        'name_for_warranty',
        'matches',
        'is_read',
        'confirmed_at',
        'expected_arrival',
        'actual_arrival',
        'shipment_date',
        'expected_delivery_at',
        'finalized_at',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function brand()
    {
        return $this->belongsTo(Brands::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expenses::class, 'order_id');
    }

    public function invoice()
    {
        return $this->hasMany(Invoice::class, 'order_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offers::class);
    }

    public function proposal()
    {
        return $this->hasMany(Proposal::class, 'order_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

     public function shipments()
    {
        return $this->belongsToMany(Shipment::class, 'order_shipments');
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
                return 'Proposed';
            case 3:
                return 'SM Confirmed';
            case 4:
                return 'PM Confirmed';
            case 5:
                return 'Invoice Received';
            case 6:
                return 'invoice to Supplier Paid';
            case 7:
                return 'Invoice issued';
            case 8:
                return 'invoice from Customer Paid';
            case 9:
                return 'Ready for Shipment';
            case 10:
                return 'Delivered to the Customer';
            case 11:
                return 'Shipment booked';
            case 12:
                return 'Finalized';
            case 13:
                return 'Cancelled';
            case 14:
                return 'Expired';
            default:
                return $value;
        }
    }
}
