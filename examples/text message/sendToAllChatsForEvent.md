# Sending a message to all users in all chats for one event.
Do you want to send a message to all users going to a specific event? Then you can use the sendToAllChatsForEvent() operation.


##### example:
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
    ->textMessage()
    ->sendToAllChatsForEvent($eventId, $message)
} catch (CloseSdkException $e) {
    echo "The text has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|Message| A string of text that you'd like to send.|

[Back to User Guide](/USERGUIDE.md#textmessage)