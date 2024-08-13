<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $login_datetime
 * @property string $ip_address
 * @property string $country
 * @property string $region
 * @property string $user_agent
 * @property string $identifier
 * @property int $success
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login query()
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereLoginDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Auth_login whereUserId($value)
 */
	class Auth_login extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Availability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Availability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Availability query()
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Availability whereUpdatedAt($value)
 */
	class Availability extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blocked_list whereUserId($value)
 */
	class Blocked_list extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Brands newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereUpdatedAt($value)
 */
	class Brands extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property float $vat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereVat($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Customer_invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer_invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer_invoice query()
 */
	class Customer_invoice extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Expenses extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expenses_type whereUpdatedAt($value)
 */
	class Expenses_type extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Invoice extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Invoice_company extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Models extends \Eloquent {}
}

namespace App\Models{
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
 */
	class OfferStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderStatus whereUpdatedAt($value)
 */
	class OrderStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $offer_id
 * @property int $brand_id
 * @property int $customer_id
 * @property int|null $supplier_id
 * @property int|null $shipment_id
 * @property string|null $image
 * @property string|null $other_features
 * @property string $reference_number
 * @property int $matches
 * @property int $is_read
 * @property string|null $confirmed_at
 * @property string|null $expected_arrival
 * @property string|null $actual_arrival
 * @property string|null $shipment_date
 * @property string|null $expected_delivery_at
 * @property string|null $finalized_at
 * @property \Illuminate\Support\Carbon $deadline
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brands|null $brand
 * @property-read \App\Models\Customer $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Expenses> $expenses
 * @property-read int|null $expenses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoice
 * @property-read int|null $invoice_count
 * @property-read \App\Models\offers|null $offer
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereActualArrival($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereExpectedArrival($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereExpectedDeliveryAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereFinalizedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereMatches($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereOtherFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereShipmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Orders whereUpdatedAt($value)
 */
	class Orders extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property float $amount
 * @property \Illuminate\Support\Carbon $paid_at
 * @property int $invoice_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice $invoices
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property int $offer_id
 * @property float $sell_price
 * @property string|null $notes
 * @property int $delivery_days
 * @property int $profit
 * @property int $status
 * @property string $applied_at
 * @property string|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\offers $offer
 * @property-read \App\Models\Orders $order
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereAppliedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereDeliveryDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereUpdatedAt($value)
 */
	class Proposal extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Requests newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requests query()
 */
	class Requests extends \Eloquent {}
}

namespace App\Models{
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
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $brand_id
 * @property int $supplier_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier_brand whereUpdatedAt($value)
 */
	class Supplier_brand extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $country
 * @property string $phone
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property int $active
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Supplier|null $supplier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $country
 * @property string $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 */
	class Warehouse extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $order_days
 * @property int $availability
 * @property int $brand_id
 * @property int $supplier_id
 * @property string $reference_number
 * @property string|null $image
 * @property string|null $other_features
 * @property float $discount
 * @property float $net_price
 * @property float $rrp_price
 * @property string|null $rrp_explanation
 * @property int|null $warehouse_id
 * @property string|null $serial_number
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Brands $brand
 * @property-read \App\Models\Supplier $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|offers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|offers query()
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereNetPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereOrderDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereOtherFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereRrpExplanation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereRrpPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereSerialNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|offers whereWarehouseId($value)
 */
	class offers extends \Eloquent {}
}

