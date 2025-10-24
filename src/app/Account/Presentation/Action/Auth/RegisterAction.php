<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Auth;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Shared\Presentation\Response\MessageResponse;
use App\Account\Presentation\Responder\Auth\RegisterResponder;
use App\Account\Presentation\Request\RegisterRequest;
use App\Account\Application\Auth\Register\RegisterCommand;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(prefix: 'v1')]
final class RegisterAction extends Action
{
	/**
	 * Handles formatting and returning the registration response.
	 * 
     * @var RegisterResponder
     */
	private readonly RegisterResponder $responder;

    /**
     * Constructs a new RegisterAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
	public function __construct(
		private readonly CommandBusInterface $commandBus
	) {
		$this->responder = new RegisterResponder();
	}

    /**
     * Handles user registration by creating a new user.
     *
     * @param RegisterRequest $request
     * @return MessageResponse
     */
    #[Route(methods: 'POST', uri: '/register')]
	public function __invoke(
		RegisterRequest $request): MessageResponse
	{
		$command = RegisterCommand::fromRequest(
			request: $request
		);
		
		return $this->responder->respond(
			result: (bool) $this->commandBus->send(
				command: $command
			)
		);
	}
}
