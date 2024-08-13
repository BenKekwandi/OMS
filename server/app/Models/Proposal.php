<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $offer_id
 * @property float $sell_price
 * @property string|null $notes
 * @property int $delivery_days
 * @property int $profit
 * @property int $status
 * @property string $applied_at
 * @property string|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\offers $offer
 * @property-read \App\Models\Orders $order
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereAppliedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereDeliveryDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'offer_id',
        'sell_price',
        'notes',
        'delivery_days',
        'profit',
        'status',
        'applied_at',
        'confirmed_at',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offers::class);
    }

    public function getStatusAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'Awaits SM confirmation';
            case 1:
                return 'Awaits PM confirmation';
            case 2:
                return 'Completed';
            case 3:
                return 'Cancelled';
            default:
                return $value;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }
}
