<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable = ['jenis_kelamin'];

    // One-to-Many: Gender memiliki banyak Mahasiswa
    public function student()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}

