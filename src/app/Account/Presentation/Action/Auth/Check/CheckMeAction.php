<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Auth\Check;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Response\ResourceResponse;
use App\Account\Presentation\Responder\Auth\Check\CheckMeResponder;
use App\Account\Application\Auth\Check\Me\CheckMeQuery;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;
use Illuminate\Http\Request as CheckMeRequest;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class CheckMeAction extends Action
{
    /**
     * Handles the authenticated user's identity check request.
     *
     * @var CheckMeResponder
     */
	private readonly CheckMeResponder $responder;

    /**
     * Constructs a new CheckMeAction instance.
     *
     * @param QueryBusInterface $queryBus
     */
	public function __construct(
		private readonly QueryBusInterface $queryBus
	) {
		$this->responder = new CheckMeResponder();
	}

    /**
     * Processes the GET request to verify the current authenticated user.
     *
     * @param CheckMeRequest $request
     * @return ResourceResponse
     */
    #[Route(methods: 'GET', uri: '/check-me')]
    public function __invoke(CheckMeRequest $request): ResourceResponse
    {
        $result = $this->queryBus->ask(
            query: new CheckMeQuery(request: $request)
        );
        
        return $this->responder->respond(result: $result);
    }
}
