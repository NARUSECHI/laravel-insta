<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    const LOCAL_STORAGE_FOLDER ='public/images';
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post= $post;
        $this->category=$category;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories',$all_categories);
    }

    public function store(Request $request)
    {
        //Validate all form data
        $request -> validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'required|mimes:jpg,png,jpeg,gif|max:1048'
        ]);

        //Save the post
        $this->post->user_id = Auth::user()->id;
        $this->post->image   = $this->saveImage($request);
        $this->post->description = $request->description;
        $this->post->save();
        // This ->post refers to the instance of Post Model / private $post

        // Save the categories to the category_post pivot table
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }

        $this->post->categoryPost()->createMany($category_post);

        //Go back to the homepage
        return redirect()->route('index');
    }

    private function saveImage($request)
    {
        //Rename the Image to the CURRENT TIME to avoid overwriting: UTC
        $image_name =time() . "." . $request->image->extension();
        // $image_name = '168812302312.jpeg';

        //Save the image inside storage/app/public/images/
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER,$image_name);

        return $image_name;
    }

    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
            ->with('post',$post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        #If the auth user is not the owner of the post, redirect to homepage.
        if(Auth::user()->id != $post->user->id)
        {
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        #Get all the category ID s of this post. Save in an array.
        $selected_categories = [];
        foreach($post->categoryPost as $category_post)
        {
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
            ->with('post',$post)
            ->with('all_categories',$all_categories)
            ->with('selected_categories',$selected_categories);
    }

    public function update(Request $request,$id)
    {
        #Validate the date from the form
        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'mimes:jpg,png,jpeg,gif|max:1048'
        ]);

        #Update the post
        $post =$this->post->findOrFail($id);
        $post->description = $request->description;

        //If there is a new image ...
        if($request->image)
        {
            # Delete the previous image from the local storage
            $this->deleteImage($post->image);

            #Move the new image to the local storage
            $post->image = $this->saveImage($request);
        }

        $post->save();
        #Delete all records from categoryPost related to this post
        $post->categoryPost()->delete();
        //Use the relationship categoryPost() to delete the records in category_post table that are related to this post.
        //DELETE FROM category_post WHERE post_id = $id

        //Save the new categories to category_post pivot table
        foreach($request->category as $category_id)
        {
            $category_post[] = [
                'category_id' => $category_id
            ];
        }
        $post->categoryPost()->createMany($category_post);
        return redirect()->route('post.show',$id);
    }

    private function deleteImage($image_name)
    {
        $image_path = self::LOCAL_STORAGE_FOLDER.$image_name;
        //$image_path = '/public/images/1667623.jpg';

        //If the image is exsisting, delete.
        if(Storage::disk('local')->exists($image_path))
        {
            Storage::disk('local')->delete('image_path');
        }
    }

    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);
        $this->deleteImage($post->image);
        $post->delete();
        return redirect()->route('index');
    }

}
