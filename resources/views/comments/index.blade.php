@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Comments CRUD</h2>
            </div>
            <div class="float-right">
                <a class="btn btn-success" href="javascript:void(0)" data-url="{{ route('post_comments.view') }}" id="createComments" title="Create Posts"> Add Comments </a>
                <input type="hidden" id="index_route" data-url="{{ route('comments') }}" >
                <input type="hidden" id="dataTable_route" data-url="{{ route('comments.dataTable') }}" >

            </div>
            <div class="float-left">
                <a class="btn btn-success" href="{{ route('posts.index') }}"  title="Posts">Posts </a>
            </div>
        </div>
    </div><br>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered" id="data-table-comments">
        <thead>
            <tr>
                <th>ID</th>
                <th>Author Name</th>
                <th>Post Name</th>
                <th>Comments</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    <!--Show Modal -->
  <div class="modal fade" id="viewCommentModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Comments Lists</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body" style="padding:30px">
            <div class="row">
                <p><strong>User Name:</strong> <span id="user_name"></span></p>
            </div>
            <div class="row">
                <p><strong>Posts Name:</strong> <span id="post_name"></span></p>
            </div>
            <div class="row">
                <p><strong>Comments:</strong> <span id="comments_value"></span></p>
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
    <div class="modal fade" id="ajaxCommentsModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="alert alert-success" id="custom-alert">
                    <p class="alert-msg"></p>
                </div>
                <div class="modal-body">
                    <form id="commentCreateForm" data-parsley-validate="parsley" name="commentCreateForm" class="form-horizontal">
                       <input type="hidden" name="comment_id" id="comment_id" value="">
                       <input type="hidden" id="storeRoute" data-url="{{route('comments.saveComments')}}">

                       <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Users</label>
                            <div class="col-sm-12">
                                <input type="hidden" id="user_id" name="user_id" value="">
                                <select id="users_list" class="custom-select" data-url="{{ route('comments.userslist') }}" name="users_id" data-parsley-required-message="Please select users" value="" data-parsley-required="true">
                                    <option value="">Choose Users</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-6 control-label">Posts</label>
                            <div class="col-sm-12">
                                <input type="hidden" id="post_id" name="post_id" value="">
                                <select id="post_list" class="custom-select" name="posts_id"  data-parsley-required-message="Please select posts" value="" data-parsley-required="true">
                                    <option value="">Choose Posts</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Comments</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="comments"  data-parsley-required="true" name="comments" placeholder="Enter Comments" value="" maxlength="500" data-parsley-required-message="Please enter posts comments" value="" data-parsley-required="true">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                         <button type="submit" class="btn btn-primary" id="saveCommentBtn" value="create">Submit
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
