<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    // Define the inverse relationship with User
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
