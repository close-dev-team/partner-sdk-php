# Get all properties of one specific user in one specific chat for an event.
Do you want to know the properties of one specific user? Then use the getProperties() operation. 

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
  
  $sdk
    ->textMessage()
    ->getProperties($eventId, $chatId, $userId):;
} catch (CloseSdkException $e) {
    echo "The properties could not be retrieved. \n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|UserId| Identifies one specific user. Always starts with "CLUS"|
|ChatId| Identifies one specific chat. Always starts with "CLEC"|

[Back to User Guide](/USERGUIDE.md#textmessage)