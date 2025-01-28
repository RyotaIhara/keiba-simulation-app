<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '競馬シミュレーションシステム')</title>
    
    <!-- BootstrapのCSSを使用（必要であれば他のCSSフレームワークも可能） -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @php
        $authGeneralService = new \App\Services\General\AuthGeneral();
    @endphp

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                @if ($authGeneralService->isLogin())
                    <li class="nav-item">
                        <a class="nav-link" href="#">こんにちは、{{ $authGeneralService->getSessionUserName() }} さん！</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">ログアウト</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">ログイン</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('racecourse_mst.index') }}">競馬場一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.index') }}">ユーザー</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('voting_record.index') }}">投票</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('voting_record.totalling') }}">集計</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- フラッシュメッセージの表示 -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- エラーメッセージ -->
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- メインコンテンツを表示 -->
        @yield('content')
    </div>

    <!-- 必要なJavaScriptを読み込み -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
