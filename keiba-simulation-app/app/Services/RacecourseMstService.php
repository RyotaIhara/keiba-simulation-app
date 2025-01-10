<?php

namespace App\Services;

use App\Entities\RacecourseMst;
use Doctrine\ORM\EntityManagerInterface;

class RacecourseMstService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllRacecourseMsts()
    {
        return $this->entityManager->getRepository(RacecourseMst::class)->findAll();
    }

    public function getRacecourseMst($id)
    {
        return $this->entityManager->getRepository(RacecourseMst::class)->find($id);
    }

    public function createRacecourseMst(array $data)
    {
        $racecourseMst = new RacecourseMst();

        $racecourseMst->setJyoCd($data['jyo_cd']);
        $racecourseMst->setRacecourseName($data['racecourse_name']);

        $this->entityManager->persist($racecourseMst);
        $this->entityManager->flush();

    }

    public function updateRacecourseMst($id, array $data)
    {
        $racecourseMstRepository = $this->entityManager->getRepository(RacecourseMst::class);
        $racecourseMst = $racecourseMstRepository->find($id);

        $racecourseMst->setJyoCd($data['jyo_cd']);
        $racecourseMst->setRacecourseName($data['racecourse_name']);

        $this->entityManager->persist($racecourseMst);
        $this->entityManager->flush();
    }

    public function deleteRacecourseMst($id)
    {
        $racecourseMstRepository = $this->entityManager->getRepository(RacecourseMst::class);
        $racecourseMst = $racecourseMstRepository->find($id);

        if ($racecourseMst) {
            $this->entityManager->remove($racecourseMst);
            $this->entityManager->flush();
        }
    }

}
