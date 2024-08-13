<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $company
 * @property string $country
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereUpdatedAt($value)
 * @property string|null $phone
 * @property string|null $contact_name
 * @method static \Database\Factories\Invoice_companyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company wherePhone($value)
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice_company whereName($value)
 * @mixin \Eloquent
 */
class Invoice_company extends Model
{
    use HasFactory;

    protected $table = 'invoice_companies';

    protected $primaryKey = 'id';

    protected $fillable = [
        'company',
        'country',
        'location',
        'phone',
        'contact_name',
    ];
}
