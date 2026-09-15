<?php

namespace Tests\Feature;

use App\Models\Coupon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->post('/chatbot/reply', [
            'message' => 'hello',
        ]);

        $response->assertStatus(200);
    }

    public function testInactiveCouponCannotBeApplied()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('type');
            $table->decimal('value', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Coupon::query()->create([
            'code' => 'SAVE10',
            'type' => 'percent',
            'value' => 10,
            'status' => 'inactive',
        ]);

        $response = $this->withSession([])->post(route('coupon-store'), [
            'code' => 'SAVE10',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertNull(session('coupon'));
    }
}
