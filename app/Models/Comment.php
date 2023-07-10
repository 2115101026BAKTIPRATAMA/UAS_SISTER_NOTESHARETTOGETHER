<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\softDeletes;

class Comment extends Model
{
    use HasFactory, softDeletes;

    protected $fillable =[
        'post_id','user_id','comments_content'
    ];

    public function commentator():BelongsTo
    {
        return $this->BelongsTo(User::class, 'user_id','id'); 
    }
}
