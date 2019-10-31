<?php
/**
 * Web Jump - Shop.
 * This file is part of the Nogues shop.
 */

declare(strict_types=1);

namespace Nogues\Common;

/**
 * Class to get notifications.
 *
 * @package Nogues\Common
 * @author  Marcio Vinicius <marciovinicius55@gmail.com>
 */
final class Notification
{
    /**
     * Notifications.
     *
     * @var array
     */
    private $notifications = [];

    public function add($notifications): void
    {
        $this->notifications[] = $notifications;
    }

    /**
     * Get all notifications.
     *
     * @return \Generator
     */
    public function getNotifications(): \Generator
    {
        foreach ($this->notifications as $notification) {
            yield $notification;
        }
    }
}
