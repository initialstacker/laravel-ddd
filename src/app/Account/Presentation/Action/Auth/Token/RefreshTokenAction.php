<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Auth\Token;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Account\Application\Auth\Token\Refresh\RefreshTokenCommand;
use App\Account\Presentation\Response\TokenResponse;
use App\Account\Presentation\Responder\Auth\Token\RefreshTokenResponder;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class RefreshTokenAction extends Action
{
    /**
     * Handles formatting and returning the token refresh response.
     *
     * @var RefreshTokenResponder
     */
    private readonly RefreshTokenResponder $responder;

    /**
     * Constructs a new RefreshTokenAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new RefreshTokenResponder();
    }

    /**
     * Handles the HTTP POST request to refresh authentication tokens.
     *
     * @param Request $request
     * @return TokenResponse
     */
    #[Route(methods: 'POST', uri: '/refresh-token')]
    public function __invoke(Request $request): TokenResponse
    {
        $result = $this->commandBus->send(
            command: RefreshTokenCommand::fromRequest(
                request: $request
            )
        );
        
        return $this->responder->respond(result: $result);
    }
}
