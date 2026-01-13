<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.blogs.index', compact('posts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blogPost)
    {
        $blogPost->load('user');
        return view('admin.blogs.show', compact('blogPost'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'status' => 'required|in:draft,published,scheduled,archived'
        ]);

        $blogPost->update([
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null
        ]);

        return back()->with('success', "Blog post status updated to {$request->status}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully');
    }
}
