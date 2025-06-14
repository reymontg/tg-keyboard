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

namespace Reymon\InlineButton;

use Reymon\InlineButton;

/**
 * Represents button to be shown above inline query results.
 */
final class Start extends InlineButton
{
    /**
     * @param string $text  Label text on the button
     * @param string $param [Deep-linking](https://core.telegram.org/bots/features#deep-linking) parameter for the /start message sent to the bot when a user presses the button. 1-64 characters, only A-Z, a-z, 0-9, _ and - are allowed.
     */
    public function __construct(string $text, private string $param)
    {
        parent::__construct($text);
    }

    public function setParam(string $param = ''): self
    {
        $this->param = $param;
        return $this;
    }

    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * Create Button to be shown above inline query results.
     *
     * @param string $text  Label text on the button
     * @param string $param [Deep-linking](https://core.telegram.org/bots/features#deep-linking) parameter for the /start message sent to the bot when a user presses the button. 1-64 characters, only A-Z, a-z, 0-9, _ and - are allowed.
     */
    public static function new(string $text, string $param): self
    {
        return new static($text, $param);
    }

    #[\Override]
    public function toApi(): array
    {
        return \array_merge(
            parent::toApi(),
            ['start_parameter' => $this->param],
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        return \array_merge(
            parent::toMtproto(),
            ['_' => 'inlineBotSwitchPM', 'start_param' => $this->param],
        );
    }
}
