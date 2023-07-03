# Sending a message to all users in one specific chat.
Do you want to send a message to everyone in one specific chat? Then you can use the sendToAllUsersForChat() operation.


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
    ->sendToAllUsersForChat($eventId, $chatId, $request);
} catch (CloseSdkException $e) {
    echo "The card has not been sent.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO     | info |
|---------| ----------- |
| EventId | Identifies one specific event. Always starts with "CLEV"|
| ChatId  | Identifies one specific chat. Always starts with "CLEC"|
| Request | An array of card details that you'd like to send.|

[Back to User Guide](/USERGUIDE.md#cardmessage)