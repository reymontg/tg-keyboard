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

namespace Reymon\Keyboard;

use Reymon\Button;
use Reymon\Keyboard;
use Reymon\Utils\SingleUse;
use Reymon\Utils\Selective;
use Reymon\Utils\Placeholder;
use Reymon\Exception\KeyboardException;

/**
 * Shows reply interface to the user, as if they manually selected the bot's message and tapped 'Reply'.
 */
final class KeyboardForceReply extends Keyboard
{
    use Selective, Placeholder, SingleUse;

    public function addButton(Button ...$buttons): Keyboard
    {
        throw new KeyboardException(\sprintf('%s cannot use %s', __CLASS__, __METHOD__));
    }

    #[\Override]
    public function toApi(): array
    {
        return array_filter_null([
            'force_reply' => true,
            'selective'   => $this->selective,
            'input_field_placeholder' => $this->placeholder // todo: add one time keyboard?
        ]);
    }

    #[\Override]
    public function toMtproto(): array
    {
        return array_filter_null([
            '_' => 'replyKeyboardForceReply',
            'single_use'  => $this->singleUse,
            'selective'   => $this->selective,
            'placeholder' => $this->placeholder
        ]);
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }

    /**
     * @internal
     */
    #[\Override]
    public function getIterator(): \EmptyIterator
    {
        return new \EmptyIterator;
    }
}
