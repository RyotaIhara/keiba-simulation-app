<?php

namespace App\Services\General;

use App\Services\General\GeneralBase;
use DateTime;

class VotingRecordGeneral extends GeneralBase
{
    /** 流し形式でデータを整形する **/
    public function formatNagashiData($formatParams) {
        $paramsForInsertList = [];

        $raceInfo     = $formatParams['raceInfo'];
        $nagashiVotingRecord = $formatParams['nagashiVotingRecord'];
        $howToBuy     = $formatParams['howToBuyMst']; // 識別（単勝、複勝、etc）
        //$howToNagashi = $formatParams['howToNagashi']; // 流し方（軸1頭 or 軸2頭）
        $shaft        = $formatParams['shaft']; // 軸
        $partner      = $formatParams['partner']; // 相手
        $votingAmount = $formatParams['votingAmountNagashi']; // 掛け金

        $howToBuyId = $howToBuy->getId();

        $tmpParamsForInsert = array(
            'race_info'         => $raceInfo,
            'how_to_buy_mst' => $howToBuy,
            'nagashi_voting_record' => $nagashiVotingRecord,
            'voting_amount'     => $votingAmount,
            'refund_amount'     => 0,
            'created_at'        => new DateTime(date('Y-m-d H:i:s')),
            'updated_at'        => new DateTime(date('Y-m-d H:i:s'))
        );

        if (in_array($howToBuyId, ['4','5','7'] )) {
            // 馬連 or 馬単 or ワイド
            $partnerList = explode(',', $partner);
            foreach ($partnerList as $partnerNum) {
                $tmpParamsForInsert['voting_uma_ban'] = $shaft . ',' . $partnerNum;

                $paramsForInsertList[] = $tmpParamsForInsert;
            }
        }
        else if (in_array($howToBuyId, ['8'] )) {
            // 3連複
            // 一旦使わないので実装なし
        }
        else if (in_array($howToBuyId, ['9'] )) {
            // 3連単
            // 一旦使わないので実装なし
        }

        return $paramsForInsertList;
    }

    /** 
     * フォーマットの配列が流しに対応しているかを判定
     * （例：識別（単勝、複勝、etc）が「流し」に対応しているものかなど）
     * 
     * return bool
     **/
    public function checkNagashiData($formatParams) {
        return True;
    }

    /** ボックス形式でデータを整形する **/
    public function formatBoxData($formatParams) {
        $paramsForInsertList = [];

        $raceInfo        = $formatParams['raceInfo'];
        $boxVotingRecord        = $formatParams['boxVotingRecord'];
        $howToBuy        = $formatParams['howToBuyMst']; // 識別（単勝、複勝、etc）
        $votingUmaBanBox = $formatParams['votingUmaBanBox']; // 対象馬
        $votingAmount    = $formatParams['votingAmountBox']; // 掛け金

        $howToBuyId = $howToBuy->getId();

        $tmpParamsForInsert = array(
            'race_info'         => $raceInfo,
            'how_to_buy_mst' => $howToBuy,
            'boxVotingRecord' => $boxVotingRecord,
            'voting_amount'     => $votingAmount,
            'refund_amount'     => 0,
            'created_at'        => new DateTime(date('Y-m-d H:i:s')),
            'updated_at'        => new DateTime(date('Y-m-d H:i:s'))
        );

        if (in_array($howToBuyId, ['4','5'] )) {
            // 馬連 or ワイド
            $votingUmaBanList = explode(',', $votingUmaBanBox);
            for ($i = 0; $i < count($votingUmaBanList); $i++) {
                for ($i = 0; $i < count($votingUmaBanList); $i++) {
                    for ($j = $i + 1; $j < count($votingUmaBanList); $j++) {
                        $tmpParamsForInsert['voting_uma_ban'] = $votingUmaBanList[$i] . ',' . $votingUmaBanList[$j];

                        $paramsForInsertList[] = $tmpParamsForInsert;
                    }
                }
            }
        }
        if (in_array($howToBuyId, ['7'] )) {
            // 馬単
            $votingUmaBanList = explode(',', $votingUmaBanBox);
            foreach ($votingUmaBanList as $i) {
                foreach ($votingUmaBanList as $j) {
                    if ($i !== $j) {
                        $tmpParamsForInsert['voting_uma_ban'] = $i . ',' . $j;

                        $paramsForInsertList[] = $tmpParamsForInsert;
                    }
                }
            }
        }
        else if (in_array($howToBuyId, ['8'] )) {
            // 3連複
            $votingUmaBanList = explode(',', $votingUmaBanBox);
            for ($i = 0; $i < count($votingUmaBanList); $i++) {
                for ($j = $i + 1; $j < count($votingUmaBanList); $j++) {
                    for ($k = $j + 1; $k < count($votingUmaBanList); $k++) {
                        $tmpParamsForInsert['voting_uma_ban'] = $votingUmaBanList[$i] . ',' . $votingUmaBanList[$j] . ',' . $votingUmaBanList[$k];

                        $paramsForInsertList[] = $tmpParamsForInsert;
                    }
                }
            }
        }
        else if (in_array($howToBuyId, ['9'] )) {
            // 3連単
            $votingUmaBanList = explode(',', $votingUmaBanBox);
            foreach ($votingUmaBanList as $i) {
                foreach ($votingUmaBanList as $j) {
                    foreach ($votingUmaBanList as $k) {
                        if ($i !== $j && $j !== $k && $i !== $k) { // 3つの数字がすべて異なる場合のみ
                            $tmpParamsForInsert['voting_uma_ban'] = $i . ',' . $j . ',' . $k;

                            $paramsForInsertList[] = $tmpParamsForInsert;
                        }
                    }
                }
            }
        }

        return $paramsForInsertList;
    }

    /** 
     * フォーマットの配列がボックスに対応しているかを判定
     * （例：識別（単勝、複勝、etc）が「ボックス」に対応しているものかなど）
     * 
     * return bool
     **/
    public function checkBoxData($formatParams) {
        return True;
    }

    /** フォーメーション形式でデータを整形する **/
    public function formatFormationData($formatParams) {
        $paramsForInsertList = [];

        $raceInfo      = $formatParams['raceInfo'];
        $formationVotingRecord        = $formatParams['formationVotingRecord'];
        $howToBuy      = $formatParams['howToBuyMst']; // 識別（単勝、複勝、etc）
        $votingUmaBan1 = $formatParams['votingUmaBan1']; // フォーメーションの1着
        $votingUmaBan2 = $formatParams['votingUmaBan2']; // フォーメーションの2着
        $votingUmaBan3 = $formatParams['votingUmaBan3']; // フォーメーションの3着
        $votingAmount  = $formatParams['votingAmountFormation']; // 掛け金

        $howToBuyId = $howToBuy->getId();

        $tmpParamsForInsert = array(
            'race_info'         => $raceInfo,
            'how_to_buy_mst' => $howToBuy,
            'formationVotingRecord' => $formationVotingRecord,
            'voting_amount'     => $votingAmount,
            'refund_amount'     => 0,
            'created_at'        => new DateTime(date('Y-m-d H:i:s')),
            'updated_at'        => new DateTime(date('Y-m-d H:i:s'))
        );

        if (in_array($howToBuyId, ['4','5','7'] )) {
            // 馬連 or 馬単 or ワイド
            $votingUmaBanList_1 = explode(',', $votingUmaBan1);
            $votingUmaBanList_2 = explode(',', $votingUmaBan2);
            foreach ($votingUmaBanList_1 as $umaBan_1) {
                foreach ($votingUmaBanList_2 as $umaBan_2) {
                    if ($umaBan_1 != $umaBan_2) {
                        $tmpParamsForInsert['voting_uma_ban'] = $umaBan_1 . ',' . $umaBan_2;

                        $paramsForInsertList[] = $tmpParamsForInsert;
                    }
                }
            }
        }
        else if (in_array($howToBuyId, ['8', '9'] )) {
            // 3連複 or 3連単
            $votingUmaBanList_1 = explode(',', $votingUmaBan1);
            $votingUmaBanList_2 = explode(',', $votingUmaBan2);
            $votingUmaBanList_3 = explode(',', $votingUmaBan3);
            foreach ($votingUmaBanList_1 as $umaBan_1) {
                foreach ($votingUmaBanList_2 as $umaBan_2) {
                    foreach ($votingUmaBanList_3 as $umaBan_3) {
                        if ($umaBan_1 != $umaBan_2 
                                && $umaBan_1 != $umaBan_3
                                && $umaBan_2 != $umaBan_3) {
                            $tmpParamsForInsert['voting_uma_ban'] = $umaBan_1 . ',' . $umaBan_2 . ',' . $umaBan_3;

                            $paramsForInsertList[] = $tmpParamsForInsert;
                        }
                    }
                }
            }
        }

        return $paramsForInsertList;
    }

    /** 
     * フォーマットの配列がフォーメーションに対応しているかを判定
     * （例：識別（単勝、複勝、etc）が「フォーメーション」に対応しているものかなど）
     * 
     * return bool
     **/
    public function checkFormationData($formatParams) {
        return True;
    }

}