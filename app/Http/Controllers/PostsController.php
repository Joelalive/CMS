<?php

namespace App\Http\Controllers;

use App\Http\Requests\posts\CreatePostsRequest;
use App\Http\Requests\posts\UpdatePostRequest;
use App\post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = post::all();
        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostsRequest $request)
    {
        $image = $request->image->store('posts');

        post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $image,
            'published_at' => $request->published_at
        ]);

        session()->flash('success', 'Post Created Successfully');

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(post $post)
    {
        return view('posts.create', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, post $post)
    {

        $data = $request->only(['title','description','content','published_at']);

        if($request->hasFile('image')){

            $image = $request->image->store('posts');

            Storage::delete($post->image);

        }

        $data['image'] = $image;

        $post->update($data);

        session()->flash('success', 'Post Updated Successfully.');

        return redirect()->route('posts.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $post = post::withTrashed()->where('id', $id)->firstOrFail();

        if($post->trashed()){

            Storage::delete($post->image);

            $post->forceDelete();

            session()->flash('success','Post Deleted Successfully');

        }else{

            $post->delete();

            session()->flash('success','Post Trashed Successfully');

        }


        return redirect()->route('posts.index');
    }

        /**
     * Display all trashed posts.
     *
     * @param  \App\post  $post
     * @return \Illuminate\Http\Response
     */

    public function trashed(){

        $trashed = post::onlyTrashed()->get();

        return view('posts.index')->with('posts', $trashed);

    }
}
