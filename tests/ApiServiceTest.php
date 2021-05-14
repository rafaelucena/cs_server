<?php declare(strict_types=1);

use App\Entity\Item;
use App\Service\ApiService;
use PHPUnit\Framework\TestCase;

include_once 'TestTrait.php';

final class ApiServiceTest extends TestCase
{
    use TestTrait;

    private $item;

    public function setUp(): void
    {
        $this->item = new Item();
        $this->setProperty($this->item, 'id', 42);
        $this->item->setName('Banana');
        $this->item->setAmount(6);
    }

    public function testBuild()
    {
        $service = new ApiService(ApiService::LIST);
        $successListResponse = $service->build([$this->item]);
        $this->assertIsArray($successListResponse);
        $this->assertSame([
            'success' => true,
            'data' => [
                'quantity' => 1,
                'list' => [
                    $this->item->toArray(),
                ],
            ],
        ], $successListResponse);

        $service = new ApiService(ApiService::GET);
        $successGetResponse = $service->build($this->item);
        $this->assertIsArray($successGetResponse);
        $this->assertSame([
            'success' => true,
            'data' => $this->item->toArray(),
        ], $successGetResponse);

        $service = new ApiService(ApiService::POST);
        $successPostResponse = $service->build($this->item);
        $this->assertIsArray($successPostResponse);
        $this->assertSame([
            'success' => true,
            'data' => $this->item->toArray(),
        ], $successPostResponse);

        $service = new ApiService(ApiService::PUT);
        $successPutResponse = $service->build($this->item);
        $this->assertIsArray($successPutResponse);
        $this->assertSame([
            'success' => true,
            'data' => $this->item->toArray(),
        ], $successPutResponse);

        $service = new ApiService(ApiService::DELETE);
        $item = new Item();
        $successDeleteResponse = $service->build($item);
        $this->assertIsArray($successDeleteResponse);
        $this->assertSame([
            'success' => true,
            'data' => [],
        ], $successDeleteResponse);
    }

    public function testBuildList()
    {
        $service = new ApiService(ApiService::LIST);

        $successEmptyResponse = $this->callMethod($service, 'buildList', [[]]);
        $this->assertIsArray($successEmptyResponse);
        $this->assertSame([
            'success' => true,
            'data' => [
                'quantity' => 0,
                'list' => [],
            ],
        ], $successEmptyResponse);

        $errorResponse = $this->callMethod($service, 'buildList', [[], 'Amount cannot be negative.']);
        $this->assertIsArray($errorResponse);
        $this->assertSame([
            'success' => false,
            'data' => [
                'quantity' => 0,
                'list' => [],
            ],
            'error' => 'Amount cannot be negative.',
        ], $errorResponse);

        $successResponse = $this->callMethod($service, 'buildList', [[$this->item]]);
        $this->assertIsArray($successResponse);
        $this->assertSame([
            'success' => true,
            'data' => [
                'quantity' => 1,
                'list' => [
                    $this->item->toArray(),
                ]
            ],
        ], $successResponse);
    }

    public function testBuildGet()
    {
        $service = new ApiService(ApiService::GET);

        $emptyResponse = $this->callMethod($service, 'buildGet');
        $this->assertIsArray($emptyResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
        ], $emptyResponse);

        $errorResponse = $this->callMethod($service, 'buildGet', [null, 'Item was not found.']);
        $this->assertIsArray($errorResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
            'error' => 'Item was not found.',
        ], $errorResponse);

        $successResponse = $this->callMethod($service, 'buildGet', [$this->item]);
        $this->assertIsArray($successResponse);
        $this->assertSame([
            'success' => true,
            'data' => $this->item->toArray(),
        ], $successResponse);
    }

    public function testBuildPost()
    {
        $service = new ApiService(ApiService::POST);

        $emptyResponse = $this->callMethod($service, 'buildPost');
        $this->assertIsArray($emptyResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
        ], $emptyResponse);

        $errorResponse = $this->callMethod($service, 'buildPost', [null, 'Item was not found.']);
        $this->assertIsArray($errorResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
            'error' => 'Item was not found.',
        ], $errorResponse);

        $successResponse = $this->callMethod($service, 'buildPost', [$this->item]);
        $this->assertIsArray($successResponse);
        $this->assertSame([
            'success' => true,
            'data' => $this->item->toArray(),
        ], $successResponse);
    }

    public function testBuildPut()
    {
        $service = new ApiService(ApiService::PUT);

        $emptyResponse = $this->callMethod($service, 'buildPut');
        $this->assertIsArray($emptyResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
        ], $emptyResponse);

        $errorResponse = $this->callMethod($service, 'buildPut', [null, 'Item was not found.']);
        $this->assertIsArray($errorResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
            'error' => 'Item was not found.',
        ], $errorResponse);

        $successResponse = $this->callMethod($service, 'buildPut', [$this->item]);
        $this->assertIsArray($successResponse);
        $this->assertSame([
            'success' => true,
            'data' => $this->item->toArray(),
        ], $successResponse);
    }

    public function testBuildDelete()
    {
        $service = new ApiService(ApiService::DELETE);

        $emptyResponse = $this->callMethod($service, 'buildDelete');
        $this->assertIsArray($emptyResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
        ], $emptyResponse);

        $errorResponse = $this->callMethod($service, 'buildDelete', [null, 'Item was not found.']);
        $this->assertIsArray($errorResponse);
        $this->assertSame([
            'success' => false,
            'data' => [],
            'error' => 'Item was not found.',
        ], $errorResponse);

        $item = new Item();
        $successResponse = $this->callMethod($service, 'buildDelete', [$item]);
        $this->assertIsArray($successResponse);
        $this->assertSame([
            'success' => true,
            'data' => [],
        ], $successResponse);
    }
}
