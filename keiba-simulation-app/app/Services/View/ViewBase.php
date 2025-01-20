<?php

namespace App\Services\View;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\Base;

class ViewBase extends Base
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

}