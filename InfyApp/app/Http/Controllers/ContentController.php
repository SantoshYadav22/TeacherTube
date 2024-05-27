<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $contents = Content::query();

        if ($request->has('sort_by')) {
            $contents->orderBy($request->get('sort_by'), $request->get('sort_direction', 'asc'));
        }

        // $contents = $contents->paginate(10);
        $contents = $contents->get();
        return view('contents.index', compact('contents'));
    }

    public function create()
    {
        $type = 'add';
        $teachers = ['Alexa', 'Jemini', 'Anna'];
        return view('contents.create', compact('teachers','type'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_link' => 'required',
            'teacher' => 'required|string|in:Alexa,Jemini,Anna',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = $thumbnail->store('thumbnails', 'public');
    
            // Save the thumbnail path to the database
            $data = $request->except('thumbnail');
            $data['thumbnail'] = $thumbnailPath;
    
            // Create and save the content
            Content::create($data);
    
            return response()->json(['success' => true, 'message' => 'Video added successfully.']);
        }
    
        return redirect()->back()->with('error', 'Thumbnail upload failed.');
    }

    // public function show(Content $content)
    // {
    //     return view('contents.show', compact('content'));
    // }

    public function edit(Content $content)
    {
        $teachers = ['Alexa', 'Jemini', 'Anna'];
        $type = 'edit';
        return view('contents.create', compact('content','teachers','type'));
    }

    public function update(Request $request, Content $content)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'video_link' => 'required',
            'teacher' => 'required|string|in:Alexa,Jemini,Anna',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $content->update($request->except('thumbnail'));

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailPath = $thumbnail->store('thumbnails', 'public');
    
            $content->thumbnail = $thumbnailPath;
            $content->save();
        }
        return response()->json(['success' => true, 'message' => 'Video updated successfully.']);
    }

    public function destroy(Content $content)
    {
        // Delete the content
        $content->delete();

        return redirect()->route('contents.index')->with('success', 'Content deleted successfully.');
    }
}
