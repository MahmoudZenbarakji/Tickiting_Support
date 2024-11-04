<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json([
            'success' => true,
            'data' => $articles,
        ], 200);
    }

    
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
            'message' => 'Article created successfully',
            'data' => $article,
        ], 201);
    }
 
    public function show(Article $article)
    {
        return response()->json([
            'success' => true,
            'data' => $article,
        ], 200);
    }

    
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
            'message' => 'Article updated successfully',
            'data' => $article,
        ], 200);
    }

    
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully',
        ], 200);
    }
}
