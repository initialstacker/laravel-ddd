<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Profile;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Account\Application\Profile\Delete\DeleteProfileCommand;
use App\Account\Presentation\Responder\Profile\DeleteProfileResponder;
use App\Shared\Presentation\Response\MessageResponse;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class DeleteProfileAction extends Action
{
    /**
     * Handles formatting and returning the profile delete response.
     * 
     * @var DeleteProfileResponder
     */
    private readonly DeleteProfileResponder $responder;

    /**
     * Constructs a new DeleteProfileAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new DeleteProfileResponder();
    }

    /**
     * Handles the profile delete HTTP DELETE request.
     *
     * @param Request $request
     * @return MessageResponse
     */
    #[Route(methods: 'DELETE', uri: '/profile')]
    public function __invoke(Request $request): MessageResponse
    {
        $command = DeleteProfileCommand::fromRequest(request: $request);

        return $this->responder->respond(
            result: $this->commandBus->send(command: $command)
        );
    }
}
