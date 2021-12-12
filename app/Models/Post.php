<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
//use Illuminate\Support\Arr;


class Post extends Model
{
    use HasFactory;

    protected $dates = ['published_at'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (! $this->exists) {
            // $this->attributes['slug'] = str_slug($value);
            $this->attributes['slug'] = Str::slug($value);
        }
    }

}
