<?php declare(strict_types=1);

/**
 * This file is part of Reymon.
 * Reymon is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * Reymon is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    Mahdi <mahdi.talaee1379@gmail.com>
 * @author    AhJ <AmirHosseinJafari8228@gmail.com>
 * @copyright Copyright (c) 2023, ReymonTg
 * @license   https://choosealicense.com/licenses/gpl-3.0/ GPLv3
 */

namespace Reymon\Type\Keyboard;

use Reymon\Type\Button\InlineButton;
use Reymon\Type\Keyboard;

/**
 * Represents an inline keyboard that appears right next to the message it belongs to.
 *
 * @template TButton of InlineButton
 */
final class KeyboardInline extends Keyboard
{
    public static function new(): self
    {
        return new self;
    }

    /**
     * Create inline button with callback data.
     *
     * @param string $text     Label text on the button
     * @param string $callback Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
     */
    public function addCallback(string $text, string $callback): self
    {
        return $this->addButton(InlineButton::CallBack($text, $callback));
    }

    /**
     * Create inline buttons with callback data.
     */
    public function addCallbacks(... $rows): self
    {
        \array_walk($rows, function ($row) {
            \array_map($this->addCallback(...), \array_keys($row), $row);
            $this->row();
        });
        return $this;
    }

    /**
     * Create inline button that copies specified text to the clipboard.
     *
     * @param string $text     Label text on the button
     * @param string $copyText The text to be copied to the clipboard; 1-256 characters
     */
    public function addCopyText(string $text, string $copyText): self
    {
        return $this->addButton(InlineButton::CopyText($text, $copyText));
    }

    /**
     * Create Inline button with url.
     *
     * @param string $text Label text on the button
     * @param string $url  HTTP or tg:// URL to be opened when the button is pressed. Links `tg://user?id=<user_id>` can be used to mention a user by their ID without using a username, if this is allowed by their privacy settings.
     */
    public function addUrl(string $text, string $url): self
    {
        return $this->addButton(InlineButton::Url($text, $url));
    }

    /**
     * Create Inline buttons with url.
     */
    public function addUrls(... $rows): self
    {
        \array_walk($rows, function ($row) {
            \array_map($this->addUrl(...), \array_keys($row), $row);
            $this->row();
        });
        return $this;
    }

    /**
     * Create Inline webapp button.
     *
     * @param string $text Label text on the button
     * @param string $url  An HTTPS URL of a Web App to be opened with additional data as specified in [Initializing Web Apps](https://core.telegram.org/bots/webapps#initializing-mini-apps)
     */
    public function addWebApp(string $text, string $url): self
    {
        return $this->addButton(InlineButton::WebApp($text, $url));
    }

    /**
     * Create inline button for login.
     *
     * @param string  $text        Label text on the button
     * @param string  $url         An HTTPS URL used to automatically authorize the user
     * @param ?string $forwardText New text of the button in forwarded messages
     * @param ?string $username    Username of a bot, which will be used for user authorization.
     * @param bool    $writeAccess Whether to request the permission for your bot to send messages to the user
     */
    public function addLoginUrl(string $text, string $url, ?string $forwardText = null, ?string $username = null, bool $writeAccess = false): self
    {
        return $this->addButton(InlineButton::LoginUrl($text, $url, $forwardText, $username, $writeAccess));
    }

    /**
     * Create game button for your inline game.
     *
     * @param string $text Label text on the button
     */
    public function addGame(string $text): self
    {
        return $this->addButton(InlineButton::Game($text));
    }

    /**
     * Create a buy button for your inline buy request(similar to webapps).
     *
     * @param string $text Label text on the button
     */
    public function addBuy(string $text): self
    {
        return $this->addButton(InlineButton::Buy($text));
    }

    /**
     * Create inline button that switches the current user to inline mode in a chat.
     *
     * @param string $text  Label text on the button
     * @param string $query Data to be sent in a [callback query](https://core.telegram.org/bots/api#callbackquery) to the bot when button is pressed, 1-64 bytes
     */
    public function addSwitchInline(string $text, string $query): self
    {
        return $this->addButton(InlineButton::SwitchInline($text, $query));
    }

    /**
     * Create inline buttons that switches the current user to inline mode in a chat.
     */
    public function addSwitchInlines(... $rows): self
    {
        \array_walk($rows, function ($row) {
            \array_map($this->addSwitchInline(...), \array_keys($row), $row);
            $this->row();
        });
        return $this;
    }

    /**
     * Create inline button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted.
     *
     * @param string $text  Label text on the button
     * @param string $query Data to be sent in a [callback query](https://core.telegram.org/bots/api#callbackquery) to the bot when button is pressed, 1-64 bytes
     */
    public function addSwitchInlineCurrent(string $text, string $query): self
    {
        return $this->addButton(InlineButton::SwitchInlineCurrent($text, $query));
    }

    public function addSwitchInlinesCurrent(... $rows): self
    {
        \array_walk($rows, function ($row) {
            \array_map($this->addSwitchInlineCurrent(...), \array_keys($row), $row);
            $this->row();
        });
        return $this;
    }

    /**
     * Create an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
     *
     * @param string    $text          Label text on the button
     * @param string    $query         The default inline query to be inserted in the input field. If left empty, only the bot's username will be inserted
     * @param bool      $allowUsers    Whether private chats with users can be chosen
     * @param bool|null $allowBots     Whether private chats with bots can be chosen
     * @param bool|null $allowGroups   Whether group and supergroup chats can be chosen
     * @param bool|null $allowChannels Whether channel chats can be chosen
     */
    public function addSwitchInlineFilter(string $text, string $query = '', bool $allowUsers = true, ?bool $allowBots = null, ?bool $allowGroups = null, ?bool $allowChannels = null): self
    {
        return $this->addButton(InlineButton::SwitchInlineFilter($text, $query, $allowUsers, $allowBots, $allowGroups, $allowChannels));
    }

    #[\Override]
    public function toApi(): array
    {
        return ['inline_keyboard' => parent::toApi()];
    }

    #[\Override]
    public function toMtproto(): array
    {
        return [
            '_' => 'replyInlineMarkup',
            'rows' => parent::toMtproto(),
        ];
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }
}
