<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Auth;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Response\MessageResponse;
use App\Account\Presentation\Responder\Auth\LogoutResponder;
use App\Account\Application\Auth\Logout\LogoutCommand;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class LogoutAction extends Action
{
	/**
     * Handles formatting and returning the logout response.
     * 
     * @var LogoutResponder
     */
    private readonly LogoutResponder $responder;

    /**
     * Constructs a new LogoutAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new LogoutResponder();
    }

    /**
     * Handles the logout HTTP POST request.
     *
     * @param Request $request
     * @return MessageResponse
     */
    #[Route(methods: 'POST', uri: '/logout')]
    public function __invoke(Request $request): MessageResponse
    {
        $result = $this->commandBus->send(
            command: LogoutCommand::fromRequest(
                request: $request
            )
        );

        return $this->responder->respond(result: $result);
    }
}
