<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelInvoice extends Model
{
    use HasFactory;

    protected $table = 'label_invoices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'label_id',
        'kind',
        'serial_number',
        'copies',
        'date',
    ];

    protected $casts = [
        'label_id' => 'integer',
        'kind' => 'integer',
        'copies' => 'integer',
        'date' => 'datetime',
    ];

    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id');
    }

}
