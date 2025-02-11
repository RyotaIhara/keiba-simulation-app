<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    /**
     * 現在のオートインクリメント値を取得する
     */
    protected function getAutoIncrementValue(string $table)
    {
        $result = DB::select("SHOW TABLE STATUS LIKE '{$table}'");
        return $result[0]->Auto_increment ?? null;
    }


    /**
     * オートインクリメント値を設定する
     */
    protected function setAutoIncrementValue(string $table, int $value)
    {
        DB::statement("ALTER TABLE $table AUTO_INCREMENT = $value");
    }
}
