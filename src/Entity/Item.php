<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=127)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return self
     */
    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $methods = \get_class_methods($this);
        $data = [];

        $useful = [];
        foreach ($methods as $method) {
            if (strpos($method, 'get') === 0) {
                $useful[] = $method;
                $field = substr($method, 3);
                $value = $this->{$method}();
                if (is_object($value)) {
                    continue;
                } elseif (is_bool($value)) {
                    $data[$field] = (int) $value;
                    continue;
                }

                $data[$field] = $value;
            }
        }

        return $data;
    }
}
