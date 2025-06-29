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

use Reymon\Type\Button\KeyboardButton;
use Reymon\Type\Button\PollType;

/**
 * Represents text button that request poll from user.
 */
final class Poll extends KeyboardButton
{
    /**
     * @param string $text Label text on the button
     */
    public function __construct(string $text, private PollType $type = PollType::ALL)
    {
        parent::__construct($text);
    }

    public function setPollType(PollType $type = PollType::ALL): self
    {
        $this->type = $type;
        return $this;
    }

    public function getPollType(): PollType
    {
        return $this->type;
    }

    /**
     * Create text button that request poll from user.
     *
     * @param string   $text Label text on the button
     * @param PollType $type Type of a poll, which is allowed to be created and sent when the corresponding button is pressed.
     */
    public static function new(string $text, PollType $type = PollType::ALL): self
    {
        return new static($text, $type);
    }

    #[\Override]
    public function toApi(): array
    {
        return \array_merge(
            parent::toApi(),
            ['request_poll' => $this->type->toApi()],
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        $data = ['_' => 'keyboardButtonRequestPoll'];

        if ($this->type === PollType::QUIZ) {
            $data['quiz'] = true;
        }

        return \array_merge(
            parent::toMtproto(),
            $data,
        );
    }
}
