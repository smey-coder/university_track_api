<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    protected $fillable = [

        'graduation_id',
        'certificate_code',
        'issued_date',
        'file_path'

    ];


    public function graduation()
    {
        return $this->belongsTo(
            Graduation::class
        );
    }

}