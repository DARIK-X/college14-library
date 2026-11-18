<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $t) { $t->id(); $t->string('name'); $t->string('email')->unique(); $t->timestamp('email_verified_at')->nullable(); $t->string('password'); $t->rememberToken(); $t->timestamps(); });
        Schema::create('password_reset_tokens', function (Blueprint $t) { $t->string('email')->primary(); $t->string('token'); $t->timestamp('created_at')->nullable(); });
        Schema::create('sessions', function (Blueprint $t) { $t->string('id')->primary(); $t->foreignId('user_id')->nullable()->index(); $t->string('ip_address',45)->nullable(); $t->text('user_agent')->nullable(); $t->longText('payload'); $t->integer('last_activity')->index(); });
        Schema::create('cache', function (Blueprint $t) { $t->string('key')->primary(); $t->mediumText('value'); $t->integer('expiration'); });
        Schema::create('cache_locks', function (Blueprint $t) { $t->string('key')->primary(); $t->string('owner'); $t->integer('expiration'); });
        Schema::create('books', function (Blueprint $t) { $t->id(); $t->string('title'); $t->string('author'); $t->text('description')->nullable(); $t->string('category'); $t->string('language',2)->default('kk'); $t->unsignedSmallInteger('year')->nullable(); $t->string('file_path'); $t->string('cover_path')->nullable(); $t->timestamps(); $t->index(['category','language']); });
    }
    public function down(): void { Schema::dropIfExists('books'); Schema::dropIfExists('cache_locks'); Schema::dropIfExists('cache'); Schema::dropIfExists('sessions'); Schema::dropIfExists('password_reset_tokens'); Schema::dropIfExists('users'); }
};
