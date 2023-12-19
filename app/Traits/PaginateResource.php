<?php

namespace App\Traits;

trait PaginateResource
{
    public function paginate(array $data = []): array
    {
        return [
            'collection' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'perPage' => (int) $this->perPage(),
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage()
            ],
        ] + $data;
    }
}
