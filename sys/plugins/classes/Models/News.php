<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class News extends Model{
    public $timestamps = false;
    protected $fillable = ['title','time','text','id_user'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}