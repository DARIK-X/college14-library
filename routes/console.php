<?php
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
Artisan::command('library:create-admin {email} {name}', function (string $email, string $name) {
    if (User::where('email',$email)->exists()) { $this->error('Этот email уже зарегистрирован.'); return 1; }
    $password = Str::random(24);
    User::create(['name'=>$name,'email'=>$email,'password'=>Hash::make($password)]);
    $this->info('Администратор создан. Пароль: '.$password);
    $this->warn('Сохраните пароль: повторно он не показывается.');
    return 0;
})->purpose('Create a library administrator with a random password');
