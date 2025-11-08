<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->count() === 0 || $users->count() === 0) {
            $this->command->info('Không có sản phẩm hoặc người dùng để tạo review.');
            return;
        }

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            Review::create([
                'product_id' => $products->random()->product_id,
                'user_id' => $users->random()->user_id,
                'rating' => $faker->numberBetween(2, 5),
                'comment' => $faker->randomElement([
                    'Mình rất hài lòng sản phẩm này! Giá cả hợp lý, chất lượng tốt, phục vụ tốt. Mình sẽ mua lại!',

                    'Mình dùng được gần 2 tuần rồi, phải công nhận chất lượng build rất tốt, cầm chắc tay,
                    không ọp ẹp gì. Hiệu năng thì khỏi bàn, mở app nhanh như chớp, đa nhiệm mượt mà không hề giật. 
                    Màn hình hiển thị đẹp, màu sắc tươi tắn, xem phim hay lướt web đều đã mắt. Pin dùng cả ngày dài 
                    vẫn thoải mái, tối về cắm sạc 30 phút là đầy. Giá hơi cao nhưng xứng đáng với trải nghiệm, ai 
                    cần máy bền bỉ lâu dài thì nên cân nhắc nhé!',

                    'Lên đời từ đời cũ sang cái này thấy khác biệt rõ rệt luôn! Tốc độ xử lý nhanh hơn hẳn, 
                    không còn tình trạng đứng hình khi mở nhiều tab. Camera chụp đẹp tự nhiên, không bị rỗ hay lố màu. 
                    Phần mềm cập nhật thường xuyên, fix lỗi nhanh. Dùng ngoài trời nắng gắt vẫn thấy rõ màn hình, 
                    chống chói tốt. Pin trâu, chơi game 2-3 tiếng chỉ tụt 20-25%. Cảm giác cầm nắm thoải mái, 
                    không mỏi tay. Tổng thể hài lòng, sẽ ủng hộ hãng tiếp!',

                    'Từ ngày có em nó, công việc lẫn giải trí lên level luôn! Đa nhiệm mượt, chuyển app không reload, 
                    tiết kiệm thời gian kinh khủng. Màn hình lớn, độ phân giải cao, làm việc với bảng tính 
                    hay chỉnh ảnh đều nét. Thiết kế sang trọng, để bàn làm việc nhìn chuyên nghiệp hẳn. 
                    Tích hợp AI thông minh, gợi ý nhanh, học thói quen người dùng tốt. Pin dùng 2 ngày mới 
                    phải sạc nếu chỉ lướt web + mail. Giá trị vượt mong đợi, đáng đầu tư dài hạn!',

                    'Mới “rinh” về hôm qua mà đã mê mẩn luôn! Thiết kế mỏng nhẹ, bỏ balo không chiếm chỗ, 
                    màu sắc sang chảnh nhìn là thích. Hiệu năng đỉnh cao, mở phần mềm nặng chỉ 2-3 giây 
                    là vào. Màn hình độ sáng cao, ra nắng vẫn đọc được tin nhắn rõ ràng. Loa stereo âm lượng lớn, 
                    bass ấm, nghe Zoom hay Spotify đều phê. Pin dùng liên tục 7-8 tiếng vẫn còn 40%, 
                    sạc nhanh 30 phút được 60%. Dịch vụ bảo hành nhanh gọn, yên tâm xài lâu dài. 10/10 không bàn cãi!',

                    'Mình vốn kén chọn đồ công nghệ nhưng cái này làm mình ưng hết nấc! Giao diện thân thiện, 
                    bố cục khoa học, mới dùng 5 phút đã quen. Kết nối ổn định, không rớt sóng dù ở tầng hầm. 
                    Camera cho ảnh chi tiết cao, chụp thiếu sáng vẫn nét. Độ bền thì khỏi chê, làm rơi nhẹ vài lần 
                    vẫn nguyên vẹn. Cập nhật phần mềm đều đặn, thêm tính năng mới liên tục. Pin trâu bò, 
                    dùng hỗn hợp 10 tiếng vẫn còn dư. Giá sale hợp lý, không hối hận khi xuống tiền. Sẽ giới thiệu bạn bè!',

                    'Mình mua cái này với hy vọng lên đời hiệu năng, ai dè dùng được 1 tháng là hối hận luôn! 
                    Pin tụt kinh hoàng, mới mở app 2-3 cái là xuống dưới 50%, phải cắm sạc suốt ngày. 
                    Màn hình thì hay bị ám vàng, xem phim màu sắc nhợt nhạt, không đã mắt chút nào. 
                    Camera chụp thiếu sáng thì tối om, noise tùm lum. Giá bán cao mà chất lượng build ọp ẹp, 
                    viền màn hình dày cộm. Hỗ trợ khách hàng thì chậm chạp, gọi mãi không ai nghe. 
                    Lần sau sẽ không mua hãng này nữa, phí tiền!',

                    'Sản phẩm trông đẹp trên ảnh nhưng thực tế dùng thì thất vọng tràn trề. Kết nối Wi-Fi hay rớt, 
                    phải reset router liên tục mới ổn. Loa ngoài rè rè khi tăng volume, nghe nhạc bass yếu xìu, 
                    không có chiều sâu. Phần mềm lag kinh khủng, cập nhật mãi mà vẫn giật khi đa nhiệm. 
                    Pin hứa hẹn 10 tiếng nhưng thực tế chỉ 4-5 tiếng là hết sạch. Đóng gói thì sơ sài, phụ kiện thiếu thốn. 
                    Mình dùng để làm việc mà cứ phải restart hoài, mất thời gian kinh khủng. Không recommend ai luôn!',

                    'Cái laptop này đúng kiểu "trông mèo đóng gấu", quảng cáo mượt mà nhưng dùng thực tế thì tệ hại. 
                    Chip xử lý yếu, mở Photoshop hay code Python là lag ù ù, render file nhỏ cũng mất nửa tiếng. 
                    Bàn phím gõ cọc cạch, hành trình phím nông, gõ lâu mỏi tay. Màn hình góc nhìn hẹp, nghiêng tí 
                    là màu xỉn đi. Pin dùng văn phòng nhẹ chỉ 2-3 tiếng là báo yếu, phải cắm sạc. Cập nhật driver 
                    thì hay bị brick, phải restore mất cả buổi. Giá trung bình nhưng chất lượng như đồ second-hand. 
                    Không đáng tiền, bán lại lỗ nặng!',

                    'Mình vốn tin tưởng hãng này nhưng lần này thì thất vọng nặng nề. Tai nghe kết nối Bluetooth 
                    hay ngắt quãng, nghe giữa chừng thì im bặt, phải pair lại mất công. Âm thanh thì treble chói tai, 
                    bass lỏng lẻo, nghe nhạc pop hay rock đều không phê. Pin chỉ trụ 3 tiếng liên tục, quảng cáo 20 tiếng 
                    là bịp bợm. Chất liệu nhựa bóng loáng nhưng bám vân tay kinh, lau hoài không sạch. App đồng bộ thì lỗi 
                    liên tục, không sync được playlist. Mua sale mà vẫn thấy đắt đỏ, lần sau sẽ chọn hãng khác an toàn hơn.',

                    'Pin tụt nhanh kinh khủng, dùng 3 tiếng đã hết sạch, quảng cáo 10 tiếng là nói xạo.',
                    'Màn hình đẹp nhưng độ sáng thấp, ra nắng nhìn không rõ, phải che tay mới đọc được.',
                    'Tai nghe kết nối hay ngắt, nghe nhạc giữa chừng im bặt, phải pair lại liên tục.',
                    'Camera chụp ban đêm noise tùm lum, ảnh tối thui, không xài được gì luôn.',
                    'Phần mềm lag, mở app nặng là đứng hình, cập nhật mãi vẫn không khá hơn.',
                    'Loa ngoài rè khi tăng volume, bass yếu xìu, nghe nhạc chán hẳn.',
                    'Sạc chậm như rùa, 1 tiếng mới đầy 30%, trong khi hứa sạc nhanh 30, phút.',
                    'Máy nóng ran khi chơi game, tản nhiệt kém, cầm 10 phút đã bỏng tay.',
                    'Giao diện rối, icon lộn xộn, mới dùng 5 phút đã bực mình.',
                    'Hỗ trợ khách hàng chậm, gọi mãi không ai nghe, chat thì bot, trả lời vòng vo.',


                ]),
                'review_date' => $faker->dateTimeBetween('-2 years', 'now'),
            ]);
        }

        $this->command->info('Reviews seeded successfully!');
    }
}