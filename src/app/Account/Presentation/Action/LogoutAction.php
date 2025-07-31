<?php declare(strict_types=1);

namespace App\Account\Presentation\Action;

use App\Shared\Presentation\Controller as Action;
use App\Account\Application\Logout\LogoutQuery;
use App\Account\Presentation\Responder\LogoutResponder;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Response\MessageResponse;
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
     * @param QueryBusInterface $queryBus
     */
    public function __construct(
        private readonly QueryBusInterface $queryBus
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
        return $this->responder->respond(
            result: (bool) $this->queryBus->ask(
                query: new LogoutQuery()
            )
        );
    }
}
