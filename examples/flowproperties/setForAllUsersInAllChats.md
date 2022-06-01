# Setting a property for all users in all chats for one specific event.
Do you need to set a property for all users attending one specific event? Use the setForAllUsersInAllChats() operation.


##### example:
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
  
  $this->givenSdk()->flowProperty()->setForAllUsersInAllChats(
    $eventId,
    $properties
  );
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