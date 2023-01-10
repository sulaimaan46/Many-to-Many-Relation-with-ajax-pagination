<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\PostComments;
use Yajra\DataTables\Facades\DataTables;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            return view('comments.index');
    }


    /**
     * Show the form for createView a users.
     */
    public function createView()
    {
        $post = Post::all();
        $users = User::all();

        return response()->json(['success'=> true, 'postDetails' => $post , 'usersDetails' => $users]);
    }

     /**
     * Show the form for view a users.
     */
    public function view(Request $request)
    {

        $post = Post::all();
        $users = User::all();
        $comments = Comment::where('id',$request->comment_id)->with('post.user')->first();
        return response()->json(['success'=> true, 'commentDetails' => $comments, 'postDetails' => $post , 'usersDetails' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getComments(Request $request)
    {
        try {

            $query = Comment::select('*')->with('post.user');

            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // total number of rows per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = ($columnIndex_arr) ? $columnIndex_arr[0]['column'] : 0 ; // Column index
            $columnName = ($columnIndex_arr) ? $columnName_arr[$columnIndex]['data'] : 'id' ; // Column name
            $columnSortOrder = ($order_arr) ? $order_arr[0]['dir'] : 'desc' ; // asc or desc
            $searchValue = ($search_arr) ? $search_arr['value'] : ""; // Search value

            // Total records
            $totalRecords = Comment::select('count(*) as allcount')->count();
            $totalRecordswithFilter = Comment::select('count(*) as allcount')->where('comments', 'like', '%' . $searchValue . '%')->count();

            // Get records, also we have included search filter as well
            $records = Comment::with('post.user')->orderBy($columnName, $columnSortOrder)
                ->where('comments', 'like', '%' . $searchValue . '%')
                ->orWhereHas('post',
                    function ($query) use ($searchValue) {
                        $query->where('title', 'LIKE ', '%' . $searchValue . '%');
                    }
                )->orWhereHas('post.user',
                    function ($query) use ($searchValue) {
                        $query->where('name', 'LIKE ', '%' . $searchValue . '%');
                    }
                )
                ->select('*')
                ->get();

            $data_arr = array();

            foreach ($records as $record) {
                $post = $record->post;
                $action = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Show" class="show btn btn-info btn-sm showComment m-1" id="show-comments" data-url="'.route('comments.show').'">Show</a><a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editComment m-1" id="edit-comments" data-url="'.route('comments.edit').'" >Edit</a><a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$record->id.'" data-post = "'.$post[0]->id.'" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteComment m-1" id="delete-comments"  data-url="'.route('comments.destroy').'">Delete</a>' ;
                $data_arr[] = array(
                    "id" => $record->id,
                    "user_name" => $post[0]->user->name,
                    "post_name" => $post[0]->title,
                    "comments" => $record->comments,
                    "action" =>  $action,

                );
            }
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr,
            );

            return json_encode($response);

        } catch (\Exception $th) {
            dd($th);
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
        $comment = Comment::updateOrCreate([
                    'id' => $request->comment_id
                ],
                [
                    'comments' => $request->comments,
                ]);
        $post = Post::find($request->posts_id);
        $comment->post()->attach($post);

        return response()->json(['success'=> true , 'msg' => 'Comment saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $comments = Comment::with('post.user')->find($request->id);
        return response()->json(['success'=> true, 'comments' => $comments ]);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $users
     * @return \Illuminate\Http\Request
     */
    public function userslist(Request $request)
    {
        $posts = Post::where('user_id',$request->user_id)->get();
        return response()->json(['success'=> true, 'postDetails' => $posts]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $comment = Comment::where('id',$request->comment_id)->first();
        $comment->post()->detach($request->post_id);
        $comment = $comment->delete($request->post_id);
        return response()->json(['success'=> true , 'msg' => 'Comments deleted successfully.']);

    }
}
