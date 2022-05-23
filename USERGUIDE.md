# Close PHP SDK user guide


In this Close PHP SDK user guide we will explain and demonstrate the use-cases you will encounter when partnering with the Close app. 

---

Table of Contents:  

- [Close PHP SDK user guide](#close-php-sdk-user-guide)
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

1. **Get your credentials** – Before you begin, you need to already have an account with Close. If that is not the case, feel free to [contact us][contact-us].
2. **Minimum requirements** – In order to use the Close SDK, your system will need to meet the [minimum requirements][docs-requirements], which includes having **PHP >= 7.4**.
3. **Install the SDK** – The recommended way to use the Close SDK is by installing it with [Composer][composer]:

   ```
   composer require close-dev-team/partner-sdk-php
   ```

4. **Using the SDK** – In this page you will learn how to use the SDK, but if you want to get more information about the calls, you can always see our [Close Partner API Documentation][partner-api-doc], which this SDK is a wrapper of.

---

## *Examples*


### Authorise
To be able to use the Close PHP SDK you will need to authorise with your credentials first. 

```php
Put the code in here
```

### Send text Message
One of the core features of the Close PHP SDK is sending ultra personalised text messages to Close users. There are 4 endpoints available for 4 different use-cases. 

| Endpoint | Use-case |
| -------- | ----------- |
|**toAllChatsForShow(eventId, text)**| Use when you need to reach all users for an event|
|**toAllUsersForChat(eventId, chatId, text)**|Use when you need to reach all users in one specific chat for an event |
|**toUserInChat(eventId, chatId, userId, text)**|Use when you need to reach one specific user, in a specific chat for an event|
|**toUserInAllChats(eventId, userId)**|Use when you need to reach one specific user in all chats for one event|

#### To all users in all chats for an event
```php
Put the code in here
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

