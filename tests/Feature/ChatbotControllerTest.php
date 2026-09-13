<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ChatbotControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestingTables();
        $this->seedCatalog();
    }

    public function test_chatbot_prompts_for_more_filters_when_request_is_too_broad()
    {
        $response = $this->post('/chatbot/reply', [
            'message' => 'tu van tai nghe',
        ]);

        $response->assertStatus(200);

        $payload = $response->decodeResponseJson();

        $this->assertStringContainsString('1 đến 2 tiêu chí', $payload['html']);
        $this->assertSame('Dưới 1 triệu', $payload['actions'][0]['label']);
    }

    public function test_chatbot_filters_products_by_use_case_and_budget()
    {
        $response = $this->post('/chatbot/reply', [
            'message' => 'tai nghe choi game duoi 1 trieu',
        ]);

        $response->assertStatus(200);

        $payload = $response->decodeResponseJson();

        $this->assertStringContainsString('JBL Quantum 100M2', $payload['html']);
        $this->assertStringNotContainsString('Sony WH-CH720N', $payload['html']);
    }

    public function test_chatbot_uses_session_context_for_follow_up_budget_filter()
    {
        $firstResponse = $this->post('/chatbot/reply', [
            'message' => 'Sony',
        ]);
        $firstResponse->assertStatus(200);

        $context = app('session.store')->get('chatbot.context');

        $this->assertNotEmpty($context);
        $this->assertSame(['Sony'], $context['filters']['brand_labels']);

        $secondResponse = $this->withSession([
            'chatbot.context' => $context,
        ])->post('/chatbot/reply', [
            'message' => 'duoi 2 trieu',
        ]);

        $secondResponse->assertStatus(200);

        $payload = $secondResponse->decodeResponseJson();

        $this->assertStringContainsString('Bộ DAC USB-C và hộp đựng tai nghe cao cấp', $payload['html']);
        $this->assertStringNotContainsString('Sony WH-CH720N', $payload['html']);
    }

    public function test_chatbot_prefers_faq_when_support_topic_starts_a_new_thread()
    {
        $firstResponse = $this->post('/chatbot/reply', [
            'message' => 'Sony',
        ]);
        $firstResponse->assertStatus(200);

        $context = app('session.store')->get('chatbot.context');

        $response = $this->withSession([
            'chatbot.context' => $context,
        ])->post('/chatbot/reply', [
            'message' => 'bao hanh',
        ]);

        $response->assertStatus(200);

        $payload = $response->decodeResponseJson();

        $this->assertStringContainsString('Shop ho tro bao hanh va doi tra theo chinh sach hien hanh.', $payload['html']);
        $this->assertStringNotContainsString('Sony WH-CH720N', $payload['html']);
    }

    public function test_chatbot_does_not_force_bluetooth_queries_into_true_wireless_only()
    {
        $response = $this->post('/chatbot/reply', [
            'message' => 'tai nghe bluetooth',
        ]);

        $response->assertStatus(200);

        $payload = $response->decodeResponseJson();

        $this->assertStringContainsString('Sony WH-CH720N', $payload['html']);
        $this->assertStringContainsString('SoundPEATS Air4 Lite', $payload['html']);
    }

    protected function createTestingTables()
    {
        Schema::dropIfExists('chatbot_faqs');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');

        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('summary')->nullable();
            $table->boolean('is_parent')->default(1);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->text('photo')->nullable();
            $table->integer('stock')->default(0);
            $table->string('size')->nullable();
            $table->string('condition')->default('default');
            $table->string('status')->default('active');
            $table->float('price');
            $table->float('discount')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('cat_id')->nullable();
            $table->unsignedBigInteger('child_cat_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->timestamps();
        });

        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('question');
            $table->text('keywords')->nullable();
            $table->text('answer');
            $table->string('link_text')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    protected function seedCatalog()
    {
        $now = now();

        DB::table('brands')->insert([
            [
                'id' => 8,
                'title' => 'Sony',
                'slug' => 'sony',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'title' => 'JBL',
                'slug' => 'jbl',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'title' => 'SoundPEATS',
                'slug' => 'soundpeats',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('categories')->insert([
            [
                'id' => 26,
                'title' => 'Tai nghe chụp tai',
                'slug' => 'tai-nghe-chup-tai',
                'summary' => 'Over-ear headphones',
                'is_parent' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 27,
                'title' => 'Tai nghe true wireless',
                'slug' => 'tai-nghe-true-wireless',
                'summary' => 'Bluetooth earbuds',
                'is_parent' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 28,
                'title' => 'Phụ kiện âm thanh',
                'slug' => 'phu-kien-am-thanh',
                'summary' => 'DAC and accessories',
                'is_parent' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('products')->insert([
            [
                'id' => 21,
                'title' => 'Sony WH-CH720N',
                'slug' => 'sony-wh-ch720n',
                'summary' => 'Bluetooth over-ear with active noise cancelling for work.',
                'description' => 'Bluetooth, chống ồn, pin dài và đeo êm cho làm việc.',
                'photo' => 'sony.jpg',
                'stock' => 60,
                'size' => 'M',
                'condition' => 'new',
                'status' => 'active',
                'price' => 3290000,
                'discount' => 7,
                'is_featured' => 1,
                'cat_id' => 26,
                'child_cat_id' => null,
                'brand_id' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 22,
                'title' => 'JBL Quantum 100M2',
                'slug' => 'jbl-quantum-100m2',
                'summary' => 'Gaming headset with mic for entry-level players.',
                'description' => 'Gaming, mic rõ, phù hợp chơi game và học online.',
                'photo' => 'jbl.jpg',
                'stock' => 120,
                'size' => 'M',
                'condition' => 'hot',
                'status' => 'active',
                'price' => 890000,
                'discount' => 5,
                'is_featured' => 1,
                'cat_id' => 26,
                'child_cat_id' => null,
                'brand_id' => 9,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 26,
                'title' => 'SoundPEATS Air4 Lite',
                'slug' => 'soundpeats-air4-lite',
                'summary' => 'Bluetooth true wireless for daily listening.',
                'description' => 'Bluetooth, wireless, thoải mái khi di chuyển.',
                'photo' => 'soundpeats.jpg',
                'stock' => 97,
                'size' => 'M',
                'condition' => 'hot',
                'status' => 'active',
                'price' => 1200000,
                'discount' => 0,
                'is_featured' => 1,
                'cat_id' => 27,
                'child_cat_id' => null,
                'brand_id' => 11,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 27,
                'title' => 'Bộ DAC USB-C và hộp đựng tai nghe cao cấp',
                'slug' => 'bo-dac-usb-c',
                'summary' => 'DAC USB-C accessory for Sony users.',
                'description' => 'Phụ kiện DAC USB-C cho nhu cầu di chuyển và kết nối có dây.',
                'photo' => 'dac.jpg',
                'stock' => 100,
                'size' => 'M',
                'condition' => 'default',
                'status' => 'active',
                'price' => 1880000,
                'discount' => 3,
                'is_featured' => 0,
                'cat_id' => 28,
                'child_cat_id' => null,
                'brand_id' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('chatbot_faqs')->insert([
            [
                'id' => 1,
                'question' => 'Bao hanh va doi tra',
                'keywords' => 'bao hanh,doi tra,hoan tra,chinh sach',
                'answer' => 'Shop ho tro bao hanh va doi tra theo chinh sach hien hanh.',
                'link_text' => 'Trang lien he',
                'link_url' => '/contact',
                'priority' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'question' => 'Giao hang',
                'keywords' => 'giao hang,ship,van chuyen,phi ship',
                'answer' => 'Shop ho tro giao hang toan quoc.',
                'link_text' => 'Xem san pham',
                'link_url' => '/product-grids',
                'priority' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
