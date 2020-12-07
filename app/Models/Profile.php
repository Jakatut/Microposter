<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profile";

    //fillable attributes
    protected $fillable = [
        'description',
        'image'
    ];


    public static function getById($id) {
        return User::where('id', $id)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
