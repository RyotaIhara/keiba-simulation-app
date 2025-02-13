﻿## 以下のコマンドで環境を立ち上げることができる
docker-compose up -d

## ローカル環境では以下のurlにアクセス可能
http://localhost:8080/

## keiba_simulation_appのサーバーにログインする方法
### 以下のコマンドを実行
docker exec -it keiba_simulation_app bash

## Laravelでのキャッシュクリア方法
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan view:cache

# WEBのCRUD作成手順（上記でサーバにログインした状態で実行する）
## CRUDに必要な設定を確認するコマンド
php artisan --version
php artisan migrate:status

## モデルの作成コマンド
php artisan make:model <モデル名（例：Item）>

## コントローラーの作成コマンド
php artisan make:controller <コントローラー名（例：ItemController）> --resource

## ルート設定
### routes/web.php を編集して、以下を追加します
use App\Http\Controllers\<コントローラー名>;

Route::resource('items', <コントローラー名>::class);

## ビューは下記に配置する
resources/views/

## URL
http://localhost:8080/<上記で設定した値(items)>


# バッチ処理作成の基本手順
## Artisanコマンドを作成
php artisan make:command BatchProcessCommand

## コマンドの編集
app/Console/Commands/BatchProcessCommand.phpを修正
```php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class BatchProcessCommand extends Command
{
    /**
     * コマンドのシグネチャ（コマンドの名前）
     */
    protected $signature = 'batch:process';

    /**
     * コマンドの説明
     */
    protected $description = 'このコマンドはバッチ処理を実行します';

    /**
     * コマンドのロジックを記述するメソッド
     */
    public function handle()
    {
        // バッチ処理のロジックをここに記述
        $this->info('バッチ処理を開始します...');

        // 例: データベースからレコードを取得して処理
        $records = \App\Models\YourModel::where('processed', false)->get();

        foreach ($records as $record) {
            // レコードを処理
            $record->processed = true;
            $record->save();
        }

        $this->info('バッチ処理が完了しました！');
    }
}
```

## コマンドの登録
app/Console/Kernel.phpを修正する
```php
protected $commands = [
    \App\Console\Commands\BatchProcessCommand::class,
];
```

## コマンドを実行
```bash
php artisan batch:process
```
※batch:process は、$signature に指定した名前です。

## スケジュール実行
### バッチ処理をスケジュール実行する場合、app/Console/Kernel.php の schedule メソッドに記述します。
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('batch:process')->dailyAt('02:00');
}
```

### CRONを設定する
```bash
* * * * * php /path/to/your-project/artisan schedule:run >> /dev/null 2>&1
```

# バッチ一覧
## レーススケジュールデータ作成
docker exec -it keiba_simulation_app php artisan app:batch-race-schedule-command
## レース情報データ作成
docker exec -it keiba_simulation_app php artisan app:batch-race-info-import-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'
## 払戻金データ作成
docker exec -it keiba_simulation_app php artisan app:batch-refund-amount-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'
## 投票データの払戻金を更新
docker exec -it keiba_simulation_app php artisan app:batch-update-voting-refund-amount-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'
## Netkeibaから推奨馬を取得する
docker exec -it keiba_simulation_app php artisan app:batch-get-expected-horse-netkeiba-command --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'
## シミュレーション
### シミュレーション01
docker exec -it keiba_simulation_app php artisan app:batch-simulation01-command  --userId=3 --fromRaceDate='2025-01-01' --toRaceDate='2025-01-01'
