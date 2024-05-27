@extends('layouts.app')

@section('content')
<div class="container">
    <h3>
        @php
            if(isset($type) && $type == 'add') {
                echo 'Add Content';
            } else {
                echo 'Edit Content';
            }
        @endphp
    </h3>
    
    <div id="success-message" class="alert alert-success" style="display: none;"></div>

    <form id="ContentForm" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($type) && $type == 'edit')
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ isset($content->title) ? $content->title : '' }}" required>
            <div class="text-danger" id="titleError"></div>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ isset($content->description) ? $content->description : '' }}</textarea>
            <div class="text-danger" id="descriptionError"></div>
        </div>

        <div class="form-group">
            <label for="video_link">Video Link</label>
            <input type="text" name="video_link" id="video_link" class="form-control" value="{{ isset($content->video_link) ? $content->video_link : '' }}" required>
            <small class="form-text text-muted">Please enter a valid Vimeo link or iframe link.</small>
            <div class="text-danger" id="videoLinkError"></div>
        </div>

        <div class="form-group">
            <label for="teacher">Select Teacher</label>
            <select name="teacher" id="teacher" class="form-control" required>
                <option value="">--Select Option--</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher }}" @if(isset($type) && $type == 'edit' && $teacher == $content->teacher) selected @endif>{{ $teacher }}</option>
                @endforeach            
            </select>
            <div class="text-danger" id="teacherError"></div>
        </div>

        <div class="form-group">
            <label for="thumbnail">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
            @if (isset($content->thumbnail) && $content->thumbnail)
                <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="Thumbnail" style="width: 50px; height: 50px;">
            @endif
            <div class="text-danger" id="thumbnailError"></div>
        </div>
        <br>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#ContentForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: "@php echo $type == 'add' ? route('contents.store') : route('contents.update', $content->id) ; @endphp",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#ContentForm')[0].reset();
                }
                setTimeout(() => {
                    window.location.href = '@php echo $type == "edit" ? route("contents.index") : ""; @endphp';
                }, 2000);

            },
            error: function(response) {
                var errors = response.responseJSON.errors;
                $('#titleError').text(errors.title ? errors.title[0] : '');
                $('#descriptionError').text(errors.description ? errors.description[0] : '');
                $('#videoLinkError').text(errors.video_link ? errors.video_link[0] : '');
                $('#teacherError').text(errors.teacher ? errors.teacher[0] : '');
                $('#thumbnailError').text(errors.thumbnail ? errors.thumbnail[0] : '');
            }
        });
    });
});
</script>
@endsection
