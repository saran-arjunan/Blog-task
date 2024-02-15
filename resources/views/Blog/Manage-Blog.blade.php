@extends('Layout.Header')


@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


<div class="container mt-5">


    {{-- model --}}

    <div class="modal fade" id="exampleModal1" class="student_viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="text-center">
                    <div class="blog_create_message ">

                    </div>
                </div>

                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">Create Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="claim"></div>
                    <form action="" method="post">
                        <input type="hidden" name="update_id" id="update_id">
                        <div class="mb-2 row">
                            <label for="inputPassword" class="col-sm-4 col-form-label fs-6 fw-bold">Blog Title:</label>
                            <div class="col-sm-8">
                                <input type="text" name="blog_title" class="form-control" id="blog_title">
                                <span class="error-message" id="blog_title_error" style="color: red;"></span>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="inputPassword" class="col-sm-4 col-form-label fs-6 fw-bold">Blog content:</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="blog_content" id="blog_content" rows="3"></textarea>
                                <span class="error-message" id="blog_content_error" style="color: red;"></span>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="inputPassword" class="col-sm-4 col-form-label fs-6 fw-bold">Categories:</label>
                            <div class="col-sm-8">
                                <input type="text" name="categories" class="form-control" id="categories">
                                <span class="error-message" id="categories_error" style="color: red;"></span>
                            </div>
                        </div>
                        <div class="mb-2 row">
                            <label for="inputPassword" class="col-sm-4 col-form-label fs-6 fw-bold">Publish Date:</label>
                            <div class="col-sm-8">
                                <input type="date" name="date" class="form-control" id="date">
                                <span class="error-message" id="date_error" style="color: red;"></span>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="create_blog_conform" name="create_blog_conform" class="search btn btn-primary">Create Blog</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
{{-- end --}}

{{-- off canvas --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="background-color: #f8f9fa; border: 1px solid #ced4da; border-radius: 10px;">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel" class="text-info fw-bold fs-6" style="margin-bottom: 0;">Edit Blog</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="editForm">
            <div class="row">
                <div class="col-12">
                    <div>
                        <table>
                            <tr>
                                <td class="text-danger fw-bold fs-6 mt-2">
                                    Title
                                </td>
                                <td class="text-primary fs-6">
                                    <input type="hidden" id="title_id">
                                    <input type="text" class="form-control" id="edit_title" placeholder="Title">
                                    <span class="error-message" id="titleError"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-danger fw-bold fs-6 mt-2">
                                    Content
                                </td>
                                <td class="text-primary fs-6">
                                    <textarea name="" cols="30" rows="10" class="form-control" id="edit_content"></textarea>
                                    <span class="error-message" id="contentError"></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-danger fw-bold fs-6 mt-5">
                                    Categories
                                </td>
                                <td class="text-primary fs-6 mt-2">
                                    <input type="text" class="form-control" id="edit_Categories">
                                    <span class="error-message" id="categoriesError"></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row text-center mt-2">
                <button type="button" class="btn btn-success m-auto" style="background-color: #28a745; border-color: #28a745;" id="updateButton">Update</button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#updateButton').click(function () {
            // Clear previous error messages
            $('.error-message').text('');

            var title_id= $('#title_id').val();
            var title = $('#edit_title').val();
            var content = $('#edit_content').val();
            var categories = $('#edit_Categories').val();

            // Validation
            var isValid = true;
            if (title === '') {
                $('#titleError').text('Title is required').css('color', 'red');
                isValid = false;
            }
            if (content === '') {
                $('#contentError').text('Content is required').css('color', 'red');
                isValid = false;
            }
            if (categories === '') {
                $('#categoriesError').text('Categories is required').css('color', 'red');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            var csrfToken = '{{ csrf_token() }}';
            $.ajax({
                url: '/Update-Blog',
                type: 'POST',
                data: {
                    title: title,
                    id:title_id,
                    content: content,
                    categories: categories,
                    _token: csrfToken
                },
                success: function (response) {
                    if(response.status==false){
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                            footer: ''
                            });
                    }
                    if (response.status == true) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                                footer: ''
                            });

                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }

                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(xhr, status, error);
                }
            });
        });
    });
</script>

    <div class="">
        <button class="btn btn-success" id="create_blog">create Blog </button>
    </div>
    <div class="table-responsive mt-2">
        <table id="example" class="table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Published Date</th>
                    <th>Categories</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($Blogs as $key => $BlogList)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$BlogList->title}}</td>
                    <td>{{$BlogList->published_date}}</td>
                    <td>{{$BlogList->categories}}</td>
                    <td><button class="btn btn-primary edit_blog" data-titleid="{{$BlogList->id}}" data-title="{{$BlogList->title}}"  data-content="{{$BlogList->content}}" data-categories="{{$BlogList->categories}}" id="edit_{{$key}}">Edit</button></td>

                    <td><button class="btn btn-danger delete_blog" data-titleid="{{$BlogList->id}}" id="delete_{{$key}}">Delete</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>


    </div>
</div>
<script>
 $(document).ready(function() {
    $("#blog_title").on('input', function() {
        $('#blog_title_error').text('');
    });

    $("#blog_content").on('input', function() {
        $('#blog_content_error').text('');
    });

    $("#categories").on('input', function() {
        $('#categories_error').text('');
    });

    $("#date").on('input', function() {
        $('#date_error').text('');
    });

    $("#create_blog").on('click', function() {
        $('#exampleModal1').modal('show');
    });

    $('#create_blog_conform').on('click', function() {
        var blog_title = $('#blog_title').val();
        var blog_content = $('#blog_content').val();
        var categories = $('#categories').val();
        var date = $('#date').val();

        $('.error-message').text('');

        var isValid = true;

        if (blog_title.trim() == '') {
            $('#blog_title_error').text('Blog Title is required');
            isValid = false;
        }

        if (blog_content.trim() == '') {
            $('#blog_content_error').text('Blog Content is required');
            isValid = false;
        }

        if (categories.trim() == '') {
            $('#categories_error').text('Categories is required');
            isValid = false;
        }

        if (date.trim() == '') {
            $('#date_error').text('Publish Date is required');
            isValid = false;
        }

        if (!isValid) {
            return false;
        }

        $.ajax({
    url: '/create-Blog',
    method: 'POST',
    data: {
        blog_title: blog_title,
        blog_content: blog_content,
        categories: categories,
        date: date
    },
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: function(response) {
        if(response.status==false){
            var errors = response.errors;
            var errorMessage = '';
            $.each(errors.blog_title, function(index, value) {
                errorMessage += value + '<br>';
            });
            $('.blog_create_message').html(errorMessage).css('color', 'red');
        }
        else{
            window.location.reload();
        }
    },
    error: function(xhr, status, error) {

    }
});
});
    });
    $("#example").on('click', '.edit_blog', function() {
    $('#offcanvasRight').offcanvas('show');
    var title = $(this).data('title');
    var content = $(this).data('content');
     var title_id= $(this).data('titleid');
    var categories = $(this).data('categories');

    $('#edit_title').val(title);
    $('#edit_content').val(content);
    $('#edit_Categories').val(categories);
    $('#title_id').val(title_id);

});

$("#example").on('click', '.delete_blog', function() {
    var title_id= $(this).data('titleid');
    console.log(title_id);
    Notiflix.Confirm.Show(
        'Delete Confirmation',
        'Are you sure you want to delete?',
        'Yes',
        'No',
        function() {
            $.ajax({
                url: 'Delete-Blog',
                data: {
                    'title_id': title_id
                },
                type: 'post',
                headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
                success: function(response) {
                    if(response.status==false){
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                            footer: ''
                            });
                    }
                    if (response.status == true) {
                            Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: response.message,
                                footer: ''
                            });

                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        },
        function() {
            // No action required for cancel
        }
    );
});

$(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]],
        "pageLength": 5
    });
});


</script>

@endsection


