<?php

namespace App\Services;

use App\Models\Bill;
use Illuminate\Support\Carbon;
use PayOS\PayOS;

class PayOSService
{
    private $payOS;

    /**
     * Constructor method to init PayOS config
     */
    public function __construct()
    {
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }

    /**
     * Service method to create payment link
     *
     * @param \App\Models\Bill $bill
     */
    public static function createPaymentLink(Bill $bill, $items)
    {
        $self = new self;
        try {
            $data = [
                'orderCode'   => $bill->order_code,
                'amount'      => $bill->total_price,
                'description' => 'Thanh toan hoa don ' . $bill->order_code,
                'items'       => $items,
                'returnUrl'   => 'http://localhost:5173/paysuccess',
                'cancelUrl'   => 'http://localhost:5173/payfail',
                'expiredAt'   => Carbon::now()->addMinutes(10)->timestamp
            ];
            $result = $self->payOS->createPaymentLink($data);

            return $result;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
