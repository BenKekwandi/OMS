<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereUserId($value)
 * @mixin \Eloquent
 */
class Blocked_list extends Model
{
    use HasFactory;

    protected $table = 'blocked_lists';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'ip',
    ];
}
