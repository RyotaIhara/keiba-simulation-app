<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class VotingRecordsIndexViewRepository extends EntityRepository
{
    // ここにカスタムメソッドを追加できます

    public function getAllVotingRecordsIndexViewDatas($page, $pageSize, $whereParams)
    {
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder
            ->where('v.raceDate = :raceDate')
            ->orderBy('v.votingRecordId', 'DESC')
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize)
            ->setParameter('raceDate', $whereParams['raceDate']);

        if (!is_null($whereParams['raceNum'])) {
            $queryBuilder
            ->andWhere('v.raceNum = :raceNum')
            ->setParameter('raceNum', $whereParams['raceNum']);
        }

        if (!is_null($whereParams['jyoCd'])) {
            $queryBuilder
            ->andWhere('v.jyoCd = :jyoCd')
            ->setParameter('jyoCd', $whereParams['jyoCd']);
        }

        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query, true);

        return [
            'data' => iterator_to_array($paginator),
            'totalItems' => count($paginator),
            'currentPage' => $page,
            'totalPages' => ceil(count($paginator) / $pageSize),
        ];
    }
}
