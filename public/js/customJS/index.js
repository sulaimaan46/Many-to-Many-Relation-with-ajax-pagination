    $(function(){

    $("#custom-alert").hide();
    $("#postForm").parsley();
    $("#userForm").parsley();
    $("#commentCreateForm").parsley();


    pagination();
    /*------------------------------------------
    --------------------------------------------
    Yajra DataTable on the Lists of Posts details
    --------------------------------------------
    --------------------------------------------*/

    let indexRoute =window.location.href;

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: indexRoute,
        columns: [
            {data: 'id', name: 'Id', orderable: true  },
            {data: 'users', name: 'User Name', orderable: true  },
            {data: 'title', name: 'Title' , orderable: false},
            {data: 'description', name: 'Description', orderable: false},
            {data: 'action', name: 'Action', orderable: false, searchable: true},
        ]
    });

    var users_table = $('.data-table-users').DataTable({
        processing: true,
        serverSide: true,
        ajax: indexRoute,
        columns: [
            {data: 'id', name: 'Id', orderable: true  },
            {data: 'name', name: 'Name' , orderable: false},
            {data: 'email', name: 'Email', orderable: false},
            {data: 'action', name: 'Action', orderable: false, searchable: true},
        ]
    });

    //Posts Functions

    /*------------------------------------------
    --------------------------------------------
    When click user on Create Post Button
    --------------------------------------------
    --------------------------------------------*/
    $('#createPosts').click(function () {
        var route = $(this).data('url');
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#saveBtn').val("create-post");
                $('#post_id').val('');
                $('#PostForm').trigger("reset");
                $('#modelHeading').html("Create New Post");
                $('#ajaxModel').modal('show');
                $('#users_list').children().remove();
                $('#users_list').append(`<option value="">Choose Users</option>`);
                $(data).each(function(i,value) {
                    $('#users_list').append(`<option value="${value.id}">
                        ${value.name}
                         </option>`);
                  });

            }
        });


    });

    //Modal Open When Click the Edit post Button
    $('body').on('click', '.editPost', function () {
        var route = $(this).data('url');
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#modelHeading').html("Edit Product");
                $('#saveBtn').val("edit-post");
                $('#ajaxModel').modal('show');
                $('#post_id').val(data.postDetails.id);
                $('#title').val(data.postDetails.title);
                $('#description').val(data.postDetails.description);
                $('#users_list').children().remove();
                $('#users_list').append(`<option value="">Choose Users</option>`);
                $(data.usersDetails).each(function(i,value) {
                    $('#users_list').append(`<option value="${value.id}">
                        ${value.name}
                         </option>`);
                  });
            },
            error: function(data){
                console.log('Error:', data);
                var error = data.responseJSON.errors.user_id;
                $(error).each(function(i,value) {
                    $('.alert-msg').text(value);
                  });
            }
        });
      });

    //Set the CSRF Token when send to the ajax call
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Modal Open When Click the Submit post Button
    $('body').on('click', '#saveBtn', function (e) {
        $(this).html('Sending..');
        var postURL = $(this).data('url');
        $.ajax({
          data: $('#postForm').serialize(),
          url: postURL,
          type: "POST",
          success: function (data) {
              $('#postForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
              $("#custom-alert").show();
              $('.alert-msg').parent().removeClass('alert-danger').addClass('alert-success')
              $('.alert-msg').text(data.msg);
              $(this).html('');
              $(this).html('Submit');

              setTimeout(() => {
                $("#custom-alert").hide();
              }, 2000);
          },
          error: function (data) {
              console.log('Error:', data);
              var error = data.responseJSON.errors;
                var errorsUsers = (error.users_id) ? 1 : 0 ;
                var errorsTitle = (error.title) ? 1 : 0 ;
                var errorsDescription = (error.description) ? 1 : 0 ;

              $('.alert-msg').parent().removeClass('alert-success').addClass('alert-danger')
              $('.alert-msg').text(data.msg);

              if(errorsUsers == 1){
                $(error.users_id).each(function(i,value) {
                    console.log('user_id',value);
                    $('.alert-msg').text(value);
                  });
              }if(errorsTitle == 1){
                $(error.title).each(function(i,value) {
                    $('.alert-msg').text(value);
                  });
              }if(errorsDescription == 1){
                $(error.description).each(function(i,value) {
                    $('.alert-msg').text(value);
                  });
              }

              $('#saveBtn').html('Save Changes');
              $('#postForm').trigger("reset");
              $('#ajaxModel').modal('show');
              $("#custom-alert").show();

              setTimeout(() => {
                $("#custom-alert").hide();
              }, 5000);
          }
      });
    });

    //Get the post list for particular users
    $('body').on('change', '#users_list' ,function(){
        var route = $(this).data('url');
        $.ajax({
            url: route+"?user_id="+$(this).val(),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#post_list').children().remove();
                $('#post_list').append(`<option value="">Choose Posts</option>`);
                $(data.postDetails).each(function(i,value) {
                    $('#post_list').append(`<option value="${value.id}">
                        ${value.title}
                         </option>`);
                  });
            }
        });
    })
    //Modal Open When Click the Delet post Button
    $('body').on('click', '#delete-posts', function () {
        var deleteUrl = $(this).data('url');
        if(confirm("Are You sure want to delete !") == true){
            $.ajax({
                type: "DELETE",
                url: deleteUrl,
                success: function (data) {
                    table.draw();
                    $("#custom-alert").show();
                    $('.alert-msg').parent().removeClass('alert-success').addClass('alert-danger');
                    $('.alert-msg').text(data.msg);
                    setTimeout(() => {
                        $("#custom-alert").hide();
                      }, 2000);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });

    //End Posts Functions

    //Users Functions

    $('#createUsers').click(function () {
        $('#saveUsersBtn').val("create-users");
        $('#user_id').val('');
        $('#userForm').trigger("reset");
        $('#modelHeading').html("Create New Users");
        $('#ajaxUsersModel').modal('show');
    });

     //Modal Open When Click the Submit post Button
     $('#saveUsersBtn').click(function (e) {
        $(this).html('Sending...');
        var userCreateURL = $(this).data('url');
        $.ajax({
          data: $('#userForm').serialize(),
          url: userCreateURL,
          type: "POST",
          success: function (data) {
              $('#userForm').trigger("reset");
              $('#ajaxUsersModel').modal('hide');
              users_table.draw();
              $("#custom-alert").show();
              $('.alert-msg').parent().removeClass('alert-danger').addClass('alert-success')
              $('.alert-msg').text(data.msg);
              $("#saveUsersBtn").html("");
              $("#saveUsersBtn").html('Submit');
              setTimeout(() => {
                $("#custom-alert").hide();
              }, 2000);
          },
          error: function (data) {
              console.log('Error:', data);
              var error = data.responseJSON.errors.description;
              $('.alert-msg').parent().removeClass('alert-success').addClass('alert-danger')
              $('.alert-msg').text(data.msg);
              $(error).each(function(i,value) {
                $('.alert-msg').text(value);
              });
              $('#saveUsersBtn').html('Save Changes');
              $('#userForm').trigger("reset");
              $('#ajaxUsersModel').modal('hide');
              $("#custom-alert").show();
              setTimeout(() => {
                $("#custom-alert").hide();
              }, 2000);
          }
      });
    });

     //Modal Open When Click the Edit post Button
     $('body').on('click', '.editUsers', function () {
        var route = $(this).data('url');
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#modelHeading').html("Edit Users");
                $('#saveBtn').val("edit-user");
                $('#ajaxUsersModel').modal('show');
                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#password').val(data.password);

            }
        });
      });


    //Modal Open When Click the Delet User Button
    $('body').on('click', '#delete-users', function () {
        var deleteUrl = $(this).data('url');
        if(confirm("Are You sure want to delete !") == true){
            $.ajax({
                type: "DELETE",
                url: deleteUrl,
                success: function (data) {
                    users_table.draw();
                    $("#custom-alert").show();
                    $('.alert-msg').parent().removeClass('alert-success').addClass('alert-danger');
                    $('.alert-msg').text(data.msg);
                    setTimeout(() => {
                        $("#custom-alert").hide();
                      }, 2000);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
    //End Users Functions

    //Commnet Functions

    /*------------------------------------------
    --------------------------------------------
    When click user on Create Commnet Button
    --------------------------------------------
    --------------------------------------------*/
    $('#createComments').click(function () {
        var route = $(this).data('url');
        $.ajax({
            url: route,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#saveBtn').val("create-comment");
                $('#user_id').val('');
                $('#post_id').val('');
                $('#commentCreateForm').trigger("reset");
                $('#modelHeading').html("Create New Comment");
                $('#ajaxCommentsModel').modal('show');
                $('#users_list').children().remove();
                $('#users_list').append(`<option value="">Choose Users</option>`);
                $(data.usersDetails).each(function(i,value) {
                    $('#users_list').append(`<option value="${value.id}">
                        ${value.name}
                         </option>`);
                  });

                  $('#post_list').children().remove();
                  $('#post_list').append(`<option value="">Choose Posts</option>`);
                  $(data.postDetails).each(function(i,value) {
                      $('#post_list').append(`<option value="${value.id}">
                          ${value.title}
                           </option>`);
                    });

            }
        });

    });

    //Modal Open When Click the Edit Comment Button
    $('body').on('click', '.editComment', function () {
        var route = $(this).data('url');
        var comment_id = $(this).data('id');
        $.ajax({
            url: route+"?comment_id="+comment_id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#saveBtn').val("edit-comment");
                $('#modelHeading').html("Update Comment");
                $('#ajaxCommentsModel').modal('show');
                $('#comment_id').val(data.commentDetails.id);
                $("#comments").val(data.commentDetails.comments);
                $('#users_list').children().remove();
                $('#users_list').append(`<option value="">Choose Users</option>`);
                $(data.usersDetails).each(function(i,value) {
                    var usersId = data.commentDetails.post[0].user.id;
                    if(value.id == usersId){
                        $('#users_list').append(`<option value="${value.id}" selected>
                             ${value.name}
                         </option>`);
                    }else{
                        $('#users_list').append(`<option value="${value.id}">
                        ${value.name}
                         </option>`);
                    }
                });


                  $('#post_list').children().remove();
                  $('#post_list').append(`<option value="">Choose Posts</option>`);
                  var post = data.commentDetails.post[0];
                  $(data.postDetails).each(function(i,value) {
                    if(value.id == post.id){
                        $('#post_list').append(`<option value="${value.id}" selected>
                             ${value.title}
                         </option>`);
                    }else{
                        $('#post_list').append(`<option value="${value.id}">
                        ${value.title}
                         </option>`);
                    }
                });
            },
            error: function(data){
                console.log('Error:', data);
                var error = data.responseJSON.errors.user_id;
                $(error).each(function(i,value) {
                    $('.alert-msg').text(value);
                  });
            }
        });
      });



      //Modal Open When Click the Submit Comment Button
     $('#saveCommentBtn').click(function (e) {
        $(this).html('Sending...');
        var commentCreateURL = $("#storeRoute").data('url');
        $.ajax({
          data: $('#commentCreateForm').serialize(),
          url: commentCreateURL,
          type: "POST",
          success: function (data) {
              $('#commentCreateForm').trigger("reset");
              $('#ajaxCommentsModel').modal('hide');
              $("#custom-alert").show();
              $('.alert-msg').parent().removeClass('alert-danger').addClass('alert-success')
              $('.alert-msg').text(data.msg);
              $("#saveCommentBtn").html("");
              $("#saveCommentBtn").html('Submit');
              $('#data-table-comments').DataTable().ajax.reload();
              setTimeout(() => {
                $("#custom-alert").hide();
              }, 2000);
          },
          error: function (data) {
              console.log('Error:', data);
              var error = data.responseJSON.errors.description;
              $('.alert-msg').parent().removeClass('alert-success').addClass('alert-danger')
              $('.alert-msg').text(data.msg);
              $(error).each(function(i,value) {
                $('.alert-msg').text(value);
              });
              $('#saveCommentBtn').html('Save Changes');
              $('#saveCommentBtn').trigger("reset");
              $('#ajaxCommentsModel').modal('hide');
              $("#custom-alert").show();

              setTimeout(() => {
                $("#custom-alert").hide();
              }, 2000);
          }
      });
    });

     //Modal Open When Click the Delet User Button
     $('body').on('click', '#delete-comments', function () {
        var deleteUrl = $(this).data('url');
        var deleteId = $(this).data('id');
        var deletePostId = $(this).data('post');

        if(confirm("Are You sure want to delete !") == true){
            $.ajax({
                type: "GET",
                url: deleteUrl+"?comment_id="+deleteId+"&post_id="+deletePostId,
                success: function (data) {
                    $('#data-table-comments').DataTable().ajax.reload();
                    $("#custom-alert").show();
                    $('.alert-msg').parent().removeClass('alert-success').addClass('alert-danger');
                    $('.alert-msg').text(data.msg);
                    setTimeout(() => {
                        $("#custom-alert").hide();
                      }, 2000);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
      //End Comment Function

});

    //Modal Open When Click the Show post Button
    $(document).on('click','#show-posts', function () {
      var showURL = $(this).data('url');
        $.ajax({
            url: showURL,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#viewPostsModal').modal('show');
                $('#user_name').text(data.user.name);
                $('#post_title').text(data.title);
                $('#post_description').text(data.description);
            }
        });
    });

     //Modal Open When Click the Show Users Button
     $(document).on('click','#show-users', function () {
        var showURL = $(this).data('url');
          $.ajax({
              url: showURL,
              type: 'GET',
              dataType: 'json',
              success: function(data) {
                  $('#viewUsersModal').modal('show');
                  $('#user_name').text(data.name);
                  $('#user_email').text(data.email);
              }
          });
      });

      //Modal Open When Click the Show Users Button
     $(document).on('click','#show-comments', function () {
        var showURL = $(this).data('url');
        var showId = $(this).data('id');

          $.ajax({
              url: showURL+"?id="+showId,
              type: 'GET',
              dataType: 'json',
              success: function(data) {
                  $('#viewCommentModal').modal('show');
                  console.log(data.comments.post[0]);
                  var post = data.comments.post[0].title;
                  var user = data.comments.post[0].user
                  $('#user_name').text(user.name);
                  $('#post_name').text(post);
                  $('#comments_value').text(data.comments.comments);

              }
          });
      });

      function pagination(){
        let dataTableRoute = $("#dataTable_route").data('url');
        $('#data-table-comments').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            lengthMenu: [10, 25, 50, 'All'],
            "oLanguage": {
                "sProcessing": "<span class='processing' >Please Wait ....</span>",
            },
            ajax: {
                url: dataTableRoute,
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            },

            columns: [
            { data: "id" },
            { data: "user_name" },
            { data: "post_name" },
            { data: "comments" },
            { data: "action" },
            ],
        });
      }
