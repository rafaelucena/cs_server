<?php

namespace App\Service;

use App\Entity\Item;

class ApiService
{
    /**
     * @var string
     */
    private $type;

    public const LIST = 'list';
    public const GET = 'get';
    public const POST = 'post';
    public const PUT = 'put';
    public const DELETE = 'delete';

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $content
     *
     * @return array
     */
    public function build($content): array
    {
        switch ($this->type) {
            case self::LIST:
                return $this->buildList($content);
            case self::GET:
                return $this->buildGet($content);
            case self::POST:
                return $this->buildPost($content);
            case self::PUT:
                return $this->buildPut($content);
            case self::DELETE:
                return $this->buildDelete($content);
        }
    }

    /**
     * @param array|Item[] $items
     *
     * @return array
     */
    private function buildList(array $items): array
    {
        $response = [
            'success' => false,
            'data' => [
                'quantity' => 0,
                'list' => [],
            ],
        ];

        foreach ($items as $item) {
            $response['success'] = true;
            $response['data']['quantity']++;
            $response['data']['list'][] = $item->toArray();
        }

        return $response;
    }

    /**
     * @param Item $item
     *
     * @return array
     */
    private function buildGet(Item $item = null): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        if ($item !== null) {
            $response = [
                'success' => true,
                'data' => $item->toArray(),
            ];
        }

        return $response;
    }

    /**
     * @param Item $item
     *
     * @return array
     */
    private function buildPost(Item $item = null): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        if ($item !== null && $item->getId() !== null) {
            $response = [
                'success' => true,
                'data' => $item->toArray(),
            ];
        }

        return $response;
    }

    /**
     * @param Item $item
     *
     * @return array
     */
    private function buildPut(Item $item = null): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        if ($item !== null && $item->getId() !== null) {
            $response = [
                'success' => true,
                'data' => $item->toArray(),
            ];
        }

        return $response;
    }

    /**
     * @param Item $item
     *
     * @return array
     */
    public function buildDelete(Item $item = null): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];

        if ($item !== null && $item->getId() === null) {
            $response = [
                'success' => true,
                'data' => [],
            ];
        }

        return $response;
    }
}