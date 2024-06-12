<?php

/**
 * Push demo
 *
 * phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols, PSR1.Classes.ClassDeclaration.MissingNamespace
 *
 * SPDX-FileCopyrightText: Copyright 2010 Aldo Armiento (aldo.armiento@gmail.com)
 * SPDX-FileCopyrightText: Copyright 2021 M2mobi B.V., Amsterdam, The Netherlands
 * SPDX-FileCopyrightText: Copyright 2022 Move Agency Group B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: BSD-3-Clause
 */

define('VALID_TOKEN', '1e82db91c7ceddd72bf33d74ae052ac9c84a065b35148ac401388843106a7485');
define('INVALID_TOKEN', 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff');

// Adjust to your timezone
date_default_timezone_set('Europe/Rome');

// Report all PHP errors
error_reporting(-1);

// Using Composer autoload all classes are loaded on-demand
require_once 'vendor/autoload.php';

class SampleLogger extends \Psr\Log\AbstractLogger
{
    public function log($level, $message, array $context = []): void
    {
        printf("%s: %s ApnsPHP[%d]: %s\n", date('r'), strtoupper($level), getmypid(), trim($message));
    }
}

// Instantiate a new ApnsPHP_Push object
$push = new \ApnsPHP\Push(
    \ApnsPHP\Push::ENVIRONMENT_SANDBOX,
    'server_certificates_bundle_sandbox.pem',
    new SampleLogger(),
);

// Increase write interval to 100ms (default value is 10ms).
// This is an example value, the 10ms default value is OK in most cases.
// To speed up the sending operations, use Zero as parameter but
// some messages may be lost.
// $push->setWriteInterval(100 * 1000);

// Connect to the Apple Push Notification Service
$push->connect();

for ($i = 1; $i <= 10; $i++) {
    // Instantiate a new Message with a single recipient
    $message = new \ApnsPHP\Message($i == 5 ? INVALID_TOKEN : VALID_TOKEN);

    // Set a custom identifier. To get back this identifier use the getCustomIdentifier() method
    // over a ApnsPHP_Message object retrieved with the getErrors() message.
    $message->setCustomIdentifier(sprintf("Message-Badge-%03d", $i));

    // Set badge icon to "3"
    $message->setBadge($i);

    // Add the message to the message queue
    $push->add($message);
}

// Send all messages in the message queue
$push->send();

// Disconnect from the Apple Push Notification Service
$push->disconnect();

// Examine the error message container
$aErrorQueue = $push->getErrors();
if (!empty($aErrorQueue)) {
    var_dump($aErrorQueue);
}
