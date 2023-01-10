<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::select('*');
        if ($request->ajax()) {
             return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Show" class="show btn btn-info btn-sm showUsers m-1" id="show-users" data-url="'.route('users.show',$row->id).'">Show</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUsers m-1" id="edit-users" data-url="'.route('users.edit',$row->id).'">Edit</a>';
                    $btn = $btn.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteUsers m-1" id="delete-users"  data-url="'.route('users.destroy',$row->id).'">Delete</a>';
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
                            $query->orWhere('name', 'LIKE', "%$keyword%")
                            ->orWhere('email', 'LIKE', "%$keyword%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
            }
        return view('users.index');
    }

    /**
     * Show the form for view a users.
     *
     * @return \Illuminate\Http\Request
     */
    public function view(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::updateOrCreate([
            'id' => $request->user_id
        ],
        [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password

        ]);

        return response()->json(['success'=> true , 'msg' => 'User saved successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success'=> true , 'msg' => 'User deleted successfully.']);
    }
}
