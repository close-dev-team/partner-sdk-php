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
  $request = [
        "title" => "Special offer!",
        "image_id" => "CLIM05D3FA6NOOUFJUPC5B5Y7V0NS6",
        "text" => "First paragraph for {user.nickname}",
        "link" => "https =>//thecloseapp.com/",
        "push_notification_message" => "Click to see our special offers for you.",
        "button_text" => "O P E N",
        "open_link_in_app" => false,
        "detail_view_description_1" => "extra paragraph 1\nevent",
        "detail_view_title_2" => "Heading 2",
        "detail_view_description_2" => "extra paragraph 2"
  ];
  
  $sdk
    ->cardMessage()
    ->sendToUserInChat($eventId, $chatId, $userId, $request);
} catch (CloseSdkException $e) {
    echo "The card has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO     | info                                                     |
|---------|----------------------------------------------------------|
| EventId | Identifies one specific event. Always starts with "CLEV" |
| ChatId  | Identifies one specific chat. Always starts with "CLEC"  |
| UserId  | Identifies one specific user. Always starts with "CLUS"  |
| Request | An array of card details that you'd like to send.                                                         |

[Back to User Guide](/USERGUIDE.md#cardmessage)