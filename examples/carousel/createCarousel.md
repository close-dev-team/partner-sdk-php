# Creating a carousel for an event.
Do you want to create a carousel for a specific event? Then you can use the createCarousel() operation.


##### example:
```php
<?php
use ClosePartnerSdk\CloseSdk;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Exception\CloseSdkException;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $name = 'Awesome Carousel';
  
  $sdk
    ->event()
    ->createCarousel($eventId, $name)
} catch (CloseSdkException $e) {
    // error
}
```
##### DTOs explained:
| DTO     | info                                                     |
|---------|----------------------------------------------------------|
| EventId | Identifies one specific event. Always starts with "CLEV" |
| Name    | Name of the carousel. Must be unique per event.          |

[Back to User Guide](/USERGUIDE.md#carousel)