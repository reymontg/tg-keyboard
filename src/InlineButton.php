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

namespace Reymon;

use Reymon\InlineButton\Buy;
use Reymon\InlineButton\CallBack;
use Reymon\InlineButton\CopyText;
use Reymon\InlineButton\Game;
use Reymon\InlineButton\LoginUrl;
use Reymon\InlineButton\SwitchInline;
use Reymon\InlineButton\SwitchInlineCurrent;
use Reymon\InlineButton\SwitchInlineFilter;
use Reymon\InlineButton\Webapp;
use Reymon\InlineButton\Url;

abstract class InlineButton extends Button
{
    /**
     * Create inline button with callback data.
     *
     * @param string $text     Label text on the button
     * @param string $callback Data to be sent in a callback query to the bot when button is pressed, 1-64 bytes
     */
    public static function CallBack(string $text, string $callback): CallBack
    {
        return new CallBack($text, $callback);
    }

    /**
     * Create inline button that copies specified text to the clipboard.
     *
     * @param string $text     Label text on the button
     * @param string $copyText The text to be copied to the clipboard; 1-256 characters
     */
    public static function CopyText(string $text, string $copyText): CopyText
    {
        return new CopyText($text, $copyText);
    }

    /**
     * Create Inline button with url.
     *
     * @param string $text Label text on the button
     * @param string $url  HTTP or tg:// URL to be opened when the button is pressed. Links `tg://user?id=<user_id>` can be used to mention a user by their ID without using a username, if this is allowed by their privacy settings.
     */
    public static function Url(string $text, string $url): Url
    {
        return new Url($text, $url);
    }

    /**
     * Create Inline webapp button.
     *
     * @param string $text Label text on the button
     * @param string $url  An HTTPS URL of a Web App to be opened with additional data as specified in [Initializing Web Apps](https://core.telegram.org/bots/webapps#initializing-mini-apps)
     */
    public static function Webapp(string $text, string $url): Webapp
    {
        return new Webapp($text, $url);
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
    public static function Login(string $text, string $url, ?string $forwardText = null, ?string $username = null, bool $writeAccess = false): LoginUrl
    {
        return new LoginUrl($text, $url, $forwardText, $username, $writeAccess);
    }

    /**
     * Create game button for your inline game.
     *
     * @param string $text Label text on the button
     */
    public static function Game(string $text): Game
    {
        return new Game($text);
    }

    /**
     * Create a buy button for your inline buy request(similar to webapps).
     *
     * @param string $text Label text on the button
     */
    public static function Buy(string $text): Buy
    {
        return new Buy($text);
    }
    /**
     * Create inline button that switches the current user to inline mode in a chat.
     *
     * @param string $text  Label text on the button
     * @param string $query Data to be sent in a [callback query](https://core.telegram.org/bots/api#callbackquery) to the bot when button is pressed, 1-64 bytes
     */
    public static function SwitchInline(string $text, string $query): SwitchInline
    {
        return new SwitchInline($text, $query);
    }

    /**
     * Create inline button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted.
     *
     * @param string $text  Label text on the button
     * @param string $query Data to be sent in a [callback query](https://core.telegram.org/bots/api#callbackquery) to the bot when button is pressed, 1-64 bytes
     */
    public static function SwitchInlineCurrent(string $text, string $query): SwitchInlineCurrent
    {
        return new SwitchInlineCurrent($text, $query);
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
    public static function SwitchInlineFilter(string $text, string $query = '', bool $allowUsers = true, ?bool $allowBots = null, ?bool $allowGroups = null, ?bool $allowChannels = null): SwitchInlineFilter
    {
        return new SwitchInlineFilter($text, $query, $allowUsers, $allowBots, $allowGroups, $allowChannels);
    }
}
