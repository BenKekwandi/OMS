<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property float $amount
 * @property int $invoice_id
 * @property \Illuminate\Support\Carbon $paid_at
 * @property int $order_id
 * @property int $expenses_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Orders|null $orders
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereExpensesTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereUpdatedAt($value)
 * @property-read \App\Models\Expenses_type $expenses_type
 * @property int $expense_type_id
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses whereExpenseTypeId($value)
 * @mixin \Eloquent
 */
class Expenses extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'amount',
        'paid_at',
        'expenses_type_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->belongsTo(Orders::class);
    }

    public function expenses_type()
    {
        return $this->belongsTo(Expenses_type::class);
    }

    public function getPaidAtAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d');
        }
    }
}
