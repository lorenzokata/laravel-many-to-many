<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Post;
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

        return view('admin.posts.create', compact('categories'));
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
        return view('admin.posts.show', compact('post'));
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

        return view('admin.posts.edit', compact('post', 'categories'));
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

        return redirect()->route('admin.posts.index')->with('deleted', 'Hai eliminato con successo l\'elemento ' .$post->id);
    }
}
