<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    private $names = [
        'Pop',
        'Rock',
        'Hip Hop',
        'Jazz',
        'Classical',
        'Electronic',
        'Anime',
        'Country',
        'Blues',
        'R&B'
    ];

    private $descriptions = [
        'Thể loại phổ biến với giai điệu bắt tai, ca từ đơn giản và dễ nghe.',
        'Thể loại mạnh mẽ với âm thanh guitar điện và trống đặc trưng, thường thể hiện cảm xúc mãnh liệt.',
        'Thể loại giàu nhịp điệu và lời rap, thể hiện câu chuyện và cảm xúc cá nhân.',
        'Thể loại ngẫu hứng với nhạc cụ sống động như saxophone, thường mang sắc thái thư giãn hoặc phức tạp.',
        'Âm nhạc bác học với cấu trúc tinh tế, thường được trình diễn bởi dàn nhạc cổ điển.',
        'Thể loại sử dụng âm thanh điện tử và beat lặp lại, phổ biến trong các lễ hội và câu lạc bộ đêm.',
        'Thể loại mang phong cách wibu, với nhịp điều và lời hát cảm xúc, như kể câu chuyện đầy xúc cảm của bản thân',
        'Thể loại âm nhạc truyền thống Mỹ, thường kể chuyện qua lời ca mộc mạc và giai điệu dịu dàng.',
        'Thể loại sâu lắng và đầy cảm xúc, khởi nguồn từ cộng đồng người Mỹ gốc Phi ở miền Nam nước Mỹ.',
        'Thể loại pha trộn giữa soul, funk và pop, nổi bật với giọng hát đầy nội lực và cảm xúc.'
    ];

    private $thumbnails = [
        '/pop.jpg',
        '/rock.jpg',
        '/hiphop.jpg',
        '/jazz.jpg',
        '/classical.jpg',
        '/electronic.jpg',
        '/anime.jpg',
        '/country.jpg',
        '/blues.jpg',
        '/r&b.jpg'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $from = count(Category::all());
        for ($i = $from; $i < $from + 10; $i++) {
            DB::table('categories')->insert([
                'name'        => $this->names[$i],
                'description' => $this->descriptions[$i],
                'thumbnail'   => $this->thumbnails[$i],
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ]);
        }
    }
}
