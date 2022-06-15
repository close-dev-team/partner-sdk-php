# Close SDK for PHP

[![Lastest Version](https://img.shields.io/github/v/tag/close-dev-team/partner-sdk-php)](https://github.com/close-dev-team/partner-sdk-php/releases)
[![Build Status](https://img.shields.io/github/workflow/status/close-dev-team/partner-sdk-php/run-automated-tests?style=flat)](https://github.com/close-dev-team/partner-sdk-php)
[![Apache 2 License](https://img.shields.io/github/license/close-dev-team/partner-sdk-php)](https://www.apache.org/licenses/LICENSE-2.0)

The **Close SDK for PHP** makes it easy for developers to communicate with [The Close App][the-close-app] in their PHP code. Get started really fast by [installing the SDK through Composer](#Getting-Started).

Jump To:
* [Getting Started](#Getting-Started)
* [Quick Examples](#Quick-Examples)
* [Full user guide](/USERGUIDE.md)
* [Getting Help](#Getting-Help)
* [Features](#Features)
* [Contributing](#Contributing)
* [Other Resources](#Resources)

## Getting Started

1. **Get your credentials** – Before you begin, you need to already have an account with Close. If that is not the case, feel free to [contact us][contact-us].
2. **Minimum requirements** – In order to use the Close SDK, your system will need to meet the [minimum requirements][docs-requirements], which includes having **PHP >= 7.4**.
3. **Install the SDK** – The recommended way to use the Close SDK is by installing it with [Composer][composer]:

   ```
   composer require close-dev-team/partner-sdk-php
   ```

4. **Using the SDK** – In this page you will learn how to use the SDK, but if you want to get more information about the calls, you can always see our [Close Partner API Documentation][partner-api-doc], which this SDK is a wrapper of.

## Quick Examples

### Create the Close SDK client

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

### Import tickets using the Close App
#### Import ticket with required information
```php
<?php
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Exception\CloseSdkException;

try {  
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $ticketGroup = new TicketGroup('+31666111000');
  $productTitle = 'Singular entrance ticket';
  $scanCode = '1234567890123';
  $ticket = new Ticket(
      $scanCode,
      new EventTime(new DateTime('2022-10-10 20:00:00')),
      $productTitle
  );
  $ticketGroup->addTicket($ticket);
  // Call endpoint
  $sdk
    ->ticket()
    ->import($eventId, $ticketGroup);
} catch (CloseSdkException $e) {
    echo "The ticket has not been imported.\n";
    // We recommend to retry after a couple of seconds.
}
```
#### Import ticket with seat information
```php
<?php
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Exception\CloseSdkException;
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\SeatInfo;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $ticketGroup = new TicketGroup('+31666111000');
  $productTitle = 'Singular entrance ticket';
  $scanCode = '1234567890123';
  
  $ticket = new Ticket(
      $scanCode,
      new EventTime(new DateTime('2022-10-10 20:00:00')),
      $productTitle
  );

  $seatInfo = new SeatInfo()
    ->withChair('12')
    ->withEntrance('E')
    ->withRow('3')
    ->withSection('A');

  $ticket = $ticket->withSeatInfo($seatInfo);

  $ticketGroup->addTicket($ticket);
  // Call endpoint
  $sdk
    ->ticket()
    ->import($eventId, $ticketGroup);
} catch (CloseSdkException $e) {
    echo "The ticket has not been imported.\n";
    // We recommend to retry after a couple of seconds.
}
```
### Cancelling tickets

#### Cancel a ticket in the Close App

```php
<?php
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Exception\CloseSdkException;
use ClosePartnerSdk\Dto\TicketCancelDto;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $scanCode = 'ABCD';
  $phoneNumber = '+31631111111';
  $eventTime = new EventTime(new DateTime('2022-10-10 20:00:00'))
  $ticketCancelDto = new TicketCancelDto($scanCode, $phoneNumber, $eventTime);
  // Call cancel endpoint
  $sdk
    ->ticket()
    ->cancel($eventId, $ticketCancelDto);
} catch (CloseSdkException $e) {
    echo "The ticket has not been cancelled.\n";
    // We recommend to retry after a couple of seconds.
}
```

### Sending messages

#### Send a message to all users in a chat

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
    ->textMessage()
    ->sendToAllUsersForChat($eventId, $chatId, $message);
} catch (CloseSdkException $e) {
    echo "The text has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```

### Flow Properties

#### To set a value in the flow properties

```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $properties = [
    new ItemFlowProperty('vip', 'This is a great vip event!'),
    new ItemFlowProperty('promotion', 'This event has a special promotion'),
  ];
  
  $sdk->flowProperty()->setForAllUsersInAllChats(
    $eventId,
    $properties
  );
} catch (CloseSdkException $e) {
    echo "The property has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```

## Getting Help

Feel free to let us know if you have encountered any questions or problems using our SDK. We will try to make sure that we will get back to you as soon as possible.

* If you have questions that have not been answered in this documentation, please [contact us][contact-us].
* If you think that you may have found a bug, feel free to [open an issue][open-issue].

## Features

* Provides a very easy way to communicate with our [Partner API](https://partner.closeapi.nl/api/documentation) for all of the supported endpoints. This means that we always fetch the correct data based on your API credentials.
* It is built on the latest software, with the highest security standards and following the [PSR conventions](https://www.php-fig.org/psr/).
* You can provide your own Http client as a dependency of the client builder and providing the instance in the options object. You have an example above.
* We use [Guzzle](https://docs.guzzlephp.org/en/7.0/) to generate these requests, and we make use of its technology (async requests, middlewares, etc.).
* We provide a data structure f our domain that can be easily used by external PHP applications.
* We give back clear responses and exceptions in case something doesn't go as expected.

## Advanced features
1. In case you want to make usage of your own HttpClient, you can provide the implementation to the client builder when instantiating our SDK:

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\HttpClient\HttpClientBuilder;

// Instantiate the Close client using the client credentials given by Close
  return new CloseSdk(
       new Options([
            'client_builder' => new HttpClientBuilder($myownHttpClient),
            'client_id' => 'client_test',
            'client_secret' => 'client_test_secret',
       ])
  );
```
*Important: The client needs to implement the [PSR-7](https://www.php-fig.org/psr/psr-7/) conventions to be accepted by our SDK.*
2. In case you don't provide any instance, we use the discovery functionality from [HttpPlug](http://httplug.io/), which look up for an available implementation of `\Http\Client\HttpClient`.

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

