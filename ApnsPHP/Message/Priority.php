<?php

/**
 * APNS Push Priority
 *
 * SPDX-FileCopyrightText: Copyright 2024 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-2-Clause
 */

namespace ApnsPHP\Message;

/**
 * APNS Push Priority.
 */
enum Priority: int
{
    /**
     * Send notification immediately. This is the default.
     */
    case Immediately = 10;

    /**
     * Send the notification based on power considerations on the user's device.
     */
    case ConsiderPowerUsage = 5;

    /**
     * Prioritize the device's power considerations over all other factors for delivery,
     * and prevent awakening the device.
     */
    case PrioritizePowerUsage = 1;
}
