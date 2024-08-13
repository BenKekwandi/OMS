<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $login_datetime
 * @property string $ip_address
 * @property string $country
 * @property string $region
 * @property string $user_agent
 * @property string $identifier
 * @property int $success
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login query()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereLoginDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereUserId($value)
 * @mixin \Eloquent
 */
class Auth_login extends Model
{
    use HasFactory;

    protected $table = 'auth_logins';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'login_datetime',
        'ip_address',
        'country',
        'region',
        'user_agent',
        'identifier',
        'success',
    ];

    public $timestamps = false;
}
