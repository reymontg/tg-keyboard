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
use Reymon\Utils\Url;

/**
 * Represents inline button for login.
 */
final class LoginUrl extends InlineButton
{
    use Url;

    /**
     * @param string  $text        Label text on the button
     * @param string  $url         An HTTPS URL used to automatically authorize the user
     * @param ?string $forwardText New text of the button in forwarded messages
     * @param ?string $username    Username of a bot, which will be used for user authorization.
     * @param bool    $writeAccess Whether to request the permission for your bot to send messages to the user
     */
    public function __construct(string $text, private string $url, private ?string $forwardText = null, private ?string $username = null, private bool $writeAccess = false)
    {
        parent::__construct($text);
    }

    public function setForwardText(?string $forwardText = null): self
    {
        $this->forwardText = $forwardText;
        return $this;
    }

    public function getForwardText(): ?string
    {
        return $this->forwardText;
    }

    public function setUsername(?string $username = null): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setWriteAccess(bool $writeAccess = false): self
    {
        $this->writeAccess = $writeAccess;
        return $this;
    }

    public function getWriteAccess(): bool
    {
        return $this->writeAccess;
    }

    /**
     * Create inline button for login.
     *
     * @param string  $text        Label text on the button
     * @param string  $url         An HTTPS URL used to automatically authorize the user
     * @param ?string $forwardText New text of the button in forwarded messages
     * @param ?string $username    Username of a bot, which will be used for user authorization.
     * @param bool    $writeAccess Whether to request the permission for your bot to send messages to the user
     */
    public static function new(string $text, string $url, ?string $forwardText = null, ?string $username = null, bool $writeAccess = false): self
    {
        return new static($text, $url, $forwardText, $username, $writeAccess);
    }

    #[\Override]
    public function toApi(): array
    {
        return \array_merge(
            parent::toApi(),
            [
                'text'      => $this->text,
                'login_url' => array_filter_null([
                    'url' => $this->url,
                    'forward_text' => $this->forwardText,
                    'bot_username' => $this->username,
                    'request_write_access' => $this->writeAccess,
                ]),
            ],
        );
    }

    #[\Override]
    public function toMtproto(): array
    {
        return \array_merge(
            parent::toMtproto(),
            [
                '_' => 'inputKeyboardButtonUrlAuth',
                'url' => $this->url,
                'bot' => $this->username,
                'fwd_text' => $this->forwardText,
                'request_write_access' => $this->writeAccess,
            ],
        );
    }
}
