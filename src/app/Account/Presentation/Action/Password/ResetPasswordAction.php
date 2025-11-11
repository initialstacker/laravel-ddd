<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Password;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Response\MessageResponse;
use App\Account\Application\Password\Reset\ResetPasswordCommand;
use App\Account\Presentation\Responder\Password\ResetPasswordResponder;
use App\Account\Presentation\Request\ResetPasswordRequest;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix(prefix: 'v1')]
final class ResetPasswordAction extends Action
{
    /**
     * Handles formatting and returning the reset password response.
     * 
     * @var ResetPasswordResponder
     */
    private readonly ResetPasswordResponder $responder;

    /**
     * Constructs a new ResetPasswordAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new ResetPasswordResponder();
    }

    /**
     * Handles the reset password HTTP POST request.
     *
     * @param ResetPasswordRequest $request
     * @return MessageResponse
     */
    #[Route(methods: 'POST', uri: '/reset-password')]
    public function __invoke(
        ResetPasswordRequest $request): MessageResponse
    {
        $result = $this->commandBus->send(
            command: ResetPasswordCommand::fromRequest(
                request: $request
            )
        );

        return $this->responder->respond(result: $result);
    }
}
