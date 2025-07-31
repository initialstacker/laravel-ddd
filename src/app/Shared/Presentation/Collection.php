<?php declare(strict_types=1);

namespace App\Shared\Presentation;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Request;

/**
 * @property \Illuminate\Pagination\LengthAwarePaginator<int, mixed> $resource
 */
abstract class Collection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $pagination = $this->resource;
        
        return [
            ...$this->collection,
            'meta' => [
                'current_page' => $pagination->currentPage(),
                'from' => $pagination->firstItem(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'to' => $pagination->lastItem(),
                'total' => $pagination->total(),
            ],
            'links' => [
                'first' => $pagination->url(page: 1),
                'last' => $pagination->url(
                    page: $pagination->lastPage()
                ),
                'prev' => $pagination->previousPageUrl(),
                'next' => $pagination->nextPageUrl(),
            ],
        ];
    }
}
