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

namespace Reymon;

use Reymon\Mtproto\Type;
use Reymon\Keyboard\KeyboardInline;
use RangeException;
use OutOfBoundsException;

/**
 * @implements \IteratorAggregate<list<Button>>
 */
abstract class Keyboard implements Type, \IteratorAggregate
{
    private int $index = 0;

    /**
     * @var list<list<Button>>
     */
    private array $rows = [];

    /**
     * Create new tg-keyboard.
     */
    public static function new(): static
    {
        return new static;
    }

    private function getRows(): array
    {
        $rows = $this->rows;
        if (empty($rows[$this->index])) {
            unset($rows[$this->index]);
        }
        return $rows;
    }


    /**
     * Add button(s) to keyboard.
     */
    public function addButton(Button ...$button): self
    {
        $row = &$this->rows[$this->index];
        $row = \array_merge($row, $button);
        return $this;
    }

    /**
     * To add a button by it coordinates to keyboard (Note that coordinates start from 0 look like arrays indexes).
     *
     */
    public function addToCoordinates(int $row, int $column, Button ...$button): self
    {
        \array_splice($this->rows[$row], $column, 0, $button);
        return $this;
    }

    /**
     * To replace a button by it coordinates to keyboard (Note that coordinates start from 0 look like arrays indexes).
     *
     * @throws OutOfBoundsException
     */
    public function replaceIntoCoordinates(int $row, int $column, Button ...$button): self
    {
        if (\array_key_exists($row, $this->rows) && \array_key_exists($column, $this->rows[$row])) {
            \array_splice($this->rows[$row], $column, \count($button), $button);
            return $this;
        }
        throw new OutOfBoundsException("Please be sure that $row and $column exists in array keys!");
    }

    /**
     * To remove button by it coordinates to keyboard (Note that coordinates start from 0 look like arrays indexes).
     *
     * @throws OutOfBoundsException
     */
    public function removeFromCoordinates(int $row, int $column, int $count = 1): self
    {
        if (\array_key_exists($row, $this->rows) && \array_key_exists($column, $this->rows[$row])) {
            \array_splice($this->rows[$row], $column, $count);
            $currentRow = $this->rows[$row];
            if (\count($currentRow) == 0) {
                \array_splice($this->rows, $row, 1);
            }
            return $this;
        }
        throw new OutOfBoundsException("Please be sure that $row and $column exists in array keys!");
    }

    /**
     * Remove the last button from keyboard.
     *
     * @throws RangeException
     */
    public function remove(): self
    {
        if (!empty($rows = $this->rows) && !empty($endButtons = \end($rows))) {
            $endRow    = \array_keys($rows);
            $endButton = \array_keys($endButtons);

            if (\count($endButtons) == 1) {
                unset($this->rows[\end($endRow)]);
            }
            unset($this->rows[\end($endRow)][\end($endButton)]);
            return $this;
        }
        throw new RangeException("Keyboard array is already empty!");
    }

    /**
     * Add a new raw with specified button ( pass null to only add new row).
     *
     */
    public function row(?Button ...$button): self
    {
        $row = &$this->rows[$this->index];

        if (!empty($row)) {
            $this->rows = [];
            $this->index++;
        }

        if (!empty($button)) {
            $this->addButton(... $button);
            $this->row();
        }

        return $this;
    }

    /**
     * Add specified buttons to keyboard (each button will add to new row).
     */
    public function stack(?Button ...$button): self
    {
        \array_map($this->row(...), $button);
        return $this;
    }

    /**
     * Convert Telegram api keyboard to easy-keyboard.
     *
     * @param array $replyMarkup array of Telegram api Keyboard
     */
    public static function tryFrom(array $replyMarkup): ?self
    {
        $replyMarkup = $replyMarkup['inline_keyboard'] ?? false;
        if (!$replyMarkup) {
            return null;
        }
        $keyboard = new KeyboardInline;
        foreach ($replyMarkup as $row) {
            foreach ($row as $button) {
                $text  = $button['text'];
                $login = $button['login_url'] ?? null;
                $query = $button['switch_inline_query'] ?? $button['switch_inline_query_current_chat'] ?? $button['switch_inline_query_chosen_chat']['query'] ?? '';
                $keyboard->addButton(match (true) {
                    isset($button['url'])           => InlineButton::Url($text, $button['url']),
                    isset($button['pay'])           => InlineButton::Buy($text),
                    isset($button['callback_game']) => InlineButton::Game($text),
                    isset($button['callback_data']) => InlineButton::CallBack($text, $button['callback_data']),
                    isset($button['switch_inline_query']) => InlineButton::SwitchInline($text, $query),
                    isset($button['switch_inline_query_current_chat']) => InlineButton::SwitchInlineCurrent($text, $query),
                    isset($button['switch_inline_query_chosen_chat'])  => InlineButton::SwitchInlineFilter(
                        $text,
                        $query,
                        $button['switch_inline_query_chosen_chat']['allow_user_chats']    ?? null,
                        $button['switch_inline_query_chosen_chat']['allow_bot_chats']     ?? null,
                        $button['switch_inline_query_chosen_chat']['allow_group_chats']   ?? null,
                        $button['switch_inline_query_chosen_chat']['allow_channel_chats'] ?? null,
                    ),
                    !\is_null($login) => InlineButton::Login(
                        $text,
                        $login['url'],
                        $login['forward_text'] ?? null,
                        $login['bot_username'] ?? null,
                        isset($login['request_write_access'])
                    ),
                });
            }
            $keyboard->row();
        }
        return $keyboard;
    }

    #[\Override]
    public function toApi(): array
    {
        $rows = $this->getRows();
        return \array_map(
            fn (array $buttons) => \array_map(
                fn (Button $button): array => $button->toApi(),
                $buttons
            ),
            $rows
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        $rows = $this->getRows();
        return \array_map(
            fn (array $buttons) => [
                '_' => 'keyboardButtonRow',
                'buttons' => \array_map(
                    fn (Button $button): array => $button->toApi(),
                    $buttons
                )
            ],
            $rows
        );
    }

    /**
     * @internal
     */
    public function getIterator(): \Traversable
    {
        $rows = $this->getRows();

        foreach ($rows as $row) {
            foreach ($row as $button) {
                yield $button;
            }
        }
    }
}
