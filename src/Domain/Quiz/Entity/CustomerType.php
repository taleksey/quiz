<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "customer_type")]
class CustomerType
{
    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 20)]
    private string $name;

    /**
     * @var bool
     */
    #[ORM\Column(name:"show", type: 'boolean', options: [
        "default" => 1
    ])]
    private bool $show = true;

    /**
     * @var bool
     */
    #[ORM\Column(name:"add", type: 'boolean', options: [
        "default" => 1
    ])]
    private bool $add;

    /**
     * @var bool
     */
    #[ORM\Column(name:"edit", type: 'boolean', options: [
        "default" => 1
    ])]
    private bool $edit;

    /**
     * @var Collection<int, Customer>
     */
    #[ORM\OneToMany(mappedBy: "customerType", targetEntity: "Customer")]
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isShow(): bool
    {
        return $this->show;
    }

    /**
     * @param bool $show
     */
    public function setShow(bool $show): void
    {
        $this->show = $show;
    }

    /**
     * @return bool
     */
    public function isAdd(): bool
    {
        return $this->add;
    }

    /**
     * @param bool $add
     */
    public function setAdd(bool $add): void
    {
        $this->add = $add;
    }

    /**
     * @return bool
     */
    public function isEdit(): bool
    {
        return $this->edit;
    }

    /**
     * @param bool $edit
     */
    public function setEdit(bool $edit): void
    {
        $this->edit = $edit;
    }

    /**
     * @return ArrayCollection<int, Customer>|Collection<int, Customer>
     */
    public function getCustomers(): ArrayCollection|Collection
    {
        return $this->customers;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomers(Customer $customer): void
    {
        $this->customers->add($customer);
    }
}
