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
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Expenses_type extends Model
{
    use HasFactory;

    protected $table = 'expenses_types';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
