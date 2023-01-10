@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Posts CRUD</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-success" href="javascript:void(0)" data-url="{{ route('post_users.view') }}" id="createPosts" title="Create Posts"> Create New post </a>
                <input type="hidden" id="index_route" data-url="{{ route('posts.index') }}" >
            </div>
            <div class="float-left"  style="margin:10px">
                <a class="btn btn-success" href="{{ route('comments') }}"  id="viewComments" title="Commnets"> Comments </a>
            </div>
            <div class="float-left" style="margin:10px">
                <a class="btn btn-success" href="{{ route('users.index') }}"  id="viewComments" title="Commnets"> Users </a>
            </div>
        </div>
    </div><br>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Title</th>
                <th>Description</th>
                <th width="180px">Action</th>
            </tr>
        </thead>
    </table>

    <!--Show Modal -->
  <div class="modal fade" id="viewPostsModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Posts Lists</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" style="padding:30px">
            <div class="row">
                <p><strong>User Name:</strong> <span id="user_name"></span></p>
            </div>
            <div class="row">
                <p><strong>Title:</strong> <span id="post_title"></span></p>
            </div>
            <div class="row">
                <p><strong>Description:</strong> <span id="post_description"></span></p>
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
    <div class="modal fade" id="ajaxModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="alert alert-success" id="custom-alert">
                    <p class="alert-msg"></p>
                </div>
                <div class="modal-body">
                    <form id="postForm" data-parsley-validate="parsley" name="postForm" class="form-horizontal">
                       <input type="hidden" name="post_id" id="post_id">
                       <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Users</label>
                            <div class="col-sm-12">
                                <input type="hidden" id="user_id" name="user_id" value="">
                                <select id="users_list" class="custom-select" name="users_id" data-parsley-required-message="Please select users" data-parsley-required="true" >
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title"  data-parsley-required="true" name="title" data-parsley-required-message="Please type a title" placeholder="Enter Title" value="" maxlength="50">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-12">
                                <textarea id="description" name="description" data-parsley-required="true" placeholder="Enter Description" data-parsley-required-message="Please add your post description" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                         <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Submit
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
