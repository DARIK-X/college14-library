<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LibraryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get('/',[LibraryController::class,'index'])->name('home');
Route::get('/books/{book}',[LibraryController::class,'show'])->name('books.show');
Route::get('/books/{book}/read',[LibraryController::class,'file'])->name('books.read');
Route::get('/books/{book}/cover',[LibraryController::class,'cover'])->name('books.cover');
Route::post('/language',function(Request $request) { $request->validate(['lang'=>'required|in:kk,ru,en']); session(['lang'=>$request->lang]); return back(); })->name('language');
Route::middleware('guest')->group(function() {
    Route::get('/admin/login',[AdminController::class,'loginForm'])->name('login');
    Route::post('/admin/login',[AdminController::class,'login'])->middleware('throttle:5,1')->name('admin.login');
});
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function() {
    Route::get('/',[AdminController::class,'index'])->name('books');
    Route::get('/books/create',[AdminController::class,'create'])->name('create');
    Route::post('/books',[AdminController::class,'store'])->name('store');
    Route::get('/books/{book}/edit',[AdminController::class,'edit'])->name('edit');
    Route::put('/books/{book}',[AdminController::class,'update'])->name('update');
    Route::delete('/books/{book}',[AdminController::class,'destroy'])->name('destroy');
    Route::post('/logout',[AdminController::class,'logout'])->name('logout');
});
