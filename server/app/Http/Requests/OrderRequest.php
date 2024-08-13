<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        if (!($user->hasrole('admin') || $user->hasrole('pm') || $user->hasrole('sm') || $user->hasrole('accounting'))) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['integer'],
            'offer_id' => ['integer'],
            'from' => ['date'],
            'to' => ['date'],
            'payment_deadline_from' => ['date'],
            'payment_deadline_to' => ['date'],
            'net_price_from' => ['numeric'],
            'net_price_to' => ['numeric'],
            'date_invoice_from' => ['date'],
            'date_invoice_to' => ['date'],
            'shipment_date_from' => ['date'],
            'shipment_date_to' => ['date'],
            'confirm_from' => ['date'],
            'confirm_to' => ['date'],
            'expected_arrival_from' => ['date'],
            'expected_arrival_to' => ['date'],
            'actual_arrival_from' => ['date'],
            'actual_arrival_to' => ['date'],
            'model' => 'string',
            'user' => 'integer',
            'customer' => 'integer',
            'supplier' => 'integer',
            'brand' => 'integer',
            // 'status' => ['array'],
            'status.*' => 'integer',
            'availability' => 'integer',
            'with_image'=>''
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {

            if (($this->filled('from') && !$this->filled('to')) || ($this->filled('to') && !$this->filled('from'))) {
                $validator->errors()->add('from', 'Both start and end date ranges are required.');
                $validator->errors()->add('to', 'Both start and end date ranges are required.');
            }

            if (($this->filled('payment_deadline_from') && !$this->filled('payment_deadline_to')) || ($this->filled('payment_deadline_to') && !$this->filled('payment_deadline_from'))) {
                $validator->errors()->add('payment_deadline_from', 'Both start and end deadline ranges are required.');
                $validator->errors()->add('payment_deadline_to', 'Both start and end deadline ranges are required.');
            }

            if (($this->filled('confirm_from') && !$this->filled('confirm_to')) || ($this->filled('confirm_to') && !$this->filled('confirm_from'))) {
                $validator->errors()->add('confirm_from', 'Both start and end confirm ranges are required.');
                $validator->errors()->add('confirm_to', 'Both start and end confirm ranges are required.');
            }

            if (($this->filled('actual_arrival_from') && !$this->filled('actual_arrival_to')) || ($this->filled('actual_arrival_to') && !$this->filled('actual_arrival_from'))) {
                $validator->errors()->add('actual_arrival_from', 'Both start and end actual_arrival ranges are required.');
                $validator->errors()->add('actual_arrival_to', 'Both start and end actual_arrival ranges are required.');
            }

            if (($this->filled('expected_arrival_from') && !$this->filled('expected_arrival_to')) || ($this->filled('expected_arrival_to') && !$this->filled('expected_arrival_from'))) {
                $validator->errors()->add('expected_arrival_from', 'Both start and end expected_arrival ranges are required.');
                $validator->errors()->add('expected_arrival_to', 'Both start and end expected_arrival ranges are required.');
            }

            if (($this->filled('date_invoice_from') && !$this->filled('date_invoice_to')) || ($this->filled('date_invoice_to') && !$this->filled('date_invoice_from'))) {
                $validator->errors()->add('date_invoice_from', 'Both start and end invoice dates ranges are required.');
                $validator->errors()->add('date_invoice_to', 'Both start and end invoice dates ranges are required.');
            }

            if (($this->filled('shipment_date_from') && !$this->filled('shipment_date_to')) || ($this->filled('shipment_date_to') && !$this->filled('shipment_date_from'))) {
                $validator->errors()->add('shipment_date_from', 'Both start and end invoice dates ranges are required.');
                $validator->errors()->add('shipment_date_to', 'Both start and end invoice dates ranges are required.');
            }

            //--------------------------------------------------------------------------------------------------------------------------

            if (($this->filled('from') && $this->filled('to')) && (Carbon::parse($this->from)->gt(Carbon::parse($this->to)))) {
                $validator->errors()->add('from', 'The start date must be before the end date.');
                $validator->errors()->add('to', 'The end date must be after the start date.');
            }

            if (($this->filled('payment_deadline_from') && $this->filled('payment_deadline_to')) && (Carbon::parse($this->payment_deadline_from)->gt(Carbon::parse($this->payment_deadline_to)))) {
                $validator->errors()->add('payment_deadline_from', 'The start deadline date must be before the end deadline date.');
                $validator->errors()->add('payment_deadline_to', 'The end deadline date must be after the start deadline date.');
            }

            if (($this->filled('confirm_from') && $this->filled('confirm_to')) && (Carbon::parse($this->confirm_from)->gt(Carbon::parse($this->confirm_to)))) {
                $validator->errors()->add('confirm_from', 'The start confirm date must be before the end confirm date.');
                $validator->errors()->add('confirm_to', 'The end confirm date must be after the start confirm date.');
            }

            if (($this->filled('actual_arrival_from') && $this->filled('actual_arrival_to')) && (Carbon::parse($this->actual_arrival_from)->gt(Carbon::parse($this->actual_arrival_to)))) {
                $validator->errors()->add('actual_arrival_from', 'The start actual_arrival date must be before the end actual_arrival date.');
                $validator->errors()->add('actual_arrival_to', 'The end actual_arrival date must be after the start actual_arrival date.');
            }

            if (($this->filled('expected_arrival_from') && $this->filled('expected_arrival_to')) && (Carbon::parse($this->expected_arrival_from)->gt(Carbon::parse($this->expected_arrival_to)))) {
                $validator->errors()->add('expected_arrival_from', 'The start expected_arrival date must be before the end expected_arrival date.');
                $validator->errors()->add('expected_arrival_to', 'The end expected_arrival date must be after the start expected_arrival date.');
            }

            if (($this->filled('date_invoice_from') && $this->filled('date_invoice_to')) && (Carbon::parse($this->date_invoice_from)->gt(Carbon::parse($this->date_invoice_to)))) {
                $validator->errors()->add('date_invoice_from', 'The start date_invoice date must be before the end date_invoice date.');
                $validator->errors()->add('date_invoice_to', 'The end date_invoice date must be after the start date_invoice date.');
            }

            if (($this->filled('shipment_date_from') && $this->filled('shipment_date_to')) && (Carbon::parse($this->shipment_date_from)->gt(Carbon::parse($this->shipment_date_to)))) {
                $validator->errors()->add('shipment_date_from', 'The start shipment_date date must be before the end shipment_date date.');
                $validator->errors()->add('shipment_date_to', 'The end shipment_date date must be after the start shipment_date date.');
            }
        });
    }
}
