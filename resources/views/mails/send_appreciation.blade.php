<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Send Appreciation</title>
</head>

<body style="margin: 0; padding: 0; background-color: #eef3f8; font-family: Arial, sans-serif;">
	<div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
		<div style="text-align: center; margin-bottom: 30px;">
			<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Spotify_logo_without_text.svg/2048px-Spotify_logo_without_text.svg.png"
				alt="Vibra Logo" style="width: 50px;">
		</div>
		<div
			style="background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.05); text-align: left;">
			<h1 style="color: #1d1d1f;">Xin chào {{ $user->name }},</h1>
			<h2 style="color: #1d1d1f; text-align: justify;">Cảm ơn bạn đã hoàn tất thanh toán tại Vibra.</h2>
			<p style="font-size: 16px; color: #333; text-align: justify;">Chúng tôi rất vui khi được đồng hành cùng bạn
				trên hành trình khám phá âm nhạc không giới hạn.</p>
			<p style="font-size: 16px; color: #333;">Hóa đơn chi tiết như sau:</p>

			<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
				<thead>
					<tr style="background-color: #f0f0f0;">
						<th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Bài hát</th>
						<th style="padding: 12px; border: 1px solid #ddd; text-align: right;">Số lượng</th>
						<th style="padding: 12px; border: 1px solid #ddd; text-align: right;">Đơn giá</th>
						<th style="padding: 12px; border: 1px solid #ddd; text-align: right;">Thành tiền</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($items as $item)
						<tr>
							<td style="padding: 12px; border: 1px solid #ddd;">{{ $item['name'] }}</td>
							<td style="padding: 12px; border: 1px solid #ddd; text-align: right;">{{ $item['quantity'] }}
							</td>
							<td style="padding: 12px; border: 1px solid #ddd; text-align: right;">{{ $item['price'] }}</td>
							<td style="padding: 12px; border: 1px solid #ddd; text-align: right;">{{ $item['price'] }}</td>
						</tr>
					@endforeach
					<tr style="background-color: #f9f9f9;">
						<td colspan="3"
							style="padding: 12px; border: 1px solid #ddd; text-align: right; font-weight: bold;">Tổng
							cộng</td>
						<td style="padding: 12px; border: 1px solid #ddd; text-align: right; font-weight: bold;">
							{{ $totalPrice }}
						</td>
					</tr>
				</tbody>
			</table>

			<p style="margin-top: 40px; color: #1d1d1f;">Trân trọng,</p>
			<p style="color: #1d1d1f;">Vibra</p>
		</div>

		<div style="margin-top: 40px; font-size: 12px; color: #999; text-align: center;">
			© 2025 Vibra. All rights reserved.
		</div>
	</div>
</body>

</html>