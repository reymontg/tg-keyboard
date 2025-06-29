<?php declare(strict_types=1);

namespace Reymon\Type\Keyboard\Test\Keyboards;

use Reymon\Type\Keyboard\KeyboardForceReply;
use Reymon\Type\Keyboard\Test\Buttons\KeyboardButtonTest;

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
