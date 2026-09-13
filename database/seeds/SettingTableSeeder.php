<?php

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
            'description'=>"Web bán tai nghe chuyên cung cấp các dòng tai nghe bluetooth, tai nghe gaming, tai nghe chụp tai và phụ kiện âm thanh chính hãng. Chúng tôi tập trung vào trải nghiệm nghe nhạc, làm việc và giải trí với sản phẩm chất lượng, bảo hành rõ ràng và giao hàng nhanh.",
            'short_des'=>"Chuyên tai nghe chính hãng, mẫu mã hiện đại, âm thanh tốt, giá hợp lý và hỗ trợ tư vấn nhanh.",
            'photo'=>"image.jpg",
            'logo'=>'logo.jpg',
            'address'=>"NO. 342 - London Oxford Street, 012 United Kingdom",
            'email'=>"taingheshop@gmail.com",
            'phone'=>"+060 (800) 801-582",
        );
        DB::table('settings')->insert($data);
    }
}
