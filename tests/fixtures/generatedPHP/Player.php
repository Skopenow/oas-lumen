<?php

namespace DanBallance\OasLumen\Tests\fixtures\generatedPHP;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="player")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /** @ORM\Column(type="string") */
    protected $name;

    /** @ORM\Column(type="integer") */
    protected $age;

    /** @ORM\Column(type="boolean") */
    protected $isOnline;

    public function __construct($name = null, $age = null, $isOnline = null)
    {
        $this->name = $name;
        $this->age = $age;
        $this->isOnline = $isOnline;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getIsOnline()
    {
        return $this->isOnline;
    }

    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;
    }
}
