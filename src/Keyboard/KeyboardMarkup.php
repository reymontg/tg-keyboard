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
use Reymon\Utils\Selective;
use Reymon\Utils\SingleUse;
use Reymon\Utils\EasyMarkup;
use Reymon\Utils\Placeholder;

/**
 * Represents a custom keyboard with reply options.
 */
final class KeyboardMarkup extends Keyboard
{
    use Selective, Placeholder, SingleUse, EasyMarkup;

    private bool $resize = true;
    private bool $alwaysShow = false;

    /**
     * Whether to resize the keyboard vertically for optimal fit (e.g., make the keyboard smaller if there are just two rows of buttons). Defaults to false, in which case the custom keyboard is always of the same height as the app's standard keyboard.
     */
    public function resize(bool $resize = true): self
    {
        $this->resize = $resize;
        return $this;
    }

    /**
     * Whether to always show the keyboard when the regular keyboard is hidden. Defaults to false, in which case the custom keyboard can be hidden and opened with a keyboard icon.
     */
    public function alwaysShow(bool $alwaysShow = true): self
    {
        $this->alwaysShow = $alwaysShow;
        return $this;
    }

    #[\Override]
    public function toApi(): array
    {
        return array_filter_null([
            'keyboard' => $this->getButtons(),
            'is_persistent'     => $this->alwaysShow,
            'resize_keyboard'   => $this->resize,
            'one_time_keyboard' => $this->singleUse,
        ]);
    }

    #[\Override]
    public function toMtproto(): array
    {
        return array_filter_null([
            '_' => 'replyKeyboardMarkup',
            'rows'        => $this->getButtons(),
            'resize'      => $this->resize,
            'single_use'  => $this->singleUse,
            'selective'   => $this->selective,
            'persistent'  => $this->alwaysShow,
            'placeholder' => $this->placeholder,
        ]);
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->toApi();
    }
}
