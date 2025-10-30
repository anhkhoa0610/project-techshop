<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Faker\Factory;

class ReviewSeeder extends Seeder
{
    /* Run the database seeds.*/
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->count() === 0 || $users->count() === 0) {
            $this->command->info('Không có sản phẩm hoặc người dùng để tạo review.');
            return;
        }

        $faker = Factory::create('vi_VN');

        foreach ($products as $index => $product) {

            $user = $users[$index % $users->count()];

            Review::create([
                'product_id' => $product->product_id,
                'user_id' => $user->user_id,
                'rating' => $faker->numberBetween(1, 5),
                'comment' => $faker->randomElement([
                    'Mình dùng được gần 2 tuần rồi, phải công nhận chất lượng build rất tốt,
                     cầm chắc tay, không ọp ẹp gì. Hiệu năng thì khỏi bàn, mở app nhanh như 
                     chớp, đa nhiệm mượt mà không hề giật. Màn hình hiển thị đẹp,
                      màu sắc tươi tắn, xem phim hay lướt web đều đã mắt. Pin dùng cả ngày dài 
                      vẫn thoải mái, tối về cắm sạc 30 phút là đầy. Giá hơi cao nhưng xứng đáng 
                      với trải nghiệm, ai cần máy bền bỉ lâu dài thì nên cân nhắc nhé! ',


                    'Sản phẩm này đúng kiểu “tiền nào của nấy”, từ hộp đựng đến phụ kiện 
                    đi kèm đều chỉn chu. Mình thích nhất là thiết kế tối giản, dễ phối đồ, 
                    mang đi đâu cũng tiện. Tính năng thông minh, kết nối nhanh, ít khi phải 
                    reset lại. Âm thanh trong trẻo, bass chắc, nghe nhạc EDM hay podcast đều ổn.
                     Độ hoàn thiện cao, không thấy lỗi vặt vãnh. Điểm trừ duy nhất là hướng dẫn 
                     sử dụng hơi ngắn, newbie đọc hơi khó hiểu. Nói chung recommend 9/10! ',

                    'Lên đời từ đời cũ sang cái này thấy khác biệt rõ rệt luôn! 
                    Tốc độ xử lý nhanh hơn hẳn, không còn tình trạng đứng hình
                    khi mở nhiều tab. Camera chụp đẹp tự nhiên, không bị rỗ hay lố màu. 
                    Phần mềm cập nhật thường xuyên, fix lỗi nhanh. Dùng ngoài trời nắng gắt
                    vẫn thấy rõ màn hình, chống chói tốt. Pin trâu, chơi game 2-3 tiếng 
                    chỉ tụt 20-25%. Cảm giác cầm nắm thoải mái, không mỏi tay.
                    Tổng thể hài lòng, sẽ ủng hộ hãng tiếp! ',

                    'Máy này đúng chuẩn “hàng ngon trong tầm giá”, không cần chi 
                    quá nhiều mà vẫn có trải nghiệm cao cấp. Giao diện trực quan,
                     ai mới dùng cũng làm quen nhanh. Tản nhiệt ổn, chơi game nặng 
                     1 tiếng chỉ ấm nhẹ. Loa ngoài to rõ, xem phim không cần tai nghe 
                     vẫn đủ chất. Kết nối Wi-Fi 6 bắt sóng xa, tải file nặng chỉ mất 
                     vài giây. Phụ kiện tặng kèm đầy đủ, không phải mua thêm. Nhược 
                     điểm nhỏ là sạc hơi lâu, nhưng bù lại pin bền. 5 sao! ',

                    'Từ ngày có em nó, công việc lẫn giải trí lên level luôn!
                     Đa nhiệm mượt, chuyển app không reload, tiết kiệm thời gian kinh khủng.
                    Màn hình lớn, độ phân giải cao, làm việc với bảng tính hay chỉnh ảnh đều nét.
                    Thiết kế sang trọng, để bàn làm việc nhìn chuyên nghiệp hẳn. Tích hợp AI 
                    thông minh, gợi ý nhanh, học thói quen người dùng tốt. Pin dùng 2 ngày mới 
                    phải sạc nếu chỉ lướt web + mail. Giá trị vượt mong đợi, đáng đầu tư dài hạn! ',

                    'Mới “rinh” về hôm qua mà đã mê mẩn luôn! Thiết kế mỏng nhẹ, bỏ balo không chiếm chỗ, 
                    màu sắc sang chảnh nhìn là thích. Hiệu năng đỉnh cao, mở phần mềm nặng chỉ 2-3 
                    giây là vào. Màn hình độ sáng cao, ra nắng vẫn đọc được tin nhắn rõ ràng. 
                    Loa stereo âm lượng lớn, bass ấm, nghe Zoom hay Spotify đều phê. 
                    Pin dùng liên tục 7-8 tiếng vẫn còn 40%, sạc nhanh 30 phút được 60%. 
                    Dịch vụ bảo hành nhanh gọn, yên tâm xài lâu dài. 10/10 không bàn cãi!',

                    'Sản phẩm này đúng kiểu “im lặng là vàng” – không ồn ào quảng cáo nhưng 
                    dùng rồi mới thấy chất. Tốc độ phản hồi nhanh nhạy, vuốt chạm mượt như lụa.
                    Chất liệu cao cấp, chống bám vân tay tốt, lau nhẹ là sạch. Tính năng bảo mật xịn,
                    mở khóa khuôn mặt hay vân tay chỉ 0.5 giây. Phần mềm tối ưu, không ăn 
                    pin vô ích. Dùng để học online, ghi chú, xem tài liệu đều tiện. Nhược điểm duy nhất 
                    là hộp không có ốp lưng tặng, phải mua thêm. Nói chung đáng tiền!',

                    'Mình vốn kén chọn đồ công nghệ nhưng cái này làm mình ưng hết nấc! 
                    Giao diện thân thiện, bố cục khoa học, mới dùng 5 phút đã quen. Kết nối ổn định,
                    không rớt sóng dù ở tầng hầm. Camera cho ảnh chi tiết cao, chụp thiếu sáng vẫn nét.
                    Độ bền thì khỏi chê, làm rơi nhẹ vài lần vẫn nguyên vẹn. Cập nhật phần mềm đều đặn, 
                    thêm tính năng mới liên tục. Pin trâu bò, dùng hỗn hợp 10 tiếng vẫn còn dư.
                    Giá sale hợp lý, không hối hận khi xuống tiền. Sẽ giới thiệu bạn bè!',

                    'Cầm trên tay thấy “đã” ngay từ phút đầu: trọng lượng vừa phải, không nặng cổ tay. 
                    Màn hình cong nhẹ ôm mắt, xem phim dài không mỏi. Chip xử lý mạnh mẽ, render file 4K 
                    chỉ mất 1/3 thời gian so với máy cũ. Tản nhiệt hiệu quả, chơi game 2 tiếng 
                    máy chỉ ấm ấm. Loa ngoài cân bằng, treble sáng, không rè. Tích hợp trợ lý ảo thông minh, 
                    ra lệnh tiếng Việt chuẩn. Sạc không dây tiện lợi, đặt lên là đầy.
                    Điểm cộng lớn là chính sách đổi trả 30 ngày, mua yên tâm. 5 sao!',

                    'Từ ngày có “em nó” thì năng suất tăng vọt luôn! Đa nhiệm mượt mà, 
                    mở 20 tab Chrome + Photoshop vẫn không ngáp. Màu sắc hiển thị chuẩn, 
                    calibrating sẵn cho dân thiết kế. Bàn phím gõ êm, hành trình phím vừa tay, 
                    code đêm không ồn. Touchpad rộng, cử chỉ đa điểm nhạy bén. Pin thực tế 9-10 
                    tiếng làm việc văn phòng, cuối tuần còn dư chơi game nhẹ. Thiết kế tối giản 
                    nhưng tinh tế, để đâu cũng nổi bật. Hỗ trợ kỹ thuật online 24/7, 
                    hỏi là trả lời ngay. Rất hài lòng, sẽ mua thêm tặng gia đình!'

                ]),
                'review_date' => $faker->dateTimeBetween('-1 years', 'now'),
            ]);
        }

        $this->command->info(' Đã tạo review sản phẩm thành công!');
    }
}