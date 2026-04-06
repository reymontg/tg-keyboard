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

namespace Reymon\Type\Button;

use Reymon\Mtproto\Type;

enum Color: string implements Type
{
    case NONE = '';
    case RED    = 'danger';
    case GREEN  = 'success';
    case PURPLE = 'primary';

    #[\Override]
    public function toApi(): string
    {
        return $this->value;
    }

    #[\Override]
    public function toMtproto(): string
    {
        return match ($this) {
            Color::NONE   => '',
            Color::RED    => 'bg_danger',
            Color::GREEN  => 'bg_success',
            Color::PURPLE => 'bg_primary',
        };
    }

    /**
     * @internal
     */
    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->toApi();
    }
}
