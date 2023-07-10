<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;


class Post extends Model
{
    use HasFactory, softDeletes;
 
    protected $fillable =[
        'title','news_content','author','image'
    ];

    public function writer():BelongsTo
    {
        return $this->BelongsTo(User::class, 'author','id'); 
    }

    public function comments():HasMany
    {
        return $this->HasMany(Comment::class, 'post_id','id'); 
    }
}
