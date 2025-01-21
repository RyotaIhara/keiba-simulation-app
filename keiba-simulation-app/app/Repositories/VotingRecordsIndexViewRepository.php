<?php

namespace App\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class VotingRecordsIndexViewRepository extends EntityRepository
{
    // ここにカスタムメソッドを追加できます

    public function getAllVotingRecordsIndexViewDatas($page, $pageSize)
    {
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder
            ->orderBy('v.votingRecordId', 'DESC')
            ->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

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
