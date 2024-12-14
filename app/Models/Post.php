<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'user_id',
        'content',
        'image',
    ];

    // العلاقة مع المستخدم (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الإعجابات (Like)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // العلاقة مع التعليقات (Comment)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
