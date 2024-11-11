<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        $post = Post::latest()->paginate(5);

        return new PostResource(true, 'List Data Post', $post);
    }

    public function store(Request $request)
    {

       $validator = Validator::make($request->all(), [
           'title' => 'required',
           'content' => 'required',
           'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
       ]);
       
    //    Check validation 
       if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
       }


    //    upload files
       $image = $request->file('image');
       $image->storeAs('public/posts/', $image->hashName());


       $post = Post::create([
           'title' => $request->title,
           'content' => $request->content,
           'image' => $image->hashName(),
       ]);


       return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);

    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return new PostResource(false, 'Data Post Tidak Ditemukan!', null);
        }

        return new PostResource(true, 'Detail Data Post!', $post);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/posts/', $image->hashName());

            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $image->hashName(),
            ]);
        } else {
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }

        return new PostResource(true, 'Data Post Berhasil Diupdate!', $post);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        // delete image
        Storage::delete('public/posts/' .basename($post->image)); 

        $post->delete();

        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
