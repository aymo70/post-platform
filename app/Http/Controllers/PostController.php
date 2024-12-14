<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // عرض جميع المنشورات
    public function index()
    {
        // $posts = Post::with(['user', 'comments.user', 'likes'])->orderBy('created_at', 'desc')->get();
        // return response()->json($posts);
        $posts = Post::with('user:id,name,image')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($post) {
                // إضافة رابط الصورة الكامل
                if ($post->image) {
                    $post->image = asset('storage/' . $post->image);
                }
                return $post;
            });

        return response()->json(['posts' => $posts]);
    }

    // إنشاء منشور جديد
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'content' => 'required|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);
    //     $imagePath = null;
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('posts', 'public'); // حفظ الصورة في مجلد posts داخل التخزين العام
    //     }


    //     $post = Post::create([
    //         'user_id' => auth()->id(),
    //         'content' => $request->content,
    //         'image' => $imagePath,
    //     ]);

    //     return response()->json(['post' => $post, 'message' => 'تم إنشاء المنشور بنجاح'], 201);
    // }
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من نوع وحجم الصورة
        ]);

        // حفظ الصورة إذا كانت موجودة
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public'); // حفظ الصورة في مجلد posts داخل التخزين العام
        }

        // إنشاء المنشور
        $post = Post::create([
            'content' => $request->content,
            'image' => $imagePath, // حفظ مسار الصورة
            'user_id' => auth()->id(), // حفظ معرف المستخدم الحالي
        ]);

        return response()->json($post, 201);
    }

    // عرض منشور معين
    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'likes'])->findOrFail($id);
        return response()->json($post);
    }

    // تحديث منشور
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'title' => 'sometimes|string|max:255',
    //         'content' => 'sometimes|string',
    //     ]);

    //     $post = Post::findOrFail($id);

    //     if ($post->user_id !== auth()->id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }

    //     $post->update($request->only('title', 'content'));

    //     return response()->json($post);
    // }
    public function update(Request $request, $id)
    {
        // التحقق من المدخلات
        $request->validate([
            'content' => 'required|string', // التحقق من أن المحتوى نصي ومطلوب
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // الصورة اختيارية مع أنواع محددة
        ]);

        // جلب المنشور المطلوب
        $post = Post::findOrFail($id);

        // التحقق من ملكية المستخدم للمنشور
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'غير مسموح لك بتعديل هذا المنشور'], 403);
        }

        // تحديث المحتوى
        $post->content = $request->content;

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            // حفظ الصورة الجديدة
            $imagePath = $request->file('image')->store('posts', 'public');
            $post->image = $imagePath;
        }

        // حفظ التحديثات في قاعدة البيانات
        $post->save();

        // إرجاع الاستجابة
        return response()->json(['post' => $post, 'message' => 'تم تحديث المنشور بنجاح']);
    }


    // حذف منشور
    // public function destroy($id)
    // {
    //     $post = Post::findOrFail($id);

    //     if ($post->user_id !== auth()->id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }

    //     $post->delete();

    //     return response()->json(['message' => 'Post deleted successfully']);
    // }
    public function destroy($id)
    {
        // جلب المنشور
        $post = Post::findOrFail($id);

        // التحقق من ملكية المستخدم للمنشور
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'غير مسموح لك بحذف هذا المنشور'], 403);
        }

        // حذف الصورة إذا كانت موجودة
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // حذف المنشور
        $post->delete();

        // إرجاع استجابة النجاح
        return response()->json(['message' => 'تم حذف المنشور بنجاح']);
    }
}
