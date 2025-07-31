<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Profile;

use App\Shared\Presentation\Controller as Action;
use App\Account\Presentation\Request\UpdateProfileRequest;
use App\Account\Application\Profile\Update\UpdateProfileCommand;
use App\Account\Presentation\Responder\Profile\UpdateProfileResponder;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Response\MessageResponse;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class UpdateProfileAction extends Action
{
    /**
     * Handles formatting and returning the profile update response.
     * 
     * @var UpdateProfileResponder
     */
    private readonly UpdateProfileResponder $responder;

    /**
     * Constructs a new UpdateProfileAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new UpdateProfileResponder();
    }

    /**
     * Handles the profile update HTTP PUT request.
     *
     * @param UpdateProfileRequest $request
     * @return MessageResponse
     */
    #[Route(methods: 'PUT', uri: '/profile')]
    public function __invoke(UpdateProfileRequest $request): MessageResponse
    {
        $command = UpdateProfileCommand::fromRequest(
            request: $request
        );

        return $this->responder->respond(
            result: (bool) $this->commandBus->send(command: $command)
        );
    }
}
