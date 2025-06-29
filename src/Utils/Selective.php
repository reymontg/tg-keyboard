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

namespace Reymon\Type\Utils;

/**
 * @internal
 */
trait Selective
{
    private bool $selective = false;

    /**
     * Whether to show the keyboard to specific users only. Targets: 1- users that are @mentioned in the text of the [Message](https://core.telegram.org/bots/api#message) object 2- if the bot's message is a reply to a message in the same chat and forum topic, sender of the original message.
     */
    public function selective(bool $selective = true): self
    {
        $this->selective = $selective;
        return $this;
    }
}
