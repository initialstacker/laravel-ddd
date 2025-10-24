<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Auth\Password;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Response\MessageResponse;
use App\Account\Application\Auth\Password\Forgot\ForgotPasswordCommand;
use App\Account\Presentation\Responder\Auth\Password\ForgotPasswordResponder;
use App\Account\Presentation\Request\ForgotPasswordRequest;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix(prefix: 'v1')]
final class ForgotPasswordAction extends Action
{
    /**
     * Handles formatting and returning the forgot password response.
     * 
     * @var ForgotPasswordResponder
     */
    private readonly ForgotPasswordResponder $responder;

    /**
     * Constructs a new ForgotPasswordAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new ForgotPasswordResponder();
    }

    /**
     * Handles the forgot password HTTP POST request.
     *
     * @param ForgotPasswordRequest $request
     * @return MessageResponse
     */
    #[Route(methods: 'POST', uri: '/forgot-password')]
    public function __invoke(
        ForgotPasswordRequest $request): MessageResponse
    {
        $result = $this->commandBus->send(
            command: ForgotPasswordCommand::fromRequest(
                request: $request)
        );

        return $this->responder->respond(result: $result);
    }
}
