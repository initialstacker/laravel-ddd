<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder;

use App\Account\Presentation\ResponseMetadata;
use App\Account\Presentation\TokenResponse;
use App\Account\Presentation\AuthTokenData;
use Illuminate\Http\Response;

final class LoginResponder
{
    /**
     * Holds the response metadata such as request ID and timestamp.
     * 
     * @var ResponseMetadata
     */
    private ResponseMetadata $metadata;

    public function __construct()
    {
        $this->metadata = new ResponseMetadata();
    }

    /**
     * Generate a response based on the login token generation result.
     *
     * @param array<string, string>|null $result
     * @return TokenResponse
     */
    public function respond(?array $result): TokenResponse
    {
        if (!blank(value: $result)) {
            $authTokenData = new AuthTokenData(
                accessToken: $result['access_token'],
                refreshToken: $result['refresh_token']
            );

            return new TokenResponse(
                status: Response::HTTP_OK,
                data: $authTokenData,
                metadata: $this->metadata
            );
        }

        return new TokenResponse(
            status: Response::HTTP_BAD_REQUEST,
            data: new AuthTokenData(
                message: 'Failed to generate token.'
            ),
            metadata: $this->metadata
        );
    }
}
