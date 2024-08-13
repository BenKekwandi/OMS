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
 * @property string|null $address
 * @property string|null $primary_name
 * @property string|null $opening_time
 * @property string|null $closing_time
 * @property string|null $invoice_delivery_rules
 * @property string|null $tax
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Brands> $brands
 * @property-read int|null $brands_count
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereClosingTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereInvoiceDeliveryRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereOpeningTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier wherePrimaryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUserId($value)
 * @method static \Database\Factories\SupplierFactory factory($count = null, $state = [])
 * <<<<<<< HEAD
 * =======
 * @property bool $is_credit
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereIsCredit($value)
 * >>>>>>> 8202eef08cfb6a24bad17f5a5f1b59a1ba0f9588
 * @property-read \App\Models\User $pm
 * @mixin \Eloquent
 */
class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'country_id',
        'phone',
        'address',
        'primary_name',
        'opening_time',
        'closing_time',
        'invoice_delivery_rules',
        'tax',
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

    public function brands()
    {
        return $this->belongsToMany(Brands::class, 'supplier_brands', 'supplier_id', 'brand_id');
    }
    public function pm()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
