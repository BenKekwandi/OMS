<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string|null $file
 * @property float|null $amount
 * @property int|null $invoice_company_id
 * @property string|null $invoice_number
 * @property \Illuminate\Support\Carbon|null $invoicing_date
 * @property \Illuminate\Support\Carbon|null $payment_deadline
 * @property bool $is_customer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Orders $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereInvoicingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereIsCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice wherePaymentDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereUpdatedAt($value)
 * @property bool $is_paid
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereIsPaid($value)
 * @property bool $is_real
 * @method static \Illuminate\Database\Eloquent\Builder|Invoice whereIsReal($value)
 * @property-read \App\Models\Invoice_company $invoice_company
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'file',
        'amount',
        'invoice_company_id',
        'invoice_number',
        'invoicing_date',
        'payment_deadline',
        'is_customer',
        'is_paid',
        'is_real',
    ];

    protected $casts = [
        'invoicing_date' => 'datetime',
        'payment_deadline' => 'datetime',
        'is_customer' => 'boolean',
        'is_paid' => 'boolean',
        'is_real' => 'boolean',
    ];

    public function getFileAttribute($value)
    {
        if ($value) {
            return Storage::disk('public')->url($value);
        }
    }

    public function getInvoicingDateAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }

    public function getPaymentDeadlineAttribute($value)
    {
        if ($value) {
            return (new DateTime($value))->format('Y-m-d H:i');
        }
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    public function invoice_company()
    {
        return $this->belongsTo(Invoice_company::class);
    }
}
