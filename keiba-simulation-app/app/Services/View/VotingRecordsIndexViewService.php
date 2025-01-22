<?php

namespace App\Services\View;

use Doctrine\ORM\EntityManagerInterface;
use App\Entities\VotingRecordsIndexView;
use App\Services\View\ViewBase;

class VotingRecordsIndexViewService extends ViewBase
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /** voting_records_index_viewのデータ一覧を取得する **/
    public function getAllVotingRecordsIndexViewDatas($page, $pageSize, $searchForm)
    {
        $whereParams = [
            'raceDate' => $searchForm['raceDate'],
            'raceNum' => $searchForm['raceNum'],
            'jyoCd' => $searchForm['racecourse']
        ];
        return $this->entityManager->getRepository(VotingRecordsIndexView::class)->getAllVotingRecordsIndexViewDatas($page, $pageSize, $whereParams);
    }

    /** voting_records_idからvoting_records_index_viewテーブルのデータを取得する **/
    public function getVotingRecordsIndexViewData($id)
    {
        return $this->entityManager->getRepository(VotingRecordsIndexView::class)->find($id);
    }

    /** ユニークなカラムを指定してデータを1つ取得する **/
    public function getVotingRecordsIndexViewDataByUniqueColumn($whereParams) {
        return $this->entityManager->getRepository(VotingRecordsIndexView::class)->findOneBy($whereParams);
    }

}