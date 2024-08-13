<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Database\Factories\OfficeAddressFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress query()
 * @property int $id
 * @property string $contact_name
 * @property string|null $company
 * @property string $street_1
 * @property string|null $street_2
 * @property string|null $street_3
 * @property string $city
 * @property string|null $state
 * @property string $post_code
 * @property string $country
 * @property string|null $tax
 * @property string $email
 * @property string $phone
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereStreet1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereStreet2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereStreet3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeAddress whereUpdatedAt($value)
 * @property string $contact_Name
 * @mixin \Eloquent
 */
class OfficeAddress extends Model
{
    use HasFactory;
    protected $table = 'office_addresses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'contact_name',
        'company',
        'street_1',
        'street_2',
        'street_3',
        'city',
        'state',
        'country',
        'email',
        'post_code',
        'tax',
        'phone',
    ];

}
