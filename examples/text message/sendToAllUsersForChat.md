# Sending a message to all users in one specific chat.


##### example:
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
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|ChatId| Identifies one specific chat. Always starts with "CLEC"|
|Message| A string of text that you'd like to send.|

[Back to User Guide](/USERGUIDE.md)