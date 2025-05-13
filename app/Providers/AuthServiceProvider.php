<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		//
	];

	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		VerifyEmail::toMailUsing(function ($notifiable, $url) {
			return (new MailMessage)
				->greeting('Xin chào, ' . $notifiable->name . '!')
				->line('Vui lòng nhấn nút bên dưới để xác minh địa chỉ email của bạn.')
				->action('Xác minh địa chỉ email', $url)
				->line('Nếu bạn không tạo tài khoản, không cần thực hiện thêm hành động nào.');
		});
	}
}
