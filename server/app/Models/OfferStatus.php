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
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfferStatus extends Model
{
    use HasFactory;

    protected $table = 'offer_status';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
    ];
}
