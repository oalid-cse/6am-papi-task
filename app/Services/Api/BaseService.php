<?php

namespace App\Services\Api;

class BaseService
{
    protected function getPaginationDetails($resource) : array
    {
        $response = [
            'per_page' => $resource->perPage(),
            'from' => $resource->firstItem(),
            'to' => $resource->lastItem(),
            'total' => $resource->total(),
            'current_page' => $resource->currentPage(),
            'last_page' => $resource->lastPage(),
            'prev_page_url' => $resource->previousPageUrl(),
            'first_page_url' => $resource->url(1),
            'next_page_url' => $resource->nextPageUrl(),
            'last_page_url' => $resource->url($resource->lastPage())
        ];

        return $response;
    }

    protected function getPaginatedResource($resource, $formatedResource = null) {
        $data = $this->getPaginationDetails($resource);
        if($formatedResource != null) {
            $data['list'] = $formatedResource;
        } else {
            $data['list'] = $resource->getCollection();
        }

        return $data;
    }
}
