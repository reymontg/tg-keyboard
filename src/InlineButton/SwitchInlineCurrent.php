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

namespace Reymon\InlineButton;

/**
 * Represents inline button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted.
 */
final class SwitchInlineCurrent extends SwitchInline
{
    /**
     * @param string $text  Label text on the button
     * @param string $query Data to be sent in a [callback query](https://core.telegram.org/bots/api#callbackquery) to the bot when button is pressed, 1-64 bytes
     */
    public function __construct(string $text, string $query = '')
    {
        parent::__construct($text, $query);
    }

    /**
     * Create inline button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted.
     *
     * @param string $text  Label text on the button
     * @param string $query Data to be sent in a [callback query](https://core.telegram.org/bots/api#callbackquery) to the bot when button is pressed, 1-64 bytes
     */
    public static function new(string $text, string $query): self
    {
        return new static($text, $query);
    }

    #[\Override]
    public function toApi(): array
    {
        return ['switch_inline_query_current_chat' => $this->query, 'text' => $this->text];
    }

    #[\Override]
    public function toMtproto(): array
    {
        return \array_merge(
            parent::toMtproto(),
            ['_' => 'keyboardButtonSwitchInline', 'query' => $this->query, 'same_peer' => true],
        );
    }
}
