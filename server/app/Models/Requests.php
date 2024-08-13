<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Requests newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests query()
 * @mixin \Eloquent
 */
class Requests extends Model
{
    use HasFactory;

    protected $table = 'requests';

    protected $primaryKey = 'id';

    protected $fillable = [
        'brand_id',
        'model_id',
        'customer_id',
        'deadline',
        'description',
        'status',
    ];
}
