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

<div class="form-group">
    <label for="code">コード</label>
    <input type="text" name="code" id="code" class="form-control" value="{{ $user->getCode() }}" required>
</div>
<div class="form-group">
    <label for="username">ユーザー名</label>
    <input type="text" name="username" id="username" class="form-control" value="{{ $user->getUsername() }}" required>
</div>
<div class="form-group">
    <label for="password">パスワード</label>
    <input type="password" name="password" id="password" class="form-control" value="" required>
</div>
<div class="form-group">
    <label for="password_confirm">パスワード（確認用）</label>
    <input type="password" name="password_confirm" id="password-confirm" class="form-control" value="" required>
</div>