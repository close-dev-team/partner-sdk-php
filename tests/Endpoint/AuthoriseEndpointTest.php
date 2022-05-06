<?php
declare(strict_types=1);

namespace ClosePartnerSdk\Tests\Endpoint;

use ClosePartnerSdk\Dto\AuthCredentials;
use ClosePartnerSdk\Exception\Auth\InvalidCredentialsException;
use ClosePartnerSdk\Exception\ConnectionException;
use ClosePartnerSdk\Exception\InvalidResponseJsonFormat;
use Http\Client\Common\Exception\ClientErrorException;
use Laminas\Diactoros\Request;
use PHPUnit\Framework\MockObject\MockObject;

class AuthoriseEndpointTest extends EndpointTestCase
{
    /** @test **/
    public function extract_access_token_from_response()
    {
        $clientId = '923e4785-077e-4523-9466-f2f298a398d4';
        $clientSecret = '4WNDmGIi8foQezA5y830oKeGaHY9DnooItUa555z';
        $accessToken = "Hello, I'm mister token.";
        $expiresIn = 86400;

        $this->mockClient->addResponse(
            $this->mockResponse([
                "token_type" => "Bearer",
                "expires_in" => $expiresIn,
                "access_token" => $accessToken,
            ])
        );

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

        $this->mockClient->addResponse(
            $this->mockResponse('I am a teapot')
        );

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

    /** @test **/
    public function inform_when_credentials_are_invalid()
    {
        $clientId = '923e4785-077e-4523-9466-f2f298a398d4';
        $clientSecret = '4WNDmGIi8foQezA5y830oKeGaHY9DnooItUa555z';
        /** @var MockObject $response */
        $response = $this->mockResponse([]);
        $response
            ->method('getStatusCode')
            ->willReturn(401);

        $this->mockClient->addException(
            new ClientErrorException(
                'Forbidden',
                new Request(),
                $response
            )
        );

        $this->expectException(InvalidCredentialsException::class);

        $this->givenSdk()
            ->authorise()
            ->withCredentials(
                new AuthCredentials(
                    $clientId,
                    $clientSecret
                )
            );
    }

    /** @test **/
    public function inform_when_there_is_a_connection_problem()
    {
        $clientId = '923e4785-077e-4523-9466-f2f298a398d4';
        $clientSecret = '4WNDmGIi8foQezA5y830oKeGaHY9DnooItUa555z';

        $this->mockClient->addException(
            new \Exception('Something wrong happened'),
        );

        $this->expectException(ConnectionException::class);

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
