<?php

namespace Database\Seeders;

use App\Helpers\FileHelper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private $names = [
        'Kenshi Yonezu',
        'Yoasobi',
        'Billie Eilish',
        'Fujii Kaze',
        'Adele'
    ];

    private $emails = [
        'kenshi@gmail.com',
        'yoasobi@gmail.com',
        'billie@gmail.com',
        'fujikaze@gmail.com',
        'adele@gmail.com'
    ];

    private $descriptions = [
        'Kenshi Yonezu (米津玄師) là một nghệ sĩ đa tài người Nhật Bản: ca sĩ, nhạc sĩ, nhà sản xuất âm nhạc và họa sĩ minh họa. Sinh ngày 10 tháng 3 năm 1991 tại Tokushima, Nhật Bản, anh bắt đầu sự nghiệp âm nhạc vào năm 2009 dưới nghệ danh Hachi, chuyên sản xuất nhạc Vocaloid với phần mềm Hatsune Miku. Năm 2012, Yonezu ra mắt với tên thật và giọng hát thật qua album Diorama, đánh dấu bước chuyển mình từ nghệ sĩ ẩn danh trên mạng sang ngôi sao nhạc pop nổi tiếng tại Nhật Bản.',
        'YOASOBI là một nhóm nhạc pop Nhật Bản gồm hai thành viên: Ayase (nhạc sĩ, nhà sản xuất) và Ikura (ca sĩ, tên thật là Lilas Ikuta). Ra mắt vào năm 2019, họ nổi bật với mô hình "chuyển thể tiểu thuyết thành âm nhạc", tạo ra các ca khúc dựa trên truyện ngắn từ nền tảng Monogatary.com và các tác phẩm văn học khác.',
        'Billie Eilish Pirate Baird O’Connell, sinh ngày 18 tháng 12 năm 2001 tại Los Angeles, California, là một ca sĩ kiêm nhạc sĩ người Mỹ. Cô nổi tiếng với phong cách âm nhạc độc đáo, kết hợp giữa pop, alternative và electropop, cùng với hình ảnh nghệ thuật đậm chất cá nhân.',
        'Fujii Kaze là một nghệ sĩ trẻ tài năng người Nhật Bản, nổi bật với giọng hát nội lực, khả năng sáng tác sâu sắc và phong cách biểu diễn độc đáo. Anh bắt đầu sự nghiệp với các video cover trên YouTube, nhanh chóng thu hút sự chú ý nhờ kỹ năng chơi piano điêu luyện và phong cách âm nhạc pha trộn giữa pop, R&B, soul và jazz. Với những ca khúc như Shinunoga E-Wa, Kirari và Matsuri, Fujii Kaze đã chinh phục đông đảo khán giả không chỉ tại Nhật Bản mà còn trên toàn thế giới. Âm nhạc của anh thường mang thông điệp tích cực, sâu lắng, và truyền cảm hứng, giúp anh trở thành một trong những nghệ sĩ trẻ nổi bật nhất của J-pop hiện đại.',
        'Adele là một ca sĩ kiêm nhạc sĩ người Anh nổi tiếng với giọng hát đầy nội lực, cảm xúc và phong cách âm nhạc đậm chất soul, pop và blues. Cô bắt đầu nổi bật từ năm 2008 với album đầu tay 19, nhanh chóng gây ấn tượng với khán giả toàn cầu nhờ chất giọng trầm ấm và ca từ chân thành. Những bản hit như Someone Like You, Rolling in the Deep, và Hello đã đưa cô trở thành một trong những nghệ sĩ bán chạy nhất mọi thời đại. Âm nhạc của Adele thường xoay quanh các chủ đề về tình yêu, nỗi đau và sự trưởng thành, được thể hiện bằng lối hát giàu cảm xúc và chân thật. Không chỉ thành công về mặt thương mại, cô còn nhận được vô số giải thưởng danh giá như Grammy, Oscar và Brit Awards, khẳng định vị trí vững chắc trong nền âm nhạc thế giới.'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $from = count(User::all());
        for ($i = $from; $i < count($this->names); $i++) {
            $user = new User;
            $user->email = $this->emails[$i];
            DB::table('users')->insert([
                'name'              => $this->names[$i],
                'email'             => $this->emails[$i],
                'description'       => $this->descriptions[$i],
                'password'          => Hash::make('12345678'),
                'avatar'            => '/' . FileHelper::getNameFromEmail($user) . '.jpg',
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ]);
        }
    }
}
