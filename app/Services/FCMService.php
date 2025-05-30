<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FCMService
{
	private $messaging;

	public function __construct()
	{
		$this->messaging = app('firebase.messaging');
	}

	/**
	 * Service method to send a firebase cloud messaging to user.
	 * @param string $device_token
	 * @param string $title 'title' for notify.
	 * @param string $body 'body' for notify.
	 * @param string $imageUrl 'image url' for notify, default = null.
	 * @return bool|mixed|\Illuminate\Http\JsonResponse
	 */
	public static function sendNotifyNewSong(string $device_token, string $title, string $body, string $imageUrl = null)
	{
		$self = new self();
		if (!is_null($device_token) && !empty($device_token)) {
			try {
				$message = CloudMessage::new()->withNotification(
					Notification::create(
						$title,
						$body,
						$imageUrl
					)
				)->toToken($device_token);
				$self->messaging->send($message);
				return true;
			} catch (\Throwable $th) {
				return false;
			}
		} else {
			return ApiResponse::internalServerError();
		}
	}
}