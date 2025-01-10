# Dockerfile
FROM php:8.2-fpm

# 必要なPHP拡張機能をインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリを設定
WORKDIR /var/www

# 必要な拡張機能のインストール
RUN docker-php-ext-install pdo pdo_mysql

# Laravelプロジェクトファイルをコンテナにコピー
COPY ./keiba-simulation-app /var/www

# 権限設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# Composerを使ってLaravelの依存関係をインストール
RUN composer install

# コンテナのポートを開放
EXPOSE 9000
