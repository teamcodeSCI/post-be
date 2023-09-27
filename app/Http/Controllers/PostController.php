<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $post = Post::all();
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $post
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        try {
            $query = $request->query();
            $ip = $request->ip();
            $post = Post::where('ip', '=', $ip)->where('url', '=', $query['url'])->first();
            $view = Post::where('url', '=', $query['url'])->get();
            if ($post !== null) {
                $post['view_number'] = count($view);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' =>  $post
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();
            $ip = $request->ip();
            $post = Post::where('ip', '=', $ip)->get();
            if (count($post) !== 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ip already exist',
                ], 400);
            }
            $newPost = Post::create(['title' => $input['title'], 'url' => $input['url'], 'ip' =>  $ip]);
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $newPost
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $input = $request->all();
            $post = Post::find($id);
            if (!$post) {
                return response()->json([
                    'status' => false,
                    'message' => 'Post not found',
                ], 400);
            }
            if (!array_key_exists('liked', $input)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Key liked not found',
                ], 400);
            }
            $post->update(['liked' => $input['liked']]);
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $post
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
