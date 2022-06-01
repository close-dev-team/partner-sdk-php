# Sending a message to one specific user in all chats for one event.
Sometimes one user can be a part of multiple chats for one event. In case you want to send a message to this user in all chats of which this user is a member for one specific event you can use the sendToUserInAllChats() operation.

##### example:
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\UserId;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $userId = new UserId('CLUSxxxxxxxxx');
  $message = 'This is the message to send';
  
  $sdk
    ->textMessage()
    ->sendToUserInAllChats($eventId, $userId, $text);
} catch (CloseSdkException $e) {
    echo "The text has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event. Always starts with "CLEV"|
|UserId| Identifies one specific user. Always starts with "CLUS"|
|Message| A string of text that you'd like to send.|

[Back to User Guide](/USERGUIDE.md#textmessage)