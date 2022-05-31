<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Exception\Auth;

use ClosePartnerSdk\Exception\CloseSdkException;

class InvalidCredentialsException extends \DomainException implements CloseSdkException
{

}