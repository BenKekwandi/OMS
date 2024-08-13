<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Database\Factories\ShipmentServiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService query()
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipmentService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShipmentService extends Model
{
    use HasFactory;

    protected $table = 'shipment_services';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'description',
    ];
}
