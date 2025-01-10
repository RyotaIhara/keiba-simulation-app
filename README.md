# 以下のコマンドで環境を立ち上げることができる
docker-compose up -d

# ローカル環境では以下のurlにアクセス可能
http://localhost:8080/

# keiba_simulation_appのサーバーにログイン
## 以下のコマンドを実行
docker exec -it keiba_simulation_app bash

# CRUDの作成（上記でサーバにログインした状態で実行する）
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
