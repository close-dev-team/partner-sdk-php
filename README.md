# Close SDK for PHP

[![Lastest Version](https://img.shields.io/github/v/tag/close-dev-team/partner-sdk-php)](https://github.com/close-dev-team/partner-sdk-php/releases)
[![Build Status](https://img.shields.io/github/workflow/status/close-dev-team/partner-sdk-php/run-automated-tests?style=flat)](https://github.com/close-dev-team/partner-sdk-php)
[![Apache 2 License](https://img.shields.io/github/license/close-dev-team/partner-sdk-php)](https://www.apache.org/licenses/LICENSE-2.0)

The **Close SDK for PHP** makes it easy for developers to communicate with [The Close App][the-close-app] in their PHP code. Get started really fast by [installing the SDK through Composer](#Getting-Started).

Jump To:
* [Getting Started](#Getting-Started)
* [Quick Examples](#Quick-Examples)
* [Getting Help](#Getting-Help)
* [Features](#Features)
* [Contributing](#Contributing)
* [Other Resources](#Resources)

## Getting Started

1. **Get your credentials** – Before you begin, you need to already have an account with Close. If that is not the case, feel free to [contact us][contact-us].
2. **Minimum requirements** – In order to use the Close SDK, your system will need to meet the [minimum requirements][docs-requirements], which includes having **PHP >= 7.4**.
3. **Install the SDK** – The recommended way to use the Close SDK is by installing it with [Composer][composer]:

   ```
   composer require close-dev-team/partner-sdk-php
   ```

4. **Using the SDK** – In this page you will learn how to use the SDK, but if you want to get more information about the calls, you can always see our [Close Partner API Documentation][partner-api-doc], which this SDK is a wrapper of.

## Quick Examples

### Create the Close SDK client

```php
<?php
// Require the Composer autoloader.
require 'vendor/autoload.php';

use ClosePartnerSdk\CloseSdk;

// Instantiate the Close client using the client credentials given by Close
  return new CloseSdk(
       new Options([
            'client_id' => 'client_test',
            'client_secret' => 'client_test_secret',
       ])
  );
```
In case that you want to make usage of a different Http Client, you can provide your own client builder in the options object.
```php
  return new CloseSdk(
       new Options([
            'client_builder' => new ClientBuilder($myOwnHttpClient),
            'client_id' => 'client_test',
            'client_secret' => 'client_test_secret',
       ])
  );
```

### Import tickets using the Close App

```php
<?php
try {
    $closeClient->importTickets([
        'info' => 'info',
    ]);
} catch (CloseClient\Exception\CloseException $e) {
    echo "The ticket has not been imported.\n";
    // We recommend to retry after a couple of seconds.
}
```

## Getting Help

Feel free to let us know if you have encountered any questions or problems using our SDK. We will try to make sure that we will get back to you as soon as possible.

* If you have questions that have not been answered in this documentation, please [contact us][contact-us].
* If you think that you may have found a bug, feel free to [open an issue][open-issue].

## Features

* Provides a very easy way to communicate with our [Partner API](https://partner.closeapi.nl/api/documentation) for all of the supported endpoints. This means that we always fetch the correct data based on your API credentials.
* It is built on the latest software, with the highest security standards and following the [PSR conventions](https://www.php-fig.org/psr/).
* You can provide your own Http client as a dependency of the client builder and providing the instance in the options object. You have an example above.
* We use [Curl Client](https://github.com/php-http/curl-client) to generate these requests, and we make use of its technology (async requests, middlewares, etc.).
* We provide a data structure f our domain that can be easily used by external PHP applications.
* We give back clear responses and exceptions in case something doesn't go as expected.

## Contributing

If you have ideas on how to improve our SDK, don't hesitate to [open an issue][open-issue] and let us know! 
If you already have code ready that would help us improve our system, you are free to [open a PR][open-pr]. All the extra help is highly appreciated!

## Resources

* [API Docs][partner-api-doc] – For more details about the parameters of each endpoint, validation and responses.
* [Website][the-close-app] – More information about Close and what we do.
* [Issues][open-issue] – Report issues and submit pull requests.
* [License][apache-license] – More information about our license.

[contact-us]: devteam@thecloseapp.com
[partner-api-doc]: https://partner.closeapi.nl/api/documentation
[apache-license]: https://www.apache.org/licenses/LICENSE-2.0
[PSR]: https://www.php-fig.org/psr/

[open-issue]: https://github.com/close-dev-team/partner-sdk-php/issues/new/choose
[open-pr]: https://github.com/close-dev-team/partner-sdk-php/compare

[the-close-app]: http://thecloseapp.com
[guzzle]: http://guzzlephp.org
[composer]: http://getcomposer.org

