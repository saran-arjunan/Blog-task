<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Validation\Rule;


class BlogController extends Controller
{

    public function create_blog(Request $request)
    {
        try {
            $current_date = date('Y-m-d');
            $validator = Validator::make($request->all(), [
                'blog_title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('blog', 'title')->ignore($request->id),
                ],
                'blog_content' => 'required',
                'categories' => 'required',
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'=>false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ]);
            }

            $blogId = DB::table('blog')->insertGetId([
                'title' => $request->blog_title,
                'content' => $request->blog_content,
                'published_date' => $request->date,
                'categories'=>$request->categories,
                'created_at' => $current_date,
            ]);

            if ($blogId) {
                return response()->json([
                    'message' => 'Blog created successfully',
                ]);
            }
            return response()->json([
                'message' => 'Failed to create blog',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function BlogList(Request $request){
        try {
            $getList = DB::table('blog')->whereNull('updated_at')->get();
            return view('Blog.Manage-Blog', [
                'Blogs' => $getList
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function Home(Request $request){
        try {
            $getList = DB::table('blog')->whereNull('updated_at')->get();
            return view('Blog.Blog-List', [
                'Blogs' => $getList
            ]);
        } catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function Update_Blog(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => [
                    'required',
                    Rule::unique('blog', 'title')->ignore($request->id),
                ],
                'content' => 'required',
                'categories' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'=>false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ]);
            }
            $id = $request->input('id');
            $update = DB::table('blog')->where('id', $id)->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'categories' => $request->input('categories'),
            ]);

            if ($update) {
                return response()->json([
                    'status' => true,
                    'message' => 'Blog updated successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Nothing is Modified',
                ]);
            }
        } catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function Delete_Blog(Request $request){
        try {
            // return $request;
            $deleted_date = date('Y-m-d');
            $title_id = $request->input('title_id');
            $DeleteBlog = DB::table('blog')->where('id', $title_id)->update([
                'updated_at' => $deleted_date
            ]);

            if ($DeleteBlog) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data Marked as Deleted Successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to mark data as deleted'
                ]);
            }
        } catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function Search_category(Request $request){
        try{
            $categories=$request->input('Input');
            $search=DB::table('blog')->where('categories','LIKE', $categories)->whereNull('updated_at')->get();
           if($search){
            return response()->json([
                'status'=>true,
                'data'=> $search
            ]);
           }else{
            return response()->json([
                'status'=>false,
                'data'=> 'No Data Found'
            ]);
           }

        }catch(Exception $e){
        return response()->json([
            'message'=>$e->getMessage()
        ]);
        }
    }




}
