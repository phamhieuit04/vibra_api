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

	public static function sendNotifyNewSong($user, string $title, string $body, string $imageUrl = null)
	{
		$self = new self();
		if (!is_null($user) && !is_null($user->device_token)) {
			try {
				$message = CloudMessage::new()->withNotification(
					Notification::create(
						$title,
						$body,
						$imageUrl
					)
				)->toToken($user->device_token);
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