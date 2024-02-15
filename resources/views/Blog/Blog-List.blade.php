@extends('Layout.Header')
@section('content')

<div class="container">
    <div class="row justify-content-end mt-3">
        <div class="col-3">
            <input type="text" class="form-control border-2" placeholder="Search category here" id="search_category">
        </div>
    </div>
    <div id="selection_message"></div> <!-- Display selection message here -->
    <div class="row" id="blog_list">
        <!-- Blog list will be displayed here -->
    </div>
    @php
        $count = 0;
    @endphp
    @foreach($Blogs as $key => $BlogList)
        @if($count % 2 == 0)
        <div class="row">
        @endif
            <div class="col-6">
                <div class="card shadow mt-5" style="width: 32rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{$BlogList->title}}</h5>
                        <p class="card-text">{{$BlogList->content}}</p>
                        <p class="card-text">Published at:{{$BlogList->published_date}}</p>
                        <a href="#" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        @if($count % 2 != 0 || $key == count($Blogs) - 1)
        </div>
        @endif
        @php
            $count++;
        @endphp
    @endforeach
</div>
<script>
   $('#search_category').on('input', function() {
    var Input = $('#search_category').val();
    // $('#selection_message').text('');
    if (Input.trim() !== '') {
        $.ajax({
            url: '/Search-category',
            method: 'POST',
            data: {
                Input: Input
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                var Data = response.data;
                var html = '';
                $('#selection_message').text('search-Results');
                Data.forEach(function(item) {
                    html += '<div class="col-6">';
                    html += '<div class="card shadow mt-5" style="width: 32rem;">';
                    html += '<div class="card-body">';
                    html += '<h5 class="card-title">' + item.title + '</h5>';
                    html += '<p class="card-text">' + item.content + '</p>';
                    html += '<a href="#" class="btn btn-primary">View</a>';
                    html += '</div></div></div>';
                    html += '<hr>';
                });
                $('#blog_list').html(html);
            },
            error: function(xhr, status, error) {

            }
        });
    }
});

</script>

@endsection
