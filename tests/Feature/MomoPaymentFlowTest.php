<?php

namespace Tests\Feature;

use App\Http\Controllers\MomoController;
use Tests\TestCase;

class MomoPaymentFlowTest extends TestCase
{
    public function testMomoPaymentResolvesQrCodeUrlWhenPayUrlIsMissing()
    {
        $controller = new MomoController();

        $this->assertSame(
            'https://example.com/qr-code.png',
            $controller->resolvePaymentRedirectUrl([
                'resultCode' => 0,
                'qrCodeUrl' => 'https://example.com/qr-code.png',
            ])
        );
    }
}
