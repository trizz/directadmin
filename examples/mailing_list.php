<?php

// Include the required files.
require_once '../src/DirectAdmin.php';
require_once '../src/MailingList.php';

// Initialize the mailing list class.
$daMailingList = new \Trizz\DirectAdmin\MailingList('http://<host>', '<username>', '<password>', '<domain>');

// Use this domain suffix when generating random email addresses.
$testEmailSuffix = '@example.com';

// Use this name to create a "test" mailing list.
$testMailingList = 'api'.uniqid();

/******************************\
|    Run the tests/examples    |
\******************************/

// Create a 'test' mailing list.
$daMailingList->addList($testMailingList);

// Get all available mailing lists.
$availableLists = $daMailingList->lists();
// Loop through each list.
echo bold("Available lists:\n");
foreach ($availableLists as $list) {
    // Show the list name.
    echo sprintf("  %s (subscribers: %d / digest subscribers: %d)\n", bold($list['name']), $list['subscribers'], $list['digest_subscribers']);
    $subscribers = $daMailingList->getSubscribers($list['name']);

    // List the regular subscribers.
    echo bold(sprintf("    Subscribers in '%s':\n", $list['name']));
    foreach ($subscribers['subscribers'] as $member) {
        echo sprintf("        %s\n", $member);
    }

    // List the digest subscribers.
    echo bold(sprintf("    Digest subscribers in '%s':\n", $list['name']));
    foreach ($subscribers['digest_subscribers'] as $member) {
        echo sprintf("        %s\n", $member);
    }

    // Only proceed when the list name is "directAdminApiTest" because we're adding/removing new addresses!
    if ($list['name'] === $testMailingList) {
        // Generate a single and an array with multiple email addresses.
        $randomAddress = uniqid().$testEmailSuffix;
        $randomAddresses = [uniqid().$testEmailSuffix, uniqid().$testEmailSuffix, uniqid().$testEmailSuffix];

        // Test adding a single email address as subscriber.
        echo bold(sprintf("    Add a single address to the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->addAddress($randomAddress, $list['name'])['text']."\n";

        // Test adding multiple email addresses as subscribers.
        echo bold(sprintf("    Add multiple addresses to the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->addAddresses($randomAddresses, $list['name'])['text']."\n";

        // Test adding a single email address as digest subscriber.
        echo bold(sprintf("    Add a single digest address to the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->addAddress($randomAddress, $list['name'], 'digest')['text']."\n";

        // Test adding multiple email addresses as digest subscribers.
        echo bold(sprintf("    Add multiple digest addresses to the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->addAddresses($randomAddresses, $list['name'], 'digest')['text']."\n";

        // Test removing a single email address as subscriber.
        echo bold(sprintf("    Delete a single address from the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->deleteAddress($randomAddress, $list['name'])['text']."\n";

        // Test removing multiple email addresses as subscriber.
        echo bold(sprintf("    Delete multiple addresses from the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->deleteAddresses($randomAddresses, $list['name'])['text']."\n";

        // Test removing a single email address as digest subscriber.
        echo bold(sprintf("    Delete a single digest address from the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->deleteAddress($randomAddress, $list['name'], 'digest')['text']."\n";

        // Test removing multiple email addresses as digest subscriber.
        echo bold(sprintf("    Delete multiple digest addresses from the list '%s'.\n", $list['name']));
        echo '        '.$daMailingList->deleteAddresses($randomAddresses, $list['name'], 'digest')['text']."\n";
    }
}

// Remove the test mailing list.
$daMailingList->deleteList($testMailingList);

// Few newlines.
echo "\n\n";

// Little helper to format bold text.
function bold($text)
{
    return sprintf("\033[1m%s\033[0m", $text);
}
