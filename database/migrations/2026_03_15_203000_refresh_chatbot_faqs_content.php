<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefreshChatbotFaqsContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('chatbot_faqs')) {
            return;
        }

        $now = Carbon::now();

        $faqs = [
            [
                'priority' => 1,
                'question' => 'Giao hàng',
                'keywords' => 'giao hàng,ship,vận chuyển,phí ship,freeship,thời gian giao hàng',
                'answer' => 'Shop hỗ trợ giao hàng toàn quốc. Tùy khu vực và sản phẩm, thời gian giao có thể khác nhau. Nếu bạn đã chọn được mẫu phù hợp, mình có thể dẫn bạn đến trang sản phẩm để xem chi tiết trước khi đặt.',
                'link_text' => 'Xem sản phẩm',
                'link_url' => '/product-grids',
            ],
            [
                'priority' => 2,
                'question' => 'Đổi trả',
                'keywords' => 'đổi trả,hoàn trả,bảo hành,chính sách đổi trả,sản phẩm lỗi',
                'answer' => 'Shop có hỗ trợ đổi trả theo tình trạng đơn hàng và sản phẩm. Nếu bạn cần kiểm tra trường hợp cụ thể như lỗi kỹ thuật, đổi mẫu hoặc xác nhận điều kiện áp dụng, hãy liên hệ trực tiếp để được hỗ trợ nhanh nhất.',
                'link_text' => 'Trang liên hệ',
                'link_url' => '/contact',
            ],
            [
                'priority' => 3,
                'question' => 'Liên hệ',
                'keywords' => 'liên hệ,số điện thoại,email,địa chỉ,hỗ trợ,tư vấn',
                'answer' => 'Mình có thể gửi ngay số điện thoại, email và địa chỉ của shop trong khung chat này. Nếu bạn muốn trao đổi kỹ hơn về sản phẩm hoặc đơn hàng, bạn cũng có thể mở trang liên hệ.',
                'link_text' => 'Mở liên hệ',
                'link_url' => '/contact',
            ],
            [
                'priority' => 4,
                'question' => 'Theo dõi đơn hàng',
                'keywords' => 'theo dõi đơn hàng,kiểm tra đơn,track order,mã đơn hàng,trạng thái đơn',
                'answer' => 'Bạn có thể mở trang tra cứu đơn hàng để kiểm tra trạng thái xử lý và giao hàng. Nếu gặp khó khăn khi tra cứu, shop vẫn có thể hỗ trợ thêm qua trang liên hệ.',
                'link_text' => 'Tra cứu đơn hàng',
                'link_url' => '/product/track',
            ],
            [
                'priority' => 5,
                'question' => 'Thanh toán',
                'keywords' => 'thanh toán,payment,trả tiền,phương thức thanh toán,cách thanh toán',
                'answer' => 'Shop hỗ trợ đặt hàng trực tuyến theo quy trình có sẵn trên website. Nếu bạn đang phân vân trước bước thanh toán, mình có thể giúp bạn xem lại sản phẩm, giỏ hàng hoặc hướng dẫn sang trang liên hệ để được tư vấn thêm.',
                'link_text' => 'Xem giỏ hàng',
                'link_url' => '/cart',
            ],
        ];

        foreach ($faqs as $faq) {
            DB::table('chatbot_faqs')->updateOrInsert(
                ['priority' => $faq['priority']],
                array_merge($faq, [
                    'status' => 'active',
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('chatbot_faqs')) {
            return;
        }

        DB::table('chatbot_faqs')
            ->whereIn('priority', [1, 2, 3, 4, 5])
            ->delete();
    }
}
