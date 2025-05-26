<?php

namespace Database\Seeders;

use App\Models\Playlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PlaylistSeeder extends Seeder
{
	private $names = [
		"Stray Sheep",
		"The book",
		"Happier than ever"
	];

	private $descriptions = [
		"Được phát hành vào ngày 5 tháng 8 năm 2020. Tên của album được lấy cảm hứng từ New Testament.",
		"The Book là EP đầu tay của Yoasobi. Phát hành vào ngày 6 tháng 1 năm 2021, thông qua Sony Music Entertainment Japan, cùng ngày với đĩa đơn Kaibutsu, đi kèm với mùa thứ hai của Beastars .",
		"Là album phòng thu thứ hai của Billie Eilish được ra mắt vào ngày 30 tháng 7 năm 2021 bởi hãng đĩa Darkroom và Interscope Records."
	];

	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$from = count(Playlist::all());
		for ($i = $from; $i < count($this->names); $i++) {
			DB::table('playlists')->insert([
				'name' => $this->names[$i],
				'description' => $this->descriptions[$i],
				'author_id' => $i + 1,
				'thumbnail' => '/' . $this->names[$i] . ' thumbnail.jpg',
				'type' => 1,
				'total_song' => 2,
				'price' => 0,
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
			]);
		}
	}
}
