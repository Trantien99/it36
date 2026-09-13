<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChatbotFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('keywords')->nullable();
            $table->text('answer');
            $table->string('link_text')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        $now = Carbon::now();

        DB::table('chatbot_faqs')->insert([
            [
                'question' => 'Giao hang',
                'keywords' => 'giao hang,ship,van chuyen,phi ship,freeship',
                'answer' => 'Shop ho tro giao hang toan quoc. Ban co the xem san pham trong he thong va lien he neu can tu van them.',
                'link_text' => 'Xem san pham',
                'link_url' => '/product-grids',
                'priority' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'Doi tra',
                'keywords' => 'doi tra,hoan tra,bao hanh,chinh sach doi tra',
                'answer' => 'Shop co ho tro doi tra. Neu ban can xac nhan dieu kien cu the cho don hang cua minh, hay mo trang lien he de duoc ho tro nhanh.',
                'link_text' => 'Trang lien he',
                'link_url' => '/contact',
                'priority' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'Lien he',
                'keywords' => 'lien he,so dien thoai,email,dia chi,ho tro',
                'answer' => 'Chatbot se lay thong tin lien he truc tiep tu bang settings trong CSDL va hien thi cho ban ngay trong khung chat.',
                'link_text' => 'Mo lien he',
                'link_url' => '/contact',
                'priority' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'Theo doi don hang',
                'keywords' => 'theo doi don hang,kiem tra don,track order,ma don hang',
                'answer' => 'Ban co the mo trang tra cuu don hang de kiem tra tinh trang don. Neu can, shop se ho tro them qua trang lien he.',
                'link_text' => 'Tra cuu don hang',
                'link_url' => '/product/track',
                'priority' => 4,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbot_faqs');
    }
}
