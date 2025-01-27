<?php

namespace App\Services\General;

use App\Services\General\GeneralBase;
use DateTime;

class VotingRecordGeneral extends GeneralBase
{
    /** 流し形式でデータを整形する **/
    public function formatNagashiData($formatParams) {
        $paramsForInsertList = [];

        $raceInfoId   = $formatParams['raceInfoId'];
        $howToBuy     = $formatParams['howToBuyNagashi']; // 識別（単勝、複勝、etc）
        $howToNagashi = $formatParams['howToNagashi']; // 流し方（軸1頭 or 軸2頭）
        $shaft        = $formatParams['shaft']; // 軸
        $partner      = $formatParams['partner']; // 相手
        $votingAmount = $formatParams['votingAmountNagashi']; // 掛け金

        $tmpParamsForInsert = array(
            'race_info_id'      => $raceInfoId,
            'how_to_buy_mst_id' => $howToBuy,
            'voting_amount'     => $votingAmount,
            'refund_amount'     => 0,
            'created_at'        => new DateTime(date('Y-m-d H:i:s')),
            'updated_at'        => new DateTime(date('Y-m-d H:i:s'))
        );

        if (in_array($howToBuy, ['4','5','7'] )) {
            // 馬連 or 馬単 or ワイド
            $partnerList = explode(',', $partner);
            foreach ($partnerList as $partnerNum) {
                $tmpParamsForInsert['voting_uma_ban'] = $shaft . ',' . $partnerNum;

                $paramsForInsertList[] = $tmpParamsForInsert;
            }
        }
        else if (in_array($howToBuy, ['8'] )) {
            // 3連複
            // 一旦使わないので実装なし
        }
        else if (in_array($howToBuy, ['9'] )) {
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

}