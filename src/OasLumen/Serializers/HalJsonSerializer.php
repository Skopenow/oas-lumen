<?php

namespace DanBallance\OasLumen\Serializers;

use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Pagination\CursorInterface;

class HalJsonSerializer extends SerializerAbstract
{
    public function getContentType(): string
    {
        return 'application/hal+json';
    }

    public function collection($resourceKey, array $data)
    {
        return array_map(
            function($item) use ($resourceKey) {
                return $this->item($resourceKey, $item);
            },
            $data
        );
    }

    public function item($resourceKey, array $data)
    {
        $links = $data['links'];
        unset($data['links']);
        foreach ($links as $link) {
            if (!isset($data['_links'])) {
                $data['_links'] = [];
            }
            $data['_links'][$link['rel']] = [
                'href' => $link['uri']
            ];
        }
        return $data;
    }

    public function null()
    {

    }

    public function includedData(ResourceInterface $resource, array $data)
    {

    }

    public function meta(array $meta)
    {
        return [];
    }

    public function paginator(PaginatorInterface $paginator)
    {

    }

    public function cursor(CursorInterface $cursor)
    {

    }
}
