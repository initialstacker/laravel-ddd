<?php declare(strict_types=1);

namespace App\Account\Presentation\Responder\Token;

use App\Account\Presentation\ResponseMetadata;
use App\Account\Presentation\Response\TokenResponse;
use App\Account\Presentation\AuthTokenData;
use Illuminate\Http\Response;

final class RefreshTokenResponder
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
     * Generate a response based on the refresh token issuance result.
     *
     * @param string|null $result
     * @return TokenResponse
     */
    public function respond(?string $result): TokenResponse
    {
        if (is_string(value: $result)) {
            $authTokenData = new AuthTokenData(
                refreshToken: $result
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
