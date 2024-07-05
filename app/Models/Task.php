<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    protected $appends = ['created_at_formatted'];

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(function () {
            return Carbon::parse($this->created_at)->format('d.m.Y в H:i');
        });
    }
}
