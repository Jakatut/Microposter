<?php

namespace App\Models;

use App\Contracts\Likeable;
use App\Concerns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Likeable
{
    use HasFactory, Concerns\Likeable;

    protected $table = "posts";

    //fillable attributes
    protected $fillable = [
        'user_id',
        'content',
        'title'
    ];



    public function user()
    {
    	return $this->belongsTo(User::Class);
    }

}
