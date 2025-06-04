<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Send Greeting</title>
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
			<h2 style="color: #1d1d1f; text-align: justify;">Chào mừng bạn đến với Vibra - nơi âm nhạc sống dậy theo
				cách của bạn.</h2>
			<p style="font-size: 16px; color: #333; line-height: 22px; text-align: justify;">Từ những bản hit mới nhất
				đến giai điệu xưa cũ,
				từ chill nhẹ đến
				EDM bùng nổ - bạn đều có thể tìm thấy ở đây.
				Chúng tôi rất vui được đồng hành cùng bạn trên hành trình khám phá âm nhạc không giới hạn.</p>

			<p style="font-size: 16px; color: #333; line-height: 5px;">Bắt đầu ngay hôm nay:</p>
			<ul style="list-style-type: none;">
				<li style="padding-bottom: 6px; font-size: 16px; color: #333; line-height: 20px; font-style: italic;">
					Nghe không giới hạn
				</li>
				<li style="padding-bottom: 6px; font-size: 16px; color: #333; line-height: 20px; font-style: italic;">
					Tạo playlist yêu thích
				</li>
				<li style="font-size: 16px; color: #333; line-height: 20px; font-style: italic;">Cập nhật nhạc mới mỗi
					ngày</li>
			</ul>

			<h3 style="color: #1d1d1f; text-align: justify;">Cảm ơn bạn đã tin tưởng và lựa chọn chúng tôi. Chúc bạn có
				những giờ phút thư
				giãn cùng âm nhạc!</h3>

			<p style=" margin-top: 40px; color: #1d1d1f;">Trân trọng,</p>
			<p style="color: #1d1d1f;">Vibra</p>
		</div>

		<div style="margin-top: 40px; font-size: 12px; color: #999; text-align: center;">
			© 2025 Vibra. All rights reserved.
		</div>
	</div>
</body>

</html>