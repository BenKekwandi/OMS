<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property int $country_id
 * @property string|null $phone
 * @property string|null $shipping_address
 * @property string|null $billing_address
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUserId($value)
 * @method static \Database\Factories\CustomerFactory factory($count = null, $state = [])
 * @property string|null $contact_name
 * @property bool $is_credit
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIsCredit($value)
 * @property-read \App\Models\User $sm
 * @mixin \Eloquent
 */
class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'country_id',
        'phone',
        'shipping_address',
        'billing_address',
        'is_credit',
        'user_id',
    ];

    protected $casts = [
        'is_credit' => 'boolean',
    ];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function sm()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
