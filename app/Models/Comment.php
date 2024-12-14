<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];

    // العلاقة مع المستخدم (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المنشور (Post)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
