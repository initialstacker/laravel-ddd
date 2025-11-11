<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Check;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Account\Application\Check\Auth\CheckAuthQuery;
use App\Account\Presentation\Responder\Check\CheckAuthResponder;
use App\Account\Presentation\Response\AuthResponse;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Illuminate\Http\Request as CheckAuthRequest;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class CheckAuthAction extends Action
{
	/**
     * Handles the request to verify user authentication via API.
     *
     * @var CheckAuthResponder
     */
    private readonly CheckAuthResponder $responder;

    /**
     * Constructs a new CheckAuthAction instance.
     *
     * @param QueryBusInterface $queryBus
     */
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
        $this->responder = new CheckAuthResponder();
    }

    /**
     * Processes the GET request to check user authentication status.
     *
     * @param CheckAuthRequest $request
     * @return AuthResponse
     */
    #[Route(methods: 'GET', uri: '/check-auth')]
    public function __invoke(CheckAuthRequest $request): AuthResponse
    {
        $result = $this->queryBus->ask(
            query: new CheckAuthQuery(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
