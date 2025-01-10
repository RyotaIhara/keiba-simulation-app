<?php

namespace App\Services;

use App\Entities\Item;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllItems()
    {
        return $this->entityManager->getRepository(Item::class)->findAll();
    }

    public function getItem($id)
    {
        return $this->entityManager->getRepository(Item::class)->find($id);
    }

    public function createItem(array $data)
    {
        $item = new Item();
        $item->setItemName($data['item_name']);
        $item->setAmount($data['amount']);
        $item->setCount($data['count']);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function updateItem($id, array $data)
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);
        $item = $itemRepository->find($id);

        $item->setItemName($data['item_name']);
        $item->setAmount($data['amount']);
        $item->setCount($data['count']);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function deleteItem($id)
    {
        $itemRepository = $this->entityManager->getRepository(Item::class);
        $item = $itemRepository->find($id);

        if ($item) {
            $this->entityManager->remove($item);
            $this->entityManager->flush();
        }
    }

}
