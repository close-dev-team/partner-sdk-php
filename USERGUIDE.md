# Close PHP SDK user guide


In this Close PHP SDK user guide we will explain and demonstrate the use-cases you will encounter when partnering with the Close app. 


**Table of Contents:**    
  - [Getting Started](#getting-started)
  - [*Examples*](#examples)
    - [Authorise](#authorise)
    - [Send text Message](#send-text-message)
      - [To all users in all chats for an event](#to-all-users-in-all-chats-for-an-event)
    - [Import tickets using the Close App](#import-tickets-using-the-close-app)
  - [Getting Help](#getting-help)
  - [Features](#features)
  - [Contributing](#contributing)
  - [Resources](#resources)
  
---

### Getting Started

For now it is only possible to start using the Close PHP SDK by getting in touch with us first. Get in touch with your contact person at Close or [contact us][contact-us] directly. 

1. **Get your credentials** – Before you begin, you need to already have an account with Close. If that is not the case, feel free to [contact us][contact-us].
2. **Minimum requirements** – In order to use the Close SDK, your system will need to meet the [minimum requirements][docs-requirements], which includes having **PHP >= 7.4**.
3. **Install the SDK** – The recommended way to use the Close SDK is by installing it with [Composer][composer]:

   ```
   composer require close-dev-team/partner-sdk-php
   ```

4. **Using the SDK** – In this page you will learn how to use the SDK, but if you want to get more information about the calls, you can always see our [Close Partner API Documentation][partner-api-doc], which this SDK is a wrapper of.

---

## *Examples*


### Setting up the Close PHP SDK client.
Let's start with instantiating the Close client using the client credentials given to you by Close. 

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Options;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Instantiate the Close client using the client credentials given by Close
  $sdk = new CloseSdk(
       new Options([
            'client_id' => 'client_test',
            'client_secret' => 'client_test_secret',
       ])
  );
} catch (CloseSdkException $closeSdkException) {
    // You can receive an error if the token was not generated because of invalid credentials
}

```

### Send text Message
One of the core features of the Close PHP SDK is sending ultra personalised text messages to Close users. There are 4 endpoints available in the SendMessage class, with each a different use-case. 

| Endpoint | Use-case |
| -------- | ----------- |
|**toAllChatsForShow(eventId, text)**| Use when you need to reach all users for an event|
|**toAllUsersForChat(eventId, chatId, text)**|Use when you need to reach all users in one specific chat for an event |
|**toUserInChat(eventId, chatId, userId, text)**|Use when you need to reach one specific user, in a specific chat for an event|
|**toUserInAllChats(eventId, userId)**|Use when you need to reach one specific user in all chats for one event|

*examples:*
#### toAllChatsForShow(eventId,text)
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $message = 'This is the message to send';
  
  $sdk
    ->sendMessage()
    ->toAllChatsForShow($eventId, $message);
} catch (CloseSdkException $e) {
    echo "The message has not been send.";
    // We recommend to retry after a couple of seconds.
}
```
#### toAllUsersForChat(eventId,chatId,text)
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $chatId = new ChatId('CLECxxxxx');
  $message = 'This is the message to send';
  
  $sdk
    ->sendMessage()
    ->toAllUsersForChat($eventId, $chatId, $message);
} catch (CloseSdkException $e) {
    echo "The message has not been send.";
    // We recommend to retry after a couple of seconds.
}
```
#### toUserInChat(eventId, chatId, userId, text)
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $chatId = new ChatId('CLECxxxxx');
  $userId = new UserId('abcxxx');
  $message = 'This is the message to send';
  
  $sdk
    ->sendMessage()
    ->toUserInChat($eventId, $chatId,$userId, $message);
} catch (CloseSdkException $e) {
    echo "The message has not been send.";
    // We recommend to retry after a couple of seconds.
}
```
#### toUserInAllChats(eventId, userId)
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $userId = new UserId('abcxxx');
  $message = 'This is the message to send';
  
  $sdk
    ->sendMessage()
    ->toUserInAllChats($eventId, $userId, $message);
} catch (CloseSdkException $e) {
    echo "The message has not been send.";
    // We recommend to retry after a couple of seconds.
}
```












### Import tickets using the Close App

```php
<?php
try {
    $closeClient->importTickets([
        'info' => 'info',
    ]);
} catch (CloseClient\Exception\CloseException $e) {
    echo "The ticket has not been imported.\n";
    // We recommend to retry after a couple of seconds.
}
```

## Getting Help

Feel free to let us know if you have encountered any questions or problems using our SDK. We will try to make sure that we will get back to you as soon as possible.

* If you have questions that have not been answered in this documentation, please [contact us][contact-us].
* If you think that you may have found a bug, feel free to [open an issue][open-issue].

## Features

* Provides a very easy way to communicate with our [partner API][partner-api-doc] for all of the supported endpoints. This means that we always fetch the correct data based on your API credentials.
* It is built on the latest software, with the highest security standards and following the [PSR conventions][PSR].
* We use [Guzzle][guzzle] to generate these requests and we make use of its technology (async requests, middlewares, etc.).
* We provide a data structure of our domain that can be easily used by external PHP applications.
* We give back clear responses and exceptions in case something doesn't go as expected.

## Contributing

If you have ideas on how to improve our SDK, don't hesitate to [open an issue][open-issue] and let us know! 
If you already have code ready that would help us improve our system, you are free to [open a PR][open-pr]. All the extra help is highly appreciated!

## Resources

* [API Docs][partner-api-doc] – For more details about the parameters of each endpoint, validation and responses.
* [Website][the-close-app] – More information about Close and what we do.
* [Issues][open-issue] – Report issues and submit pull requests.
* [License][apache-license] – More information about our license.

[contact-us]: devteam@thecloseapp.com
[partner-api-doc]: https://partner.closeapi.nl/api/documentation
[apache-license]: https://www.apache.org/licenses/LICENSE-2.0
[PSR]: https://www.php-fig.org/psr/

[open-issue]: https://github.com/close-dev-team/partner-sdk-php/issues/new/choose
[open-pr]: https://github.com/close-dev-team/partner-sdk-php/compare

[the-close-app]: http://thecloseapp.com
[guzzle]: http://guzzlephp.org
[composer]: http://getcomposer.org

