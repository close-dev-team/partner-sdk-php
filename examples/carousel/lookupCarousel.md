# Looking up a carousel in an event.
Do you want to look up a carousel name? Then you can use the lookupCarousel() operation.


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
    ->lookupCarousel($eventId, $name)
} catch (CloseSdkException $e) {
    // error
}
```
##### DTOs explained:
| DTO     | info                                                     |
|---------|----------------------------------------------------------|
| EventId | Identifies one specific event. Always starts with "CLEV" |
| Name    | Name of the carousel.                                    |   

[Back to User Guide](/USERGUIDE.md#carousel)