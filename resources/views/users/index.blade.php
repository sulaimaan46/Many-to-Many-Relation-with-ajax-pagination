@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users CRUD</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-success" href="javascript:void(0)" id="createUsers" title="Create Users"> Create New Users     </a>
                <input type="hidden" id="index_route" data-url="{{ route('users.index') }}" >
            </div>
            <div class="float-left">
                <a class="btn btn-success" href="{{ route('posts.index') }}" id="viewPosts" title="Posts">View Posts</a>
            </div>
        </div>
    </div><br>
    <div class="alert alert-success" id="custom-alert">
        <p class="alert-msg"></p>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered data-table-users">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th width="180px">Action</th>
            </tr>
        </thead>
    </table>

    <!--Show Modal -->
  <div class="modal fade" id="viewUsersModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Users Lists</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" style="padding:30px">
            <div class="row">
                <p><strong>Name:</strong> <span id="user_name"></span></p>
            </div>
            <div class="row">
                <p><strong>Email:</strong> <span id="user_email"></span></p>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

    <!-----End Show Model ----->

    <!--Create and Update Modal -->
    <div class="modal fade" id="ajaxUsersModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="userForm" name="userForm" data-parsley-validate="parsley" class="form-horizontal">
                       <input type="hidden" name="user_id" id="user_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" data-parsley-required-message="Please insert user name" value="" data-parsley-required="true" >
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" data-parsley-required-message="Please insert user email" value="" data-parsley-required="true" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" value="" data-parsley-required-message="Please insert usert password" data-parsley-required="true" >
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                         <button type="submit" class="btn btn-primary" id="saveUsersBtn" value="create">Submit
                         </button>
                         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal -->
@endsection
