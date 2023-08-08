<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\SpladeTable;


class PostController extends Controller
{
    public function index() {
        // get all post data
        $posts = Post::latest()->paginate(7);

        // render view
        return view('posts.index', [
            'posts' => SpladeTable::for($posts)
            ->column('image')
            ->column('title')
            ->column('content')
            ->column('action')
        ]);
    }

    public function create() {
        return view('posts.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

         // upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        // insert new post to db
        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $image->hashName(),
        ]);

        // render view
        return redirect(route('posts.index'));
    }
}
