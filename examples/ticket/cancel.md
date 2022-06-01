# Canceling tickets
The Close app can be used to provide digital tickets to event-visitors. Using the Close PHP SDK you can both import and cancel tickets. 

To cancel tickets use the Ticket cancel operation. To read more about importing tickets go [here](/examples/ticket/import.md).

##### example:
```php
<?php
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\Product;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Exception\CloseSdkException;
use ClosePartnerSdk\Dto\TicketCancelDto;

try {
  // Define DTO structure
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $scanCode = 'ABCD';
  $phoneNumber = '+31631111111';
  $eventTime = new EventTime(new DateTime('2022-10-10 20:00:00'))
  $ticketCancelDto = new TicketCancelDto($scanCode, $phoneNumber, $eventTime);
  // Call cancel endpoint
  $sdk
    ->ticket()
    ->cancel($eventId, $ticketCancelDto);
} catch (CloseSdkException $e) {
    echo "The ticket has not been cancelled.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event.|
|EventTime| The DateTime at wich the event is planned.|
|TicketCancelDTO| Used to identify a specific ticket to be canceled.|

[Back to User Guide](/USERGUIDE.md)