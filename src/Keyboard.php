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

namespace Reymon\Type;

use Reymon\Type\Chat\AdministratorRights;
use Reymon\Mtproto\Type;
use Reymon\Type\Keyboard\KeyboardMarkup;
use Reymon\Type\Keyboard\KeyboardInline;
use Reymon\Type\Button\PollType;
use Reymon\Type\Button\KeyboardButton;
use Reymon\Type\Button\InlineButton;

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
        $row = \array_merge($row ?? [], $button);
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
     * @throws \OutOfBoundsException
     */
    public function replaceIntoCoordinates(int $row, int $column, Button ...$button): self
    {
        if (\array_key_exists($row, $this->rows) && \array_key_exists($column, $this->rows[$row])) {
            \array_splice($this->rows[$row], $column, \count($button), $button);
            return $this;
        }
        throw new \OutOfBoundsException("Please be sure that $row and $column exists in array keys!");
    }

    /**
     * To remove button by it coordinates to keyboard (Note that coordinates start from 0 look like arrays indexes).
     *
     * @throws \OutOfBoundsException
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
        throw new \OutOfBoundsException("Please be sure that $row and $column exists in array keys!");
    }

    /**
     * Remove the last button from keyboard.
     *
     * @throws \RangeException
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
        throw new \RangeException("Keyboard array is already empty!");
    }

    /**
     * Add a new raw with specified button ( pass null to only add new row).
     *
     */
    public function row(?Button ...$button): self
    {
        $row = &$this->rows[$this->index];

        if (!empty($row)) {
            $this->rows[]= [];
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
     * Convert Telegram api keyboard.
     *
     * @param array $replyMarkup array of Mtproto keyboard
     */
    public static function fromMtproto(array $replyMarkup): ?self
    {
        $keyboard = match ($replyMarkup['_']) {
            'replyInlineMarkup'   => KeyboardInline::new(),
            'replyKeyboardMarkup' => KeyboardMarkup::new(
                $replyMarkup['resize']      ?? false,
                $replyMarkup['single_use']  ?? false,
                $replyMarkup['persistent']  ?? false,
                $replyMarkup['selective']   ?? false,
                $replyMarkup['placeholder'] ?? null,
            ),
            default => null,
        };

        foreach ($replyMarkup['rows'] ?? [] as ['buttons' => $buttons]) {
            foreach ($buttons as $button) {
                $text  = $button['text'];
                if ($button['_'] === 'keyboardButtonSwitchInline') {
                    $query = $button['query'];
                    if ($button['same_peer']) {
                        $button = InlineButton::SwitchInlineCurrent($text, $query);
                    } elseif (isset($button['peer_types']) && !empty($button['peer_types'])) {
                        $types  = \array_column($button['peer_types'], '_');
                        $button = InlineButton::SwitchInlineFilter(
                            $text,
                            $query,
                            \in_array('inlineQueryPeerTypePM', $types) ?: null,
                            \in_array('inlineQueryPeerTypeBotPM', $types) ?: null,
                            \in_array('inlineQueryPeerTypeChat', $types) || \in_array('inlineQueryPeerTypeMegagroup', $types)   ?: null,
                            \in_array('inlineQueryPeerTypeBroadcast', $types) ?: null,
                        );
                    } else {
                        $button = InlineButton::SwitchInline($text, $query);
                    }
                } elseif ($button['_'] === 'inputKeyboardButtonRequestPeer') {
                    $buttonId = $button['button_id'];
                    $name  = isset($button['name_requested'])  ? $button['name_requested'] : null;
                    $photo = isset($button['photo_requested']) ? $button['photo_requested'] : null;
                    $username = isset($button['username_requested']) ? $button['username_requested'] : null;
                    $userAdminRights = $button['peer_type']['user_admin_rights'] ?? null;
                    $botAdminRights  = $button['peer_type']['bot_admin_rights']  ?? null;
                    $button = match ($button['peer_type']['_']) {
                        'requestPeerTypeUser' => KeyboardButton::RequestUsers(
                            $text,
                            $buttonId,
                            isset($button['peer_type']['bot']) ? $button['peer_type']['bot'] : null,
                            isset($button['peer_type']['premium']) ? $button['peer_type']['premium'] :null,
                            $name,
                            $photo,
                            $username,
                            $button['max_quantity'] ?? 1,
                        ),
                        'requestPeerTypeChat' => KeyboardButton::RequestGroup(
                            $text,
                            $buttonId,
                            isset($button['peer_type']['creator']) ? $button['peer_type']['creator'] : null,
                            isset($button['peer_type']['has_username']) ? $button['peer_type']['has_username'] : null,
                            isset($button['peer_type']['forum']) ? $button['peer_type']['forum'] : null,
                            isset($button['peer_type']['bot_participant']) ? $button['peer_type']['bot_participant'] : null,
                            $name,
                            $photo,
                            $username,
                            $userAdminRights ? AdministratorRights::fromMtproto($userAdminRights) : null,
                            $botAdminRights  ? AdministratorRights::fromMtproto($botAdminRights)  : null,
                        ),
                        'requestPeerTypeBroadcast' => KeyboardButton::RequestChannel(
                            $text,
                            $buttonId,
                            $button['peer_type']['creator'],
                            $button['peer_type']['has_username'],
                            null,
                            $name,
                            $photo,
                            $username,
                            $userAdminRights ? AdministratorRights::fromMtproto($userAdminRights) : null,
                            $botAdminRights  ? AdministratorRights::fromMtproto($botAdminRights)  : null,
                        )
                    };
                } else {
                    $button = match ($button['_']) {
                        // Reply markup
                        'keyboardButton'                   => KeyboardButton::Text($text),
                        'keyboardButtonRequestPhone'       => KeyboardButton::Phone($text),
                        'keyboardButtonRequestGeoLocation' => KeyboardButton::Location($text),
                        'keyboardButtonSimpleWebView'      => KeyboardButton::Webapp($text, $button['url']),
                        'keyboardButtonRequestPoll'        => KeyboardButton::Poll(
                            $text,
                            isset($button['quiz'])
                                ? ($button['quiz'] ? PollType::QUIZ : PollType::REGULAR)
                                : PollType::ALL
                        ),
                        // Inline markup
                        'keyboardButtonGame'     => InlineButton::Game($text),
                        'keyboardButtonBuy'      => InlineButton::Buy($text),
                        'keyboardButtonUrl'      => InlineButton::Url($text, $button['url']),
                        'keyboardButtonWebView'  => InlineButton::Webapp($text, $button['url']),
                        'keyboardButtonCallback' => InlineButton::CallBack($text, $button['data']),
                        'keyboardButtonCopy'     => InlineButton::CopyText($text, $button['copy_text']),
                        'keyboardButtonUrlAuth',
                        'inputKeyboardButtonUrlAuth' => InlineButton::LoginUrl(
                            $text,
                            $button['url'],
                            $button['fwd_text'] ?? null,
                            $button['bot'] ?? null,
                            isset($button['request_write_access']),
                        ),
                    };
                }
                $keyboard->addButton($button);
            }
            $keyboard->row();
        }
        return $keyboard;
    }

    /**
     * Convert Telegram api keyboard.
     *
     * @param array $replyMarkup array of Telegram api keyboard
     */
    public static function fromBotApi(array $replyMarkup): ?self
    {
        if (!isset($replyMarkup['inline_keyboard'])) {
            return null;
        }
        $keyboard = new KeyboardInline;

        foreach ($replyMarkup['inline_keyboard'] as $row) {
            foreach ($row as $button) {
                $text  = $button['text'];
                $query = $button['switch_inline_query'] ?? $button['switch_inline_query_current_chat'] ?? $button['switch_inline_query_chosen_chat']['query'] ?? '';
                $keyboard->addButton(match (true) {
                    isset($button['pay']) => InlineButton::Buy($text),
                    isset($button['url']) => InlineButton::Url($text, $button['url']),
                    isset($button['web_app']) => InlineButton::Webapp($text, $button['url']),
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
                    isset($button['login_url']) => InlineButton::LoginUrl(
                        $text,
                        $button['login_url']['url'],
                        $button['login_url']['forward_text'] ?? null,
                        $button['login_url']['bot_username'] ?? null,
                        isset($button['login_url']['request_write_access'])
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
        return \array_map(
            fn (array $buttons) => \array_map(
                fn (Button $button): array => $button->toApi(),
                $buttons
            ),
            $this->getRows()
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        return \array_map(
            fn (array $buttons) => [
                '_' => 'keyboardButtonRow',
                'buttons' => \array_map(
                    fn (Button $button): array => $button->toMtproto(),
                    $buttons
                )
            ],
            $this->getRows()
        );
    }

    /**
     * @internal
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getRows();
    }
}
