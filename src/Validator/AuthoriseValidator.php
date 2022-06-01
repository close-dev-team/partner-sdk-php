<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Validator;

use ClosePartnerSdk\Exception\MissingResponsePropertiesException;

class AuthoriseValidator extends ResponseValidator
{
    /**
     * @throws MissingResponsePropertiesException
     */
    public function validate(): void
    {
        $this->requireProperty('access_token');
        $this->requireProperty('expires_in');
    }
}