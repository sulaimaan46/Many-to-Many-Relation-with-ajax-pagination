<?php

namespace App\Http\Controllers;

use App\Models\PostComments;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFormRequests;
use Yajra\DataTables\Facades\DataTables;

class PostCommentsController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Comment::select('*')->with('post.user')->get();
        dd($data);
        if ($request->ajax()) {
             return Datatables::eloquent($data)
                ->addColumn('users', function ($comment) {
                    return $comment->user->name;
                })
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Show" class="show btn btn-info btn-sm showPost m-1" id="show-posts" data-url="'.route('posts.show',$row->id).'">Show</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editPost m-1" id="edit-posts" data-url="'.route('posts.edit',$row->id).'">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="delete btn btn-danger btn-sm deletePost m-1" id="delete-posts"  data-url="'.route('posts.destroy',$row->id).'">Delete</a>';

                    return $btn;
                })

                ->order(function ($query)  use ($request)  {
                    if ((!empty($request->get('order')))) {
                        $order = $request->get('order');
                        $keyword = $order[0]['dir'];
                        ($keyword == "asc") ? $query->orderBy('id') : $query->orderBy('id', 'desc') ;
                    }
                })

                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                         $instance->where(function($query) use($request){
                            $search = $request->get('search');
                            $keyword = $search['value'];
                            $query->orWhere('title', 'LIKE', "%$keyword%")
                            ->orWhere('description', 'LIKE', "%$keyword%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);

            }
        return view('posts.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFormRequests $storeRequest)
    {
        Post::updateOrCreate([
            'id' => $storeRequest->post_id
        ],
        [
            'user_id' => $storeRequest->users_id,
            'title' => $storeRequest->title,
            'description' => $storeRequest->description
        ]);

        return response()->json(['success'=> true , 'msg' => 'Post saved successfully.']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('user')->find($id);
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::with('user')->find($id);
        $users = User::all();
        return response()->json(['success'=> true , 'postDetails' => $post , 'usersDetails' => $users]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::find($id)->delete();
        return response()->json(['success'=> true , 'msg' => 'Post deleted successfully.']);
    }
}
