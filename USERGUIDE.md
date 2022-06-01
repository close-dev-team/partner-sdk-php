# Close PHP SDK user guide
In this Close PHP SDK user guide we will explain and demonstrate the use-cases you will encounter when partnering with the Close app and their according SDK elements.

### Table of Contents:   
  - [About the close app:](#about-the-close-app)
    - [What is the Close app?](#what-is-the-close-app)
    - [Close system overview](#close-system-overview)
  - [Getting started](#getting-started)
  - [Examples:](#examples)
    - [Setting up the Close Client](#setting-up-the-close-php-sdk-client)
    - [Sending text messages](#textmessage)
    - [Importing and canceling tickets](#import-tickets-using-the-close-app)
    - [Setting or getting properties for users or events](#flowproperty)
  - [Getting Help](#getting-help)
  - [Useful terms (DTOs)](#useful-terms)
  
### About the Close app

#### What is the Close app?
The Close app enables businesses to connect to their visitors/clients in a hyper-personalised way. Close started out in the event industry where we allow visitors to receive their tickets, practical information and live-updates all in one spot: The Close app. 

Using the Close PHP SDK you can easily integrate your APIs and systems with our messaging technology. 

#### Close system overview
```mermaid
graph TD;
Close-App-->Event;
Event-->Chat;
Chat-->User;
Chat-->Flow-properties-Y;
User-->Flow-properties-X;
```
### Getting Started

For now it is only possible to start using the Close PHP SDK by getting in touch with us first. Get in touch with your contact person at Close or [contact us][contact-us] directly. 

1. **Get your credentials** – Before you begin, you need to already have an account with Close. If that is not the case, feel free to [contact us][contact-us].
2. **Minimum requirements** – In order to use the Close SDK, your system will need to meet the [minimum requirements][docs-requirements], which includes having **PHP >= 7.4**.
3. **Install the SDK** – The recommended way to use the Close SDK is by installing it with [Composer][composer]:

   ```
   composer require close-dev-team/partner-sdk-php
   ```

4. **Using the SDK** – In this page you will learn how to use the SDK, but if you want to get more information about the calls, you can always see our [Close Partner API Documentation][partner-api-doc], which this SDK is a wrapper of.



### Examples [Code & Use-cases]


#### Setting up the Close PHP SDK client.
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
Now that you have the Close Client setup you're ready to continue.

### Available classes:

#### TextMessage()
One of the core features of the Close PHP SDK is sending ultra personalised text messages to Close users. There are 4 operations available in the TextMessage class, with each a different use-case. 

| Operations | Use-case |
| -------- | ----------- |
|[sendToAllChatsForEvent(eventId, text)](/examples/text%20message/sendToAllChatsForEvent.md)| Use when you need to reach all users for an event|
|[sendToAllUsersForChat(eventId, chatId, text)](/examples/text%20message/sendToAllUsersForChat.md)|Use when you need to reach all users in one specific chat for an event |
|[sendToUserInChat(eventId, chatId, userId, text)](/examples/text%20message/sendToUserInChat.md)|Use when you need to reach one specific user, in a specific chat for an event|
|[sendToUserInAllChats(eventId, userId)](/examples/text%20message/sendToUserInAllChats.md)|Use when you need to reach one specific user in all chats for one event|

#### ticket()
The Close app can be used to provide digital tickets to event-visitors. Using the Close PHP SDK you can both import and cancel tickets. 


| Operation | Use-case |
| -------- | ----------- |
|[import(eventId,ticketgroup)](/examples/ticket/import.md)| Use when you want to import a ticket.|
|[cancel(eventId, ticketCancelDto)](/examples/ticket/cancel.md)|Use when you want to cancel a ticket.|


#### flowproperty()
In order to create a personalised messaging experience it can be needed to set or get a custom property for a user or event. You can do this using the flowproperty operations.


| Operation | Use-case |
| -------- | ----------- |
|**setForOneUserInOneChat(eventId,chatId, userId, itemFlowProperties)**| Set a property for one specific user in one specific chat for an event.|
|**setForAllUsersInAllChats(eventId, itemFlowProperties)**|Set a property for all users in all chats for an event.|
|**setForUserInAllChats(eventId, userId, itemFlowProperties)**|Set a property for one specific user in all chats for one specific event.|
|**getProperties(eventId, chatId, userId)**|Get an overview of all flowproperties of a specific user, in a specific chat for an event.|
|**render(eventId, chatId, userId, text)**|-|

---
#### Getting Help

Feel free to let us know if you have encountered any questions or problems using our SDK. We will try to make sure that we will get back to you as soon as possible.

* If you have questions that have not been answered in this documentation, please [contact us][contact-us].
* If you think that you may have found a bug, feel free to [open an issue][open-issue].



[contact-us]: devteam@thecloseapp.com
[partner-api-doc]: https://partner.closeapi.nl/api/documentation
[apache-license]: https://www.apache.org/licenses/LICENSE-2.0
[PSR]: https://www.php-fig.org/psr/

[open-issue]: https://github.com/close-dev-team/partner-sdk-php/issues/new/choose
[open-pr]: https://github.com/close-dev-team/partner-sdk-php/compare

[the-close-app]: http://thecloseapp.com
[guzzle]: http://guzzlephp.org
[composer]: http://getcomposer.org

