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

use Reymon\Type\Button\Color;

/**
 * Represents button for the creation of a managed bot. Information about the created bot will be shared with the bot using the update _managed_bot_ and a [Message](https://core.telegram.org/bots/api#message) with the field _managed_bot_created_.
 */
final class RequestBot extends RequestButton
{
    /**
     * @param string  $text     Label text on the button
     * @param int     $buttonId Signed 32-bit identifier of the request
     * @param ?string $name     Suggested name for the bot
     * @param ?string $username Suggested username for the bot
     * @param Color   $color    Style of the button.
     * @param ?int    $emojiId  Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on [Fragment](https://fragment.com/) or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     */
    public function __construct(string $text, int $buttonId, private ?string $name = null, private ?string $username = null, Color $color = Color::NONE, ?int $emojiId = null)
    {
        parent::__construct($text, $buttonId, $color, $emojiId);
    }

    public function setName(?string $name = null): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setUsername(?string $username = null): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public static function new(string $text, int $buttonId, ?string $name = null, ?string $username = null, Color $color = Color::NONE, ?int $emojiId = null): self
    {
        return new static($text, $buttonId, $name, $username, $color, $emojiId);
    }

    #[\Override]
    public function toApi(): array
    {
        return \array_merge(
            parent::toApi(),
            [
                'request_managed_bot'  => array_filter_null([
                    'request_id'         => $this->buttonId,
                    'suggested_name'     => $this->name,
                    'suggested_username' => $this->username,
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
                '_' => 'inputKeyboardButtonRequest',
                'button_id' => $this->buttonId, // todo: Wating for madelineproto
            ],
        );
    }
}
