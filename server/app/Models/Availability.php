<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Availability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Availability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Availability query()
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Availability extends Model
{
    use HasFactory;

    protected $table = 'availability';

    protected $primaryKey = 'id';
}
