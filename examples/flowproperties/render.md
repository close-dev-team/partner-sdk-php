# Test a property for a specific user in a specific chat for an event.
Do you want to check what a specific property is for a specific user in a specific chat? Then use the render() operation. The function will return a filled in template string.

##### Example scenario:
Let's say you want to use the user's nickname in a message, but first you want to test if that property is set. Flowproperties can be used in templated text like this: "My name is {user.nickname}". 

Let's say user.nickname is set to "Jake". Then render(eventId, chatId, "My name is {user.nickname}") will return: "My name is Jake".

##### example:
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
    $userId = new UserId('CLUSxxxxxxxxx');
    $message = '{user.nickname}';

  $this->givenSdk()->flowProperty()->render($eventId, $chatId, $userId, $message);
} catch (CloseSdkException $e) {
    echo "The property has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|Properties|An array of ItemFlowProperties|
|ItemFlowProperty|A key-value pair.|

[Back to User Guide](/USERGUIDE.md#flowproperty)