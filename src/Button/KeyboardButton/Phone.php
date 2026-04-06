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
use Reymon\Type\Button\KeyboardButton;

/**
 * Represents text button that request contact info from user.
 */
final class Phone extends KeyboardButton
{
    /**
     * @param string $text    Label text on the button
     * @param Color  $color   Style of the button.
     * @param ?int   $emojiId Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on [Fragment](https://fragment.com/) or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     */
    public function __construct(string $text, Color $color = Color::NONE, ?int $emojiId = null)
    {
        parent::__construct($text, $color, $emojiId);
    }

    /**
     * Create text button that request contact info from user.
     *
     * @param string $text    Label text on the button
     * @param Color  $color   Style of the button.
     * @param ?int   $emojiId Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on [Fragment](https://fragment.com/) or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     */
    public static function new(string $text, Color $color = Color::NONE, ?int $emojiId = null): self
    {
        return new static($text, $color, $emojiId);
    }

    #[\Override]
    public function toApi(): array
    {
        return \array_merge(
            parent::toApi(),
            ['request_contact' => true],
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        return \array_merge(
            parent::toMtproto(),
            ['_' => 'keyboardButtonRequestPhone'],
        );
    }
}
