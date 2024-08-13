<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property-read \App\Models\ShipmentService|null $shipment_services
 * @method static \Database\Factories\ShipmentAccountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount query()
 * @property int $id
 * @property int $shipment_service_id
 * @property string|null $title
 * @property string|null $address
 * @property string|null $postmen_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount wherePostmenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount whereShipmentServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShipmentAccount extends Model
{
    use HasFactory;

    protected $table = 'shipment_accounts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'shipment_service_id',
        'title',
        'address',
        'postmen_id',
    ];
    protected function casts(): array
    {
        return [
            'shipment_service_id' => 'integer',
        ];
    }

    public function shipment_services()
    {
        return $this->belongsTo(ShipmentService::class, 'shipment_service_id');
    }

}
