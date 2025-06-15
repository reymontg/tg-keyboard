<?php declare(strict_types=1);

namespace Reymon\Keyboard\Test\Keyboards;

use Reymon\Keyboard\Test\Buttons\KeyboardButtonTest;
use Reymon\Keyboard\KeyboardForceReply;

class KeyboardForceReplyTest extends KeyboardButtonTest
{
    public function testForceReply(): void
    {
        $keyboard = KeyboardForceReply::new();
        $rawKeyboard = [
            'force_reply' => true,
            'selective'   => false,
        ];
        $this->assertJsonStringEqualsJsonString(\json_encode($keyboard), \json_encode($rawKeyboard));
    }
}
