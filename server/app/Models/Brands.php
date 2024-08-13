<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Brands newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Models> $models
 * @property-read int|null $models_count
 * @method static \Database\Factories\BrandsFactory factory($count = null, $state = [])
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Supplier> $suppliers
 * @property-read int|null $suppliers_count
 * @mixin \Eloquent
 */
class Brands extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

     public function models()
    {
        return $this->hasMany(Models::class);
    }
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_brands');
    }
}
