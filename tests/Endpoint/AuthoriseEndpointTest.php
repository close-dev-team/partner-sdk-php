<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Auth\AuthCredentials;
use ClosePartnerSdk\Exception\InvalidResponseJsonFormat;

class AuthoriseEndpointTest extends EndpointTestCase
{
    /** @test **/
    public function extract_access_token_from_response()
    {
        $clientId = '923e4785-077e-4523-9466-f2f298a398d4';
        $clientSecret = '4WNDmGIi8foQezA5y830oKeGaHY9DnooItUa555z';
        $accessToken = "Hello, I'm mister token.";
        $expiresIn = 86400;

        $this->mockResponseForClient([
            "token_type" => "Bearer",
            "expires_in" => $expiresIn,
            "access_token" => $accessToken,
        ]);

        $token = $this->givenSdk()
            ->authorise()
            ->withCredentials(
                new AuthCredentials(
                    $clientId,
                    $clientSecret
                )
            );

        self::assertEquals($accessToken, $token->getAccessToken());
        self::assertEquals($expiresIn, $token->getExpirationInSeconds());
    }

    /** @test **/
    public function inform_when_api_provides_a_wrong_json()
    {
        $clientId = '923e4785-077e-4523-9466-f2f298a398d4';
        $clientSecret = '4WNDmGIi8foQezA5y830oKeGaHY9DnooItUa555z';

        $this->mockResponseForClient('I am a teapot');

        $this->expectException(InvalidResponseJsonFormat::class);

        $this->givenSdk()
            ->authorise()
            ->withCredentials(
                new AuthCredentials(
                    $clientId,
                    $clientSecret
                )
            );
    }
}
