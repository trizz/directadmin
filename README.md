## DirectAdmin

This library provides a simple abstract DirectAdmin class that can be used to create (simple) "components" to consume the DirectAdmin API.

[![Build Status](https://travis-ci.org/trizz/directadmin.svg?branch=master)](https://travis-ci.org/trizz/directadmin)
[![StyleCI](https://styleci.io/repos/72576195/shield?branch=master)](https://styleci.io/repos/72576195)

### How to use

There are two ways to use this library:

**Without [composer](https://getcomposer.org/):**
```php
<?php
// Include the required files. "DirectAdmin.php" is ALWAYS required.
require_once '/path/to/library/src/DirectAdmin.php';
// After including the base class, you can load the components individually.
require_once '/path/to/library/src/MailingList.php';

// Initialize the component.
$daMailingList = new \Trizz\DirectAdmin\<COMPONENT>('http://<host>', '<username>', '<password>', '<domain>');
```

**With [composer](https://getcomposer.org/):**
```php
<?php
// Make sure that the composer autoload file is loaded. In most cases this is already handled by your app.

// Initialize the component.
$daMailingList = new \Trizz\DirectAdmin\<COMPONENT>('http://<host>', '<username>', '<password>', '<domain>');
```

### Examples
There's a directory called `/examples` that contains some example PHP files. 

### Available "components"
Below are the currently available classes.

#### General methods
There are some several methods that are available in every component.

| Method | Description
| --- | ---
| `setDomain($domain)` | Update the domain to use. Can be used to switch to another domain with the same user credentials.

#### MailingList
This component allows you to manage the available mailing lists inside DirectAdmin.
Available methods:

| Method | Description
| --- | ---
| `lists()` | Get an overview of the available mailing lists.
| `addList($name)` | Add a new mailing list.
| `deleteList($name)` | Remove a mailing list.
| `getSubscribers($listName, $subscriberType = all)` | Get all subscribers for the specified list. The `$subscriberType` can be: subscribers or digest_subscribers. By default both types are returned.
| `addAddress($address, $list, $type = 'list')` | Add a single email address to the specified mailing list. `$type` Can be "list" or "digest". Default: list.
| `addAddresses($addresses, $list, $type = 'list')` | Add multiple email addresses to the specified mailing list. `$type` Can be "list" or "digest". Default: list.
| `deleteAddress($address, $list, $type = 'list')` | Delete an email address from the specified list. `$type` Can be "list" or "digest". Default: list.
| `deleteAddresses($addresses, $list, $type = 'list')` | Delete multiple email addresses from the specified mailing list. `$type` Can be "list" or "digest". Default: list.
