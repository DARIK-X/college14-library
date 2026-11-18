<?php
namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class AdminController extends Controller
{
    public function loginForm() { return view('admin.login'); }
    public function login(Request $request) {
        $credentials = $request->validate(['email'=>['required','email'],'password'=>['required','string']]);
        if (!Auth::attempt($credentials)) return back()->withErrors(['email'=>__('Неверный email или пароль')])->onlyInput('email');
        $request->session()->regenerate();
        return redirect()->intended(route('admin.books'));
    }
    public function logout(Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('home'); }
    public function index() { return view('admin.index',['books'=>Book::latest()->paginate(20)]); }
    public function create() { return view('admin.form',['book'=>new Book]); }
    public function edit(Book $book) { return view('admin.form',compact('book')); }
    private function validated(Request $request, ?Book $book = null): array {
        return $request->validate([
            'title'=>['required','string','max:255'],'author'=>['required','string','max:255'],
            'description'=>['nullable','string','max:5000'],'category'=>['required','string','max:100'],
            'language'=>['required',Rule::in(['kk','ru','en'])],
            'year'=>['nullable','integer','between:1000,'.(date('Y')+1)],
            'file'=>[$book?'nullable':'required','file','mimes:pdf','max:51200'],
            'cover'=>['nullable','image','mimes:jpeg,png,webp','max:5120'],
        ]);
    }
    public function store(Request $request) {
        $data=$this->validated($request);
        $data['file_path']=$request->file('file')->store('books');
        if ($request->hasFile('cover')) $data['cover_path']=$request->file('cover')->store('covers');
        unset($data['file'],$data['cover']);
        Book::create($data);
        return redirect()->route('admin.books')->with('status','Книга добавлена');
    }
    public function update(Request $request, Book $book) {
        $data=$this->validated($request,$book);
        if ($request->hasFile('file')) { $path=$request->file('file')->store('books'); Storage::delete($book->file_path); $data['file_path']=$path; }
        if ($request->hasFile('cover')) { $path=$request->file('cover')->store('covers'); if ($book->cover_path) Storage::delete($book->cover_path); $data['cover_path']=$path; }
        unset($data['file'],$data['cover']);
        $book->update($data);
        return redirect()->route('admin.books')->with('status','Книга обновлена');
    }
    public function destroy(Book $book) {
        Storage::delete(array_filter([$book->file_path,$book->cover_path]));
        $book->delete();
        return redirect()->route('admin.books')->with('status','Книга удалена');
    }
}
