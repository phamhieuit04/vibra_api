<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendAppreciation extends Mailable
{
	use Queueable, SerializesModels;

	private $items;
	private $totalPrice;

	/**
	 * Create a new message instance.
	 */
	public function __construct($items, $totalPrice)
	{
		$this->items = $items;
		$this->totalPrice = $totalPrice;
	}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			subject: 'Cảm ơn bạn đã thanh toán - Hóa đơn của bạn được đính kèm!',
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'mails.send_appreciation',
			with: [
				'user' => Auth::user(),
				'items' => $this->items,
				'totalPrice' => $this->totalPrice
			]
		);
	}

	/**
	 * Get the attachments for the message.
	 *
	 * @return array<int, \Illuminate\Mail\Mailables\Attachment>
	 */
	public function attachments(): array
	{
		$attachments = collect([]);
		$this->items->each(function ($item) use ($attachments) {
			$attachments->push(
				Attachment::fromPath($item['path'])->withMime(mime_content_type($item['path']))
			);
		});
		return $attachments->toArray();
	}
}
