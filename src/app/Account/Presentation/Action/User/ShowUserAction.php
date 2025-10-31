<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\User;

use App\Shared\Presentation\Controller as Action;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Response\ResourceResponse;
use App\Shared\Domain\Id\UserId;
use App\Account\Application\User\Show\ShowUserQuery;
use App\Account\Presentation\Responder\User\ShowUserResponder;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class ShowUserAction extends Action
{
    /**
     * Formats and returns the user response.
     *
     * @var ShowUserResponder
     */
    private readonly ShowUserResponder $responder;

    /**
     * Constructs a new ShowUserAction instance.
     *
     * @param QueryBusInterface $queryBus
     */
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
        $this->responder = new ShowUserResponder();
    }

    /**
     * Handles the user retrieval HTTP GET request.
     *
     * @param QueryBusInterface $queryBus
     * @return ResourceResponse
     */
    #[Route(methods: 'GET', uri: '/users/{userId}')]
    public function __invoke(UserId $userId): ResourceResponse
    {
        /** @var User|null $result */
        $result = $this->queryBus->ask(
            query: new ShowUserQuery(userId: $userId)
        );

        return $this->responder->respond(
            result: $result
        );
    }
}
