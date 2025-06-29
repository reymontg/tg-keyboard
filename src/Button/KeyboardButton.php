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

namespace Reymon\Type\Button;

use Reymon\Type\Button;
use Reymon\Type\Button\KeyboardButton\Location;
use Reymon\Type\Button\KeyboardButton\Phone;
use Reymon\Type\Button\KeyboardButton\Poll;
use Reymon\Type\Button\KeyboardButton\RequestChannel;
use Reymon\Type\Button\KeyboardButton\RequestGroup;
use Reymon\Type\Button\KeyboardButton\RequestUsers;
use Reymon\Type\Button\KeyboardButton\Text;
use Reymon\Type\Button\KeyboardButton\Webapp;
use Reymon\Type\Chat\AdministratorRights;

abstract class KeyboardButton extends Button
{
    /**
     * create simple text keyboard.
     *
     * @param string $text Label text on the button
     */
    public static function Text(string $text): Text
    {
        return new Text($text);
    }

    /**
     * Create text button that open web app without requiring user information.
     *
     * @param string $text Label text on the button
     * @param string $url  An HTTPS URL of a Web App to be opened with additional data as specified in [Initializing Web Apps](https://core.telegram.org/bots/webapps#initializing-mini-apps)
     */
    public static function Webapp(string $text, string $url): Webapp
    {
        return new Webapp($text, $url);
    }

    /**
     * Create text button that request poll from user.
     *
     * @param string   $text Label text on the button
     * @param PollType $type Type of the poll, which is allowed to be created and sent when the corresponding button is pressed.
     */
    public static function Poll(string $text, PollType $type = PollType::ALL): Poll
    {
        return new Poll($text, $type);
    }

    /**
     * Create text button that request location from user.
     *
     * @param string $text Label text on the button
     */
    public static function Location(string $text): Location
    {
        return new Location($text);
    }

    /**
     * Create text button that request contact info from user.
     *
     * @param string $text Label text on the button
     */
    public static function Phone(string $text): Phone
    {
        return new Phone($text);
    }

    /**
     * Create button the criteria used to request suitable users. The identifiers of the selected users will be shared with the bot when the corresponding button is pressed.
     *
     * @param string  $text     Label text on the button
     * @param int     $buttonId Signed 32-bit identifier of the request
     * @param ?bool   $bot      Whether to request bots or users, If not specified, no additional restrictions are applied.
     * @param ?bool   $premium  Whether to request premium or non-premium users. If not specified, no additional restrictions are applied.
     * @param bool    $name     Whether to request the users' first and last name
     * @param bool    $username Whether to request the users' username
     * @param bool    $photo    Whether to request the users' photo
     * @param int     $max      The maximum number of users to be selected; 1-10.
     */
    public static function RequestUsers(string $text, int $buttonId, ?bool $bot = null, ?bool $premium = null, bool $name = false, bool $username = false, bool $photo = false, int $max = 1): KeyboardButton
    {
        return new RequestUsers($text, $buttonId, $bot, $premium, $name, $username, $photo, $max);
    }

    /**
     * Create button the criteria used to request a suitable group/supergroup. The identifier of the selected chat will be shared with the bot when the corresponding button is pressed.
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
    public static function RequestGroup(string $text, int $buttonId, ?bool $creator = null, ?bool $hasUsername = null, ?bool $forum = null, ?bool $member = null, bool     $name = false, bool $username = false, bool $photo = false, ?AdministratorRights $userAdminRights = null, ?AdministratorRights $botAdminRights = null): KeyboardButton
    {
        return new RequestGroup($text, $buttonId, $creator, $hasUsername, $forum, $member, $name, $username, $photo, $userAdminRights, $botAdminRights);
    }

    /**
     * Create button the criteria used to request a suitable channel. The identifier of the selected channel will be shared with the bot when the corresponding button is pressed.
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
    public static function RequestChannel(string $text, int $buttonId, ?bool $creator = null, ?bool $hasUsername = null, ?bool $member = null, bool $name = false, bool $username = false, bool $photo = false, ?AdministratorRights $userAdminRights = null, ?AdministratorRights $botAdminRights = null): KeyboardButton
    {
        return new RequestChannel($text, $buttonId, $creator, $hasUsername, $member, $name, $username, $photo, $userAdminRights, $botAdminRights);
    }
}
