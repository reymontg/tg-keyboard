<?php declare(strict_types=1);

/**
 * This file is part of Reymon.
 * Reymon is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * Reymon is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    AhJ <AmirHosseinJafari8228@gmail.com>
 * @copyright Copyright (c) 2023, ReymonTg
 * @license   https://choosealicense.com/licenses/gpl-3.0/ GPLv3
 */

namespace Reymon\Type\Button\KeyboardButton;

/**
 * Represents button the criteria used to request suitable users. The identifiers of the selected users will be shared with the bot when the corresponding button is pressed.
 */
final class RequestUsers extends RequestPeer
{
    /**
     * @param string  $text        Label text on the button
     * @param int     $buttonId    Signed 32-bit identifier of the request
     * @param ?bool   $bot         Whether to request bots or users, If not specified, no additional restrictions are applied.
     * @param ?bool   $premium     Whether to request premium or non-premium users. If not specified, no additional restrictions are applied.
     * @param bool    $name        Whether to request the users' first and last name
     * @param bool    $username    Whether to request the users' username
     * @param bool    $photo       Whether to request the users' photo
     * @param int     $maxQuantity The maximum number of users to be selected; 1-10.
     */
    public function __construct(string $text, int $buttonId, private ?bool $bot = null, private ?bool $premium = null, bool $name = false, bool $username = false, bool $photo = false, private int $maxQuantity = 1)
    {
        parent::__construct($text, $buttonId, $name, $username, $photo);
    }

    public function setIsBot(?bool $bot = null): self
    {
        $this->bot = $bot;
        return $this;
    }

    public function getIsBot(): bool
    {
        return $this->bot;
    }

    public function setMaxQuantity(int $maxQuantity = 1): self
    {
        $this->maxQuantity = $maxQuantity;
        return $this;
    }

    public function getMaxQuantity(): int
    {
        return $this->maxQuantity;
    }

    /**
     * Create button the criteria used to request suitable users. The identifiers of the selected users will be shared with the bot when the corresponding button is pressed.
     *
     * @param string  $text        Label text on the button
     * @param int     $buttonId    Signed 32-bit identifier of the request
     * @param ?bool   $bot         Whether to request bots or users, If not specified, no additional restrictions are applied.
     * @param ?bool   $premium     Whether to request premium or non-premium users. If not specified, no additional restrictions are applied.
     * @param bool    $name        Whether to request the users' first and last name
     * @param bool    $username    Whether to request the users' username
     * @param bool    $photo       Whether to request the users' photo
     * @param int     $maxQuantity The maximum number of users to be selected; 1-10.
     */
    public static function new(string $text, $buttonId, ?bool $bot = null, ?bool $premium = null, bool $name = false, bool $username = false, bool $photo = false, int $maxQuantity = 1): self
    {
        return new static($text, $buttonId, $bot, $premium, $name, $username, $photo, $maxQuantity);
    }

    #[\Override]
    public function toApi(): array
    {
        return \array_merge(
            parent::toApi(),
            [
                'request_users'  => array_filter_null([
                    'request_id'       => $this->buttonId,
                    'request_name'     => $this->name,
                    'request_photo'    => $this->photo,
                    'request_username' => $this->username,
                    'user_is_bot'      => $this->bot,
                    'user_is_premium'  => $this->premium,
                    'max_quantity'     => $this->maxQuantity,
                ])
            ],
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        return \array_merge(
            parent::toMtproto(),
            [
                'peer_type' => array_filter_null([
                    '_' => 'requestPeerTypeUser',
                    'bot'     => $this->bot,
                    'premium' => $this->premium,
                ])
            ],
        );
    }
}
