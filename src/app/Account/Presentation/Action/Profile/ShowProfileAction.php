<?php declare(strict_types=1);

namespace App\Account\Presentation\Action\Profile;

use App\Shared\Presentation\Controller as Action;
use App\Account\Application\Profile\Show\ShowProfileQuery;
use App\Account\Presentation\Responder\Profile\ShowProfileResponder;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Response\ResourceResponse;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Route;

#[Prefix(prefix: 'v1')]
#[Middleware(middleware: 'auth:api')]
final class ShowProfileAction extends Action
{
    /**
     * Formats and returns the profile response.
     *
     * @var ShowProfileResponder
     */
    private readonly ShowProfileResponder $responder;

    /**
     * Constructs a new ProfileAction instance.
     *
     * @param QueryBusInterface $queryBus
     */
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
        $this->responder = new ShowProfileResponder();
    }

    /**
     * Handles the profile retrieval HTTP GET request.
     *
     * @return ResourceResponse
     */
    #[Route(methods: 'GET', uri: '/profile')]
    public function __invoke(): ResourceResponse
    {
        /** @var User|null $result */
        $result = $this->queryBus->ask(
            query: new ShowProfileQuery()
        );

        return $this->responder->respond(
            result: $result
        );
    }
}
