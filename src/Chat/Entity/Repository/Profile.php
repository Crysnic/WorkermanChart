<?php

declare(strict_types=1);

namespace Chat\Entity\Repository;

/**
 * Class Profile
 * @package Chat\Entity\Repository
 */
class Profile
{
    /**
     * @var string
     */
    private $name;

    /**
     * Profile constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
