<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Token;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\CommandBusInterface;
use App\Account\Application\Token\Issue\IssueTokenCommand;
use App\Account\Presentation\Response\TokenResponse;
use App\Account\Presentation\Responder\Token\IssueTokenResponder;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Illuminate\Http\Request;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class IssueTokenAction extends Action
{
	/**
     * Handles formatting and returning the token issuance response.
     * 
     * @var IssueTokenResponder
     */
    private readonly IssueTokenResponder $responder;

    /**
     * Constructs a new IssueTokenAction instance.
     *
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        $this->responder = new IssueTokenResponder();
    }

    /**
     * Handles the HTTP POST request to issue a new authentication token.
     *
     * @param Request $request
     * @return TokenResponse
     */
    #[Route(methods: 'POST', uri: '/issue-token')]
    public function __invoke(Request $request): TokenResponse
    {
        $result = $this->commandBus->send(
            command: IssueTokenCommand::fromRequest(
                request: $request
            )
        );
        
        return $this->responder->respond(result: $result);
    }
}
