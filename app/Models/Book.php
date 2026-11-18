<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Book extends Model
{
    protected $fillable = ['title','author','description','category','language','year','file_path','cover_path'];
}
