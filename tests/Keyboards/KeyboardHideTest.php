<?php declare(strict_types=1);

namespace Reymon\Keyboard\Test\Keyboards;

use PHPUnit\Framework\TestCase;
use Reymon\Keyboard\KeyboardHide;

class KeyboardHideTest extends TestCase
{
    public function testHide(): void
    {
        $button = KeyboardHide::new();
        $rawButton = [
            'remove_keyboard' => true,
            'selective'       => false,
        ];
        $this->assertJsonStringEqualsJsonString(\json_encode($button), \json_encode($rawButton));
    }
}
