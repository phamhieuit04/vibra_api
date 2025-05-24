<?php

namespace App\Services;

use App\Models\Bill;
use PayOS\PayOS;
use function Laravel\Prompts\select;

class PayOSService
{
    private $payOS;
    public function __construct()
    {
        $this->payOS = new PayOS(env('CLIENT_ID'), env('API_KEY'), env('CHECKSUM_KEY'));
    }
    public static function createPaymentLink(Bill $bill)
    {
        $self = new self();
        try {
            $data = [
                "orderCode" => $bill->id,
                "amount" => $bill->total_price,
                "description" => "Thanh toan hoa don " . $bill->id,
                "returnUrl" => "http://localhost:5173/verify",
                "cancelUrl" => "http://localhost:5173/verify"
            ];
            $result = $self->payOS->createPaymentLink($data);
            return $result;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
