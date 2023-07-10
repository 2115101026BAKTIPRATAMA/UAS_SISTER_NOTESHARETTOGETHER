<?php

namespace App\Http\Controllers;

use App\models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class Postcontroller extends Controller
{
    public function index(){
        $posts = Post::all();
        // return response()->json(['data'=>$posts]);
        return PostDetailResource ::collection($posts->loadMissing(['writer:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function Show($id){
        $post = Post::with('writer:id,email,username')->findOrFail($id);
        // return response()->json(['data'=>$posts]);
         return new PostDetailResource($post->loadMissing(['writer:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function Show2($id){
        $post = Post::findOrFail($id);
        // return response()->json(['data'=>$posts]);
         return new PostDetailResource($post->loadMissing(['writer:id,email,username', 'comments:id,post_id,user_id,comments_content']));
    }

    public function store(Request $request){
        // return $request->file;
        $validated = $request->validate([
        'title' =>'required|max:255',
        'news_content' => 'required'
        
        ]);

        
        $image = null;
        if ($request->file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            $image = $fileName.'.'.$extension;

            Storage::putFileAs('image', $request->file, $image);
        }

        $request['image'] = $image;
        //return response()->json('bisa');
        $request['author'] = Auth::user()->id;
         $posts = Post::create($request->all());
        // dd($spost);
        return new PostDetailResource($posts->loadMissing('writer:id,email,username'));
        
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'title' =>'required|max:255',
            'news_content' => 'required'
            
            ]);

            $post = Post::findOrFail($id);
            $post->update($request->all());

            return new PostDetailResource($post->loadMissing('writer:id,email,username'));

    }

    public function destroy($id){
        $post = Post::findOrFail($id);
        $post->delete();

        return new PostDetailResource($post->loadMissing('writer:id,email,username'));
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
