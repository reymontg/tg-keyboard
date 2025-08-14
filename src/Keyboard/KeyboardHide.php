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

use Reymon\Exception\KeyboardException;
use Reymon\Type\Button;
use Reymon\Type\Keyboard;
use Reymon\Type\Utils\Selective;

/**
 * Requests clients to remove the custom keyboard (user will not be able to summon this keyboard; if you want to hide the keyboard from sight but keep it accessible, use one_time_keyboard.
 */
final class KeyboardHide extends Keyboard
{
    use Selective;

    /**
     * @param bool $selective Whether to show the keyboard to specific users only. Targets: 1- users that are @mentioned in the text of the [Message](https://core.telegram.org/bots/api#message) object 2- if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.
     */
    public function __construct(bool $selective = false)
    {
        $this->selective = $selective;
    }

    /**
     * @param bool $selective Whether to show the keyboard to specific users only. Targets: 1- users that are @mentioned in the text of the [Message](https://core.telegram.org/bots/api#message) object 2- if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.
     */
    public static function new(bool $selective = false): self
    {
        return new static($selective);
    }

    public function addButton(Button ...$button): Keyboard
    {
        throw new KeyboardException(\sprintf('%s cannot use %s', __CLASS__, __METHOD__));
    }

    #[\Override]
    public function toApi(): array
    {
        return [
            'remove_keyboard' => true,
            'selective' => $this->selective,
        ];
    }

    #[\Override]
    public function toMtproto(): array
    {
        return [
            '_' => 'replyKeyboardHide',
            'selective' => $this->selective,
        ];
    }

    /**
     * @internal
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }

    /**
     * @internal
     */
    public function getIterator(): \EmptyIterator
    {
        return new \EmptyIterator;
    }
}
