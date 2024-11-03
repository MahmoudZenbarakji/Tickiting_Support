<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // عرض جميع المقالات
    public function index()
    {
        $articles = Article::all();
        return response()->json([
            'success' => true,
            'data' => $articles,
        ], 200);
    }

    // إنشاء مقال جديد
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        $article = Article::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء المقال بنجاح.',
            'data' => $article,
        ], 201);
    }

    // عرض مقال معين
    public function show(Article $article)
    {
        return response()->json([
            'success' => true,
            'data' => $article,
        ], 200);
    }

    // تحديث مقال
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        $article->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث المقال بنجاح.',
            'data' => $article,
        ], 200);
    }

    // حذف مقال
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المقال بنجاح.',
        ], 200);
    }
}
