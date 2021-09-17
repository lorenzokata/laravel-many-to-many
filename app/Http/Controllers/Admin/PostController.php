<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Post;
use App\Tag;
use App\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation rules
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        $data = $request->all();

        // gestione slug
        $startingSlug = Str::slug($data['title'], '-');
        $newSlug = $startingSlug;
        $contatore = 0;

        while(Post::where('slug', $newSlug)->first()){

            $contatore++;
            $newSlug = $startingSlug . '-' . $contatore;
        }

        $data['slug'] = $newSlug;

        // creazione istanza, fill e save dei dati
        $newPost = new Post();
        $newPost->fill($data);
        $newPost->save();

        // gestione dei tag
        if (isSet($data['tags'])) {
            $newPost->tags()->attach($data['tags']);
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $tags = Tag::all();
        return view('admin.posts.show', compact('post', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // validation rules
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        // recupero dati
        $data = $request->all();

        // creazione slug
        $startingSlug = Str::slug($data['title'],'-');
        
        if($data['title'] != $post->title){
            
            // variabile d'appoggio
            $newSlug = $startingSlug;
            $contatore = 0;

            while(Post::where('slug', $newSlug)->first()){

                $contatore++;
                $newSlug = $startingSlug . '-' . $contatore;
            }

            $data['slug'] = $newSlug;
        } 

        $post->update($data);

        // gestione dei tag
        if (isSet($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.posts.index')->with('updated', 'Hai modificato con successo l\'elemento ' .$post->id);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        $post->tags()->detach();

        return redirect()->route('admin.posts.index')->with('deleted', 'Hai eliminato con successo l\'elemento ' .$post->id);
    }
}
