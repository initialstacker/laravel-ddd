<?php declare(strict_types=1);

namespace Initialstack\Paginator;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

/**
 * @extends \Illuminate\Pagination\LengthAwarePaginator<int, mixed>
 */
final class Paginator extends LengthAwarePaginator
{
   /**
     * Constructs a new paginator instance.
     *
     * @param array<int, mixed> $items
     * @param int $perPage
     */
    public function __construct(array $items, int $perPage = 10)
    {
        $page = Request::get(key: 'page', default: 1);

        if (!is_numeric(value: $page)) {
            $page = 1;
        }

        $currentPage = (int) $page;
        
        parent::__construct(
            items: $this->fromArray(items: $items, perPage: $perPage),
            total: count(value: $items),
            perPage: $perPage,
            currentPage: $currentPage,
            options: [
                'path' => Request::url(),
                'query' => Request::query()
            ]
        );
    }

    /**
     * Returns a slice of items for the current page.
     *
     * @param array<int, mixed> $items
     * @param int $perPage
     * 
     * @return array<int, mixed>
     */
    private function fromArray(array $items, int $perPage): array
    {
        $page = Request::get(key: 'page', default: 1);

        if (!is_numeric(value: $page)) {
            $page = 1;
        }

        $currentPage = (int) $page;

        return collect(value: $items)->slice(
            offset: ($currentPage - 1) * $perPage,
            length: $perPage
        )->all();
    }
}
