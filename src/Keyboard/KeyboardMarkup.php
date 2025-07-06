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

use Reymon\Type\Button\KeyboardButton;
use Reymon\Type\Button\PollType;
use Reymon\Type\Chat\AdministratorRights;
use Reymon\Type\Keyboard;
use Reymon\Type\Utils\Placeholder;
use Reymon\Type\Utils\Selective;
use Reymon\Type\Utils\SingleUse;

/**
 * Represents a custom keyboard with reply options.
 *
 * @template TButton of KeyboardButton
 */
final class KeyboardMarkup extends Keyboard
{
    use Selective, Placeholder, SingleUse;

    private bool $resize = true;
    private bool $alwaysShow = false;

    /**
     * @param bool    $resize      Whether to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     * @param bool    $singleUse   Whether to hide the keyboard as soon as it's been used. The keyboard will still be available, but clients will automatically display the usual letter-keyboard in the chat - the user can press a special button in the input field to see the custom keyboard again. Defaults to false.
     * @param bool    $alwaysShow  Whether to always show the keyboard when the regular keyboard is hidden. Defaults to false, in which case the custom keyboard can be hidden and opened with a keyboard icon.
     * @param bool    $selective   Whether to show the keyboard to specific users only. Targets: 1- users that are @mentioned in the text of the [Message](https://core.telegram.org/bots/api#message) object 2- if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.
     * @param ?string $placeholder The placeholder to be shown in the input field when the keyboard is active; 1-64 characters.
     */
    public static function new(bool $resize = true, bool $singleUse = true, bool $alwaysShow = true, bool $selective = false, ?string $placeholder = null): self
    {
        return (new self)
            ->resize($resize)
            ->singleUse($singleUse)
            ->selective($selective)
            ->alwaysShow($alwaysShow)
            ->placeholder($placeholder);
    }

    /**
     * Whether to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     */
    public function resize(bool $resize = true): self
    {
        $this->resize = $resize;
        return $this;
    }

    /**
     * Whether to always show the keyboard when the regular keyboard is hidden. Defaults to false, in which case the custom keyboard can be hidden and opened with a keyboard icon.
     */
    public function alwaysShow(bool $alwaysShow = true): self
    {
        $this->alwaysShow = $alwaysShow;
        return $this;
    }

    /**
     * Create simple text keyboard.
     *
     * @param string $text Label text on the button
     */
    public function addText(string $text): self
    {
        return $this->addButton(KeyboardButton::Text($text));
    }

    /**
     * Create simple texts keyboard.
     *
     * @param array ...$keyboards
     */
    public function addTexts(... $keyboards): self
    {
        \array_walk($keyboards, function ($row) {
            \array_map($this->addText(...), \array_keys($row), $row);
            $this->row();
        });
        return $this;
    }

    /**
     * Create text button that open web app without requiring user information.
     *
     * @param string $text Label text on the button
     * @param string $url  An HTTPS URL of a Web App to be opened with additional data as specified in [Initializing Web Apps](https://core.telegram.org/bots/webapps#initializing-mini-apps)
     */
    public function addWebApp(string $text, string $url): self
    {
        return $this->addButton(KeyboardButton::WebApp($text, $url));
    }

    /**
     * Create text button that request poll from user.
     *
     * @param string   $text Label text on the button
     */
    public function requestPoll(string $text, PollType $type = PollType::ALL): self
    {
        return $this->addButton(KeyboardButton::Poll($text, $type));
    }

    /**
     * Create text button that request poll quiz from user.
     *
     * @param string   $text Label text on the button
     */
    public function requestPollQuiz(string $text): self
    {
        return $this->addButton(KeyboardButton::Poll($text, PollType::QUIZ));
    }

    /**
     * Create text button that request poll regular from user.
     *
     * @param string   $text Label text on the button
     */
    public function requestPollRegular(string $text): self
    {
        return $this->addButton(KeyboardButton::Poll($text, PollType::REGULAR));
    }

    /**
     * Create text button that request location from user.
     *
     * @param string $text Label text on the button
     */
    public function requestLocation(string $text): self
    {
        return $this->addButton(KeyboardButton::Location($text));
    }

    /**
     * Create text button that request contact info from user.
     *
     * @param string $text Label text on the button
     */
    public function requestPhone(string $text): self
    {
        return $this->addButton(KeyboardButton::Phone($text));
    }

    /**
     * Create a request peer user button.
     *
     * @param string $text     Label text on the button
     * @param int    $buttonId Signed 32-bit identifier of the request
     * @param ?bool  $bot      Whether to request bots or users, If not specified, no additional restrictions are applied.
     * @param ?bool  $premium  Whether to request premium or non-premium users. If not specified, no additional restrictions are applied.
     * @param bool   $name     Whether to request the users' first and last name
     * @param bool   $username Whether to request the users' username
     * @param bool   $photo    Whether to request the users' photo
     * @param int    $max      The maximum number of users to be selected; 1-10.
     */
    public function requestUsers(string $text, int $buttonId, ?bool $bot = null, ?bool $premium = null, bool $name = false, bool $username = false, bool $photo = false, int $max = 1): self
    {
        return $this->addButton(KeyboardButton::RequestUsers($text, $buttonId, $bot, $premium, $name, $username, $photo, $max));
    }

    /**
     * Create a request group button.
     *
     * @param string               $text            Label text on the button
     * @param int                  $buttonId        Signed 32-bit identifier of the request
     * @param ?bool                $creator         Whether to request a chat owned by the user.
     * @param ?bool                $hasUsername     Whether to request a supergroup or a channel with (or without) a username. If not specified, no additional restrictions are applied.
     * @param ?bool                $forum           Whether to request a forum (or non-forum) supergroup.
     * @param ?bool                $member          Whether to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
     * @param bool                 $name            Whether to request the chat's title
     * @param bool                 $username        Whether to request the chat's username
     * @param bool                 $photo           Whether to request the chat's photo
     * @param ?AdministratorRights $userAdminRights The required administrator rights of the user in the chat. If not specified, no additional restrictions are applied.
     * @param ?AdministratorRights $botAdminRights  The required administrator rights of the bot in the chat. If not specified, no additional restrictions are applied.
     */
    public function requestGroup(string $text, int $buttonId, ?bool $creator = null, ?bool $hasUsername = null, ?bool $forum = null, ?bool $member = null, bool $name = false, bool $username = false, bool $photo = false, ?AdministratorRights $userAdminRights = null, ?AdministratorRights $botAdminRights = null): self
    {
        return $this->addButton(KeyboardButton::RequestGroup($text, $buttonId, $creator, $hasUsername, $forum, $member, $name, $username, $photo, $userAdminRights, $botAdminRights));
    }

    /**
     * Create a request channel button.
     *
     * @param string               $text            Label text on the button
     * @param int                  $buttonId        Signed 32-bit identifier of the request
     * @param ?bool                $creator         Whether to request a chat owned by the user.
     * @param ?bool                $hasUsername     Whether to request a supergroup or a channel with (or without) a username. If not specified, no additional restrictions are applied.
     * @param ?bool                $member          Whether to request a chat with the bot as a member. Otherwise, no additional restrictions are applied.
     * @param bool                 $name            Whether to request the chat's title
     * @param bool                 $username        Whether to request the chat's username
     * @param bool                 $photo           Whether to request the chat's photo
     * @param ?AdministratorRights $userAdminRights The required administrator rights of the user in the chat. If not specified, no additional restrictions are applied.
     * @param ?AdministratorRights $botAdminRights  The required administrator rights of the bot in the chat. If not specified, no additional restrictions are applied.
     */
    public function requestChannel(string $text, int $buttonId, ?bool $creator = null, ?bool $hasUsername = null, ?bool $member = null, bool $name = false, bool $username = false, bool $photo = false, ?AdministratorRights $userAdminRights = null, ?AdministratorRights $botAdminRights = null): self
    {
        return $this->addButton(KeyboardButton::RequestChannel($text, $buttonId, $creator, $hasUsername, $member, $name, $username, $photo, $botAdminRights, $userAdminRights));
    }

    #[\Override]
    public function toApi(): array
    {
        return array_filter_null([
            'keyboard'          => parent::toApi(),
            'is_persistent'     => $this->alwaysShow,
            'resize_keyboard'   => $this->resize,
            'one_time_keyboard' => $this->singleUse,
        ]);
    }

    #[\Override]
    public function toMtproto(): array
    {
        return array_filter_null([
            '_' => 'replyKeyboardMarkup',
            'rows'        => parent::toMtproto(),
            'resize'      => $this->resize,
            'single_use'  => $this->singleUse,
            'selective'   => $this->selective,
            'persistent'  => $this->alwaysShow,
            'placeholder' => $this->placeholder,
        ]);
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }
}
