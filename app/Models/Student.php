<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nama', 'tempat_tinggal', 'gender_id'];

    // Setiap Mahasiswa memiliki satu Gender
    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}

