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

namespace Reymon\Type;

use Reymon\Mtproto\Type;
use Reymon\Type\Button\Color;

abstract class Button implements Type
{
    /**
     * @param string $text    Label text on the button
     * @param Color  $color   Style of the button.
     * @param ?int   $emojiId Unique identifier of the custom emoji shown before the text of the button. Can only be used by bots that purchased additional usernames on [Fragment](https://fragment.com/) or in the messages directly sent by the bot to private, group and supergroup chats if the owner of the bot has a Telegram Premium subscription.
     */
    public function __construct(protected string $text, protected Color $color = Color::NONE, protected ?int $emojiId = null) {}

    public function setText(string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setColor(Color $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setEmojiId(?int $emojiId = null): static
    {
        $this->emojiId = $emojiId;
        return $this;
    }

    public function getEmojiId(): ?int
    {
        return $this->emojiId;
    }

    #[\Override]
    public function toApi(): array
    {
        $button['text'] = $this->text;

        if ($this->emojiId !== null) {
            $button['icon_custom_emoji_id'] = $this->emojiId;
        }

        if ($this->color !== Color::NONE) {
            $button['style'] = $this->color->toApi();
        }

        return $button;
    }

    #[\Override]
    public function toMtproto(): array
    {
        $button['text'] = $this->text;

        if ($this->emojiId) {
            $button['style']['icon'] = $this->emojiId;
        }

        if ($this->color !== Color::NONE) {
            $button['style'][$this->color->toMtproto()] = true;
        }

        if (\array_key_exists('style', $button)) {
            $button['style']['_'] = 'keyboardButtonStyle';
        }

        return $button;
    }

    /**
     * @internal
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }
}
