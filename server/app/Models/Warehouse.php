<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $country
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @method static \Database\Factories\WarehouseFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'country',
        'location',
    ];
}
