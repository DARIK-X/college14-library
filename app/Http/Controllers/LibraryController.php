<?php
namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class LibraryController extends Controller
{
    public function index(Request $request) {
        $books = Book::query()->when($request->filled('q'), fn($q) => $q->where(fn($q) => $q->where('title','like','%'.$request->q.'%')->orWhere('author','like','%'.$request->q.'%')))
            ->when($request->filled('category'), fn($q) => $q->where('category',$request->category))
            ->when(in_array($request->input('language'), ['kk','ru','en'],true), fn($q) => $q->where('language',$request->language))
            ->latest()->paginate(12)->withQueryString();
        $categories = Book::query()->distinct()->orderBy('category')->pluck('category');
        return view('books.index',compact('books','categories'));
    }
    public function show(Book $book) { return view('books.show',compact('book')); }
    public function file(Book $book) {
        abort_unless(Storage::disk('local')->exists($book->file_path),404);
        return response()->file(Storage::disk('local')->path($book->file_path),['Content-Type'=>'application/pdf','Content-Disposition'=>'inline; filename="book-'.$book->id.'.pdf"','X-Content-Type-Options'=>'nosniff']);
    }
    public function cover(Book $book) {
        abort_unless($book->cover_path && Storage::disk('local')->exists($book->cover_path),404);
        return response()->file(Storage::disk('local')->path($book->cover_path),['X-Content-Type-Options'=>'nosniff']);
    }
}
