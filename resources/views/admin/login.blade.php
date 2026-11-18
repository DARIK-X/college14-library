@php($words = require resource_path('lang/library.php'))
@php($t = $words[session('lang','kk')] ?? $words['kk'])
@extends('layouts.app')
@section('title','Вход администратора · №14 колледж')
@section('content')<section class="wrap admin-area"><div class="admin-card"><span class="section-kicker">№14 COLLEGE / ADMIN</span><h1>Личный кабинет</h1><p>Войдите, чтобы управлять библиотекой.</p><form method="POST" action="{{ route('admin.login') }}">@csrf<label>Email<input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"></label><label>Пароль<input type="password" name="password" required autocomplete="current-password"></label>@if($errors->any())<p class="error">{{ $errors->first() }}</p>@endif<button class="filter-btn">Войти →</button></form></div></section>@endsection
