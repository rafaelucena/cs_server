<?php declare(strict_types=1);

use App\Entity\Item;
use App\Service\ApiService;
use App\Service\RequestService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Collection;

include_once 'TestTrait.php';

final class RequestServiceTest extends TestCase
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

    public function testValidateRequest()
    {
        $request = new Request([
            'has_stock' => 'true',
            'has_more_than' => 4,
        ]);
        $service = new RequestService($request);
        $service->validateRequest();
        $this->assertTrue($service->getIsValid());

        $request = new Request([
            'has_stock' => 'abc',
            'has_more_than' => 4,
        ]);
        $service = new RequestService($request);
        $service->validateRequest();
        $this->assertFalse($service->getIsValid());
        $this->assertStringContainsString('The value you selected is not a valid choice.', $service->getErrors());

        $request = new Request([
            'has_stock' => 'true',
            'has_more_than' => 'abc',
        ]);
        $service = new RequestService($request);
        $service->validateRequest();
        $this->assertFalse($service->getIsValid());
        $this->assertStringContainsString('This value should be of type numeric.', $service->getErrors());
    }

    public function testGetConstraintGet()
    {
        $request = new Request();
        $service = new RequestService($request);

        /** @var Collection */
        $constraint = $this->callMethod($service, 'getConstraintGet');
        $this->assertIsObject($constraint);
        $this->assertArrayHasKey('has_stock', $constraint->getNestedContraints());
        $this->assertArrayHasKey('has_more_than', $constraint->getNestedContraints());
    }

    public function testGetConstraintPost()
    {
        $request = new Request();
        $service = new RequestService($request);

        /** @var Collection */
        $constraint = $this->callMethod($service, 'getConstraintPost');
        $this->assertIsObject($constraint);
        $this->assertArrayHasKey('name', $constraint->getNestedContraints());
        $this->assertArrayHasKey('amount', $constraint->getNestedContraints());
    }

    public function testGetConstraintPut()
    {
        $request = new Request();
        $service = new RequestService($request);

        /** @var Collection */
        $constraint = $this->callMethod($service, 'getConstraintPut');
        $this->assertIsObject($constraint);
        $this->assertArrayHasKey('name', $constraint->getNestedContraints());
        $this->assertArrayHasKey('amount', $constraint->getNestedContraints());
    }
}