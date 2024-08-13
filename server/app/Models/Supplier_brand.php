<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property int $supplier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Supplier_brand extends Model
{
    use HasFactory;

    protected $table = 'supplier_brands';

    protected $primaryKey = 'id';

    protected $fillable = [
        'brand_id',
        'supplier_id',
    ];

}
