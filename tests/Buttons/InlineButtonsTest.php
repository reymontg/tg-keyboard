<?php declare(strict_types=1);

namespace Reymon\Type\Keyboard\Test\Buttons;

use PHPUnit\Framework\TestCase;
use Reymon\Type\Button\InlineButton;

class InlineButtonsTest extends TestCase
{
    public function testBuy(): void
    {
        $button = InlineButton::Buy('hello');
        $rawButton = [
            'text' => 'hello',
            'pay' => true,
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button->toApi(), $rawButton, []);
    }

    public function testGame(): void
    {
        $button = InlineButton::Game('hello');
        $rawButton = [
            'text' => 'hello',
            'callback_game' => '',
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button->toApi(), $rawButton, []);
    }

    public function testUrl(): void
    {
        $button = InlineButton::Url('hello', 'https://example.com');
        $rawButton = [
            'text' => 'hello',
            'url' => 'https://example.com',
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button->toApi(), $rawButton, []);
    }

    public function testCallbackData(): void
    {
        $button = InlineButton::Callback('hello', 'hello-callback');
        $rawButton = [
            'text' => 'hello',
            'callback_data' => 'hello-callback',
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button->toApi(), $rawButton, []);
    }

    public function testCopyText(): void
    {
        $button = InlineButton::CopyText('hello', 'hello-copy');
        $rawButton = [
            'text' => 'hello',
            'copy_text' => ['text' => 'hello-copy'],
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button->toApi(), $rawButton, []);
    }

    public function testLoginUrl(): void
    {
        $button1 = InlineButton::LoginUrl('please-login', 'https://example.com');
        $button2 = InlineButton::LoginUrl('please-login', 'https://example.com', 'bye');
        $button3 = InlineButton::LoginUrl('please-login', 'https://example.com', 'bye', 'mahdi', true);
        $rawButton1 = [
            'login_url' => [
                'url' => 'https://example.com',
                'request_write_access' => false,
            ],
            'text' => 'please-login',
        ];
        $rawButton2 = [
            'login_url' => [
                'url' => 'https://example.com',
                'forward_text' => 'bye',
                'request_write_access' => false,
            ],
            'text' => 'please-login',
        ];
        $rawButton3 = [
            'login_url' => [
                'url' => 'https://example.com',
                'forward_text' => 'bye',
                'bot_username' => 'mahdi',
                'request_write_access' => true,
            ],
            'text' => 'please-login',
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button1->toApi(), $rawButton1, []);
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button2->toApi(), $rawButton2, []);
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button3->toApi(), $rawButton3, []);
    }

    public function testSwitchInline(): void
    {
        $button1 = InlineButton::SwitchInline('hello', 'test');
        $button2 = InlineButton::SwitchInlineCurrent('hello', 'test');
        $button3 = InlineButton::SwitchInlineFilter('hello', 'test');
        $rawButton1 = [
            'switch_inline_query' => 'test',
            'text' => 'hello'
        ];
        $rawButton2 = [
            'switch_inline_query_current_chat' => 'test',
            'text' => 'hello'
        ];
        $rawButton3 = [
            'switch_inline_query_chosen_chat' => ['allow_user_chats' => true, 'query' => 'test'],
            'text' => 'hello'
        ];
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button1->toApi(), $rawButton1, []);
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button2->toApi(), $rawButton2, []);
        $this->assertArrayIsEqualToArrayIgnoringListOfKeys($button3->toApi(), $rawButton3, []);
    }
}
