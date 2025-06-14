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

use Reymon\Keyboard;
use Reymon\Utils\EasyInline;

/**
 * Represents an inline keyboard that appears right next to the message it belongs to.
 */
final class KeyboardInline extends Keyboard
{
    use EasyInline;

    #[\Override]
    public function toApi(): array
    {
        return ['inline_keyboard' => $this->getButtons()];
    }

    #[\Override]
    public function toMtproto(): array
    {
        return [
            '_'    => 'replyInlineMarkup',
            'rows' => $this->getButtons(),
        ];
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }
}
