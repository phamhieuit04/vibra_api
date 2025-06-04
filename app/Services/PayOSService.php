<?php

namespace App\Services;

use App\Models\Bill;
use Illuminate\Support\Carbon;
use PayOS\PayOS;
use function Laravel\Prompts\select;

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
	 * @param \App\Models\Bill $bill
	 */
	public static function createPaymentLink(Bill $bill)
	{
		$self = new self();
		$items = [];
		try {
			if ($bill->playlist_id == null) {
				$songs = Bill::where('bills.id', $bill->id)
					->join('bill_details', 'bills.id', 'bill_details.bill_id')
					->join('songs', 'songs.id', 'bill_details.song_id')
					->first();
				$items = [
					[
						'name' => $songs->name,
						'quantity' => 1,
						'price' => $songs->price
					]
				];
			} else {
				$songs = Bill::where('bills.id', $bill->id)
					->join('playlists', 'bills.playlist_id', 'playlists.id')
					->join('songs', 'songs.playlist_id', 'playlists.id')
					->get();
				foreach ($songs as $song) {
					array_push($items, [
						'name' => $song->name,
						'quantity' => 1,
						'price' => $song->price
					]);
				}
			}
			$data = [
				"orderCode" => $bill->order_code,
				"amount" => $bill->total_price,
				"description" => "Thanh toan hoa don " . $bill->order_code,
				"items" => $items,
				"returnUrl" => "http://localhost:5173/paysuccess",
				"cancelUrl" => "http://localhost:5173/payfail",
				"expiredAt" => Carbon::now()->addMinutes(10)->timestamp
			];
			$result = $self->payOS->createPaymentLink($data);
			return $result;
		} catch (\Throwable $th) {
			return null;
		}
	}
}
