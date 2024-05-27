<!-- resources/views/contacts/index.blade.php -->

@extends('layouts.app')
<?php
use Carbon\Carbon;

?>
@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>List</h2>
        <a href="{{ route('contents.create') }}" class="btn btn-primary">Add Vedio</a>
    </div>

    {{-- @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif --}}
    <div class="table-responsive">
        <div id="filter_table"></div>
        <table id="example" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><a href="{{ route('contents.index', ['sort_by' => 'title', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Title</a></th>
                    <th>Thumbnail</th>
                    <th><a href="{{ route('contents.index', ['sort_by' => 'teacher', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Teacher</a></th>
                    <th><a href="{{ route('contents.index', ['sort_by' => 'description', 'sort_direction' => request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}">Description</a></th>
                    <th>Video</th>

                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($contents as $key => $contact)
            <tr> 
                <td>{{ $key + 1 }}</td>
                <td id="title">{{ $contact->title }}</td>
                <td>
                    <img src="{{ asset('storage/' .  $contact->thumbnail)	 }}" alt="Thumbnail" style="width: 50px; height: 50px;">
                </td>
                <td id="teacher">{{ $contact->teacher }}</td>
                <td id="description">{{ $contact->description }}</td>
                
                <td>
                    @if (strpos($contact->video_link, 'iframe') !== false)
                        <button class="btn btn-primary view-video" data-video="{{ $contact->video_link }}">View</button>
                    @else
                        <a href="{{ $contact->video_link }}" target="__blank" class="btn btn-secondary">Open Link</a>
                    @endif
                </td>
                <td>{{ Carbon::parse($contact->created_at)->format('d-m-Y H:i:s') }}</td>

                <td>
                    <form action="{{ route('contents.destroy', $contact->id) }}" method="POST">
                        <a href="{{ route('contents.edit', $contact->id) }}" class="btn btn-primary">Edit</a>
        
                        @csrf
                        @method('DELETE')
        
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>

    {{-- {{ $contents->appends(request()->except('page'))->links('pagination::bootstrap-4') }} --}}
</div>

<!-- Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">TeacherTube</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="container-fluid">
                <h5  id="title_data"></h5>
                <h5  id="teacher_data"></h5>
                <div class="modal-body">
                    <div id="videoContainer" style="text-align: center;"></div>
                </div>
                <h5  id="description_data"></h5>
    
            </div>
        </div>
    </div>
</div>

<script> 
 $('.view-video').on('click', function() {
            var videoHtml = "'"+$(this).data('video')+ 'referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>'+"'";
            $('#videoModal').modal('show');
            $('#videoContainer').append(videoHtml); 
            $('#title_data').text('Title: '+$('#title').text()); 
            $('#teacher_data').text('Teacher: '+$('#teacher').text()); 
            $('#description_data').text('Description: '+$('#description').text()); 

            $('#videoContainer iframe').attr('src', function (i, val) {
                return val + "?autoplay=1";
            });
        });

        $('#videoModal').on('hidden.bs.modal', function() {
            $('#videoContainer').html(''); 
        });
</script>
@endsection
