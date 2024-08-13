<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * 
 *
 * @property int $id
 * @property string $reference
 * @property int $brand_id
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Models newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Models query()
 * @method static \Illuminate\Database\Eloquent\Builder|Models whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Models whereUpdatedAt($value)
 * @property-read \App\Models\Brands $brand
 * @method static \Database\Factories\ModelsFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Models extends Model
{
    use HasFactory;

    protected $table = 'models';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reference',
        'image',
        'brand_id',
    ];
 public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }
    
    public function getImageAttribute($value)
    {
        if ($value) {
            return Storage::disk('public')->url($value);
        }

        return Storage::disk('public')->url('models/1711243438_65ff80ae18c91.png');
    }
}
