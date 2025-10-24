<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Auth;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Account\Presentation\Request\LoginRequest;
use App\Account\Presentation\Responder\Auth\LoginResponder;
use App\Account\Application\Auth\Login\LoginCommand;
use App\Account\Presentation\Response\TokenResponse;
use Spatie\RouteAttributes\Attributes\Route;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(prefix: 'v1')]
final class LoginAction extends Action
{
	/**
	 * Handles formatting and returning the login response.
	 * 
     * @var LoginResponder
     */
	private readonly LoginResponder $responder;

    /**
     * Constructs a new LoginAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
	public function __construct(
		private readonly CommandBusInterface $commandBus
	) {
		$this->responder = new LoginResponder();
	}

    /**
     * Handles the login HTTP POST request.
     *
     * @param LoginRequest $request
     * @return TokenResponse
     */
    #[Route(methods: 'POST', uri: '/login')]
    public function __invoke(LoginRequest $request): TokenResponse
    {
        /** @var array<string, string>|null $result */
        $result = $this->commandBus->send(
            command: LoginCommand::fromRequest(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
