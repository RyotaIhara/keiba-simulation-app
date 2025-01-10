<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item'; // テーブル名を指定

    protected $fillable = [
        'item_name',
        'amount',
        'count',
        'created_at',
        'updated_at'
    ];

    public $timestamps = false;  // タイムスタンプ機能を無効化
}
