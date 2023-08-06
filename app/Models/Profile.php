<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';
    protected $fillable = [
        'user_id',
        'g_id',
        's_id',
        'c_id',
        'passport',
        'profit',
        'phone',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
