# Setting a property for one user in one chat for one specific event.
Do you need to set a property for one users in one specific chat attending one specific event? Use the setForOneUserInOneChat() operation.

##### example:
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\ChatId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Dto\ItemFlowProperty;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
    // Define DTO structure
    $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
    $chatId = new ChatId('CLECxxxxx');
    $userId = new UserId('CLUSxxxxxxxxx');
    $properties = [
    new ItemFlowProperty('vip', 'This is a great vip event!'),
    new ItemFlowProperty('promotion', 'This event has a special promotion'),
  ];
  
  $this->givenSdk()->flowProperty()->setForOneUserInOneChat($eventId, $chatId, $userId, $properties);
} catch (CloseSdkException $e) {
    echo "The property has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|ChatId| Identifies one specific chat. Always starts with "CLEC"|
|UserId| Identifies one specific user. Always starts with "CLUS"|
|Properties|An array of ItemFlowProperties|
|ItemFlowProperty|A key-value pair.|

[Back to User Guide](/USERGUIDE.md#flowproperty)