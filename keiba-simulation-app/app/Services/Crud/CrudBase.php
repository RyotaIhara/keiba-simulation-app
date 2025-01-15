<?php

namespace App\Services\Crud;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\Base;

class CrudBase extends Base
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    function getValue(array $data, string $snakeKey, string $camelKey)
    {
        return $data[$snakeKey] ?? $data[$camelKey] ?? null;
    }

}