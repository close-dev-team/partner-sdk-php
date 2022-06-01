# Sending a message to one specific user in one specific chat for an event.
If you want to send a message to one specific user in one specific chat for an event you can use the sendToUserInChat() operation.

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
  $message = 'This is the message to send';
  
  $sdk
    ->textMessage()
    ->sendToUserInChat($eventId, $chatId, $userId, $message);
} catch (CloseSdkException $e) {
    echo "The text has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|ChatId| Identifies one specific chat. Always starts with "CLEC"|
|UserId| Identifies one specific user. Always starts with "CLUS"|
|Message| A string of text that you'd like to send.|

[Back to User Guide](/USERGUIDE.md)