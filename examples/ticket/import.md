# Importing tickets
The Close app can be used to provide digital tickets to event-visitors. Using the Close PHP SDK you can both import and cancel tickets. 

To import tickets use the Ticket import operation. To read more about canceling tickets go [here](/examples/ticket/cancel.md).

##### example:
```php
<?php
use ClosePartnerSdk\Dto\Ticket;
use ClosePartnerSdk\Dto\EventId;
use ClosePartnerSdk\Dto\TicketGroup;
use ClosePartnerSdk\Dto\EventTime;
use ClosePartnerSdk\Exception\CloseSdkException;

try {  
  $eventId = new EventId('CLEV3BX47D58YCMERC6CGJ2L7xxx');
  $ticketGroup = new TicketGroup('+31666111000');
  $productTitle = 'Singular entrance ticket';
  $ticket = new Ticket(
      $scanCode,
      new EventTime(new DateTime('2022-10-10 20:00:00')),
      $productTitle
  );
  $ticketGroup->addTicket($ticket);
  // Call endpoint
  $sdk
    ->ticket()
    ->import($eventId, $ticketGroup);
} catch (CloseSdkException $e) {
    echo "The ticket has not been imported.\n";
    // We recommend to retry after a couple of seconds.
}
```
##### DTOs explained:
| DTO | info |
| -------- | ----------- |
|EventId| Identifies one specific event.|
|Ticket| A ticket for an event.|
|TicketGroup| A collection of tickets linked to telephone number of the ticket buyer.|

[Back to User Guide](/USERGUIDE.md#ticket)