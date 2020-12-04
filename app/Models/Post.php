<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = "posts";

    //fillable attributes
    protected $fillable = [
        'name',
        'email',
        'password',
    ];



    public function user()
    {
    	return $this->belongsTo(User::Class);
    }

}
