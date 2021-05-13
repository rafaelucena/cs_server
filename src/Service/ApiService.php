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
    public function build($content, string $message = ''): array
    {
        switch ($this->type) {
            case self::LIST:
                return $this->buildList($content, $message);
            case self::GET:
                return $this->buildGet($content, $message);
            case self::POST:
                return $this->buildPost($content, $message);
            case self::PUT:
                return $this->buildPut($content, $message);
            case self::DELETE:
                return $this->buildDelete($content, $message);
        }
    }

    /**
     * @param array|Item[] $items
     *
     * @return array
     */
    private function buildList(array $items, string $message = ''): array
    {
        $response = [
            'success' => false,
            'data' => [
                'quantity' => 0,
                'list' => [],
            ],
        ];
        if (empty($message) === false) {
            $response['error'] = $message;
            return $response;
        }
        if (empty($items) === true) {
            $response['success'] = true;
            return $response;
        }

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
    private function buildGet(Item $item = null, string $message = ''): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];
        if (empty($message) === false) {
            $response['error'] = $message;
            return $response;
        }

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
    private function buildPost(Item $item = null, string $message = ''): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];
        if (empty($message) === false) {
            $response['error'] = $message;
            return $response;
        }

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
    private function buildPut(Item $item = null, string $message = ''): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];
        if (empty($message) === false) {
            $response['error'] = $message;
            return $response;
        }

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
    public function buildDelete(Item $item = null, string $message = ''): array
    {
        $response = [
            'success' => false,
            'data' => [],
        ];
        if (empty($message) === false) {
            $response['error'] = $message;
            return $response;
        }

        if ($item !== null && $item->getId() === null) {
            $response = [
                'success' => true,
                'data' => [],
            ];
        }

        return $response;
    }
}