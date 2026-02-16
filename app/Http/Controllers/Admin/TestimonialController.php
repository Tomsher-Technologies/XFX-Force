<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class TestimonialController extends Controller
{
    /**
     * Construct method.
     */
    function __construct()
    {
         $this->middleware('auth');

        $this->middleware('permission:manage_testimonials',  ['only' => ['index','destroy']]);
        $this->middleware('permission:add_testimonials',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_testimonials',  ['only' => ['edit','update']]);
    }

   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonials::orderBy('sort_order','asc')->paginate(15);

        return view('backend.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'type' => 'required|in:text,video',
            'comment' => 'required_if:type,text',
            'video_file' => 'required_if:type,video|mimes:mp4,mov,avi|max:102400', // 100MB max
            'status' => 'required|in:0,1',
            'sort_order' => 'nullable|integer',
        ]);

        $testimonial = new Testimonials();
        $testimonial->name = $request->name;
        $testimonial->sub_title = $request->sub_title;
        $testimonial->type = $request->type;
        $testimonial->status = $request->status;
        $testimonial->sort_order = ($request->sort_order != '') ? $request->sort_order : 0;
        if($request->type === 'text'){
            $testimonial->comment = $request->comment;
        } else if($request->type === 'video' && $request->hasFile('video_file')){
            $path = $request->file('video_file')->store('testimonials/videos', 'public');
            $testimonial->video_path = $path;
        }

        $testimonial->save();

        flash(trans('messages.testimonial') . ' ' . trans('messages.created_msg'))->success();
        return redirect()->route('testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonials $testimonial)
    {
        return view('backend.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonials $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'type' => 'required|in:text,video',
            'comment' => 'required_if:type,text',
            'video_file' => 'required_if:type,video|mimes:mp4,mov,avi|max:102400', // 100MB max
            'status' => 'nullable|in:0,1',
            'sort_order' => 'nullable|integer',
        ]);

        $testimonial->name = $request->name;
        $testimonial->sub_title = $request->sub_title;
        $testimonial->type = $request->type;
        $testimonial->status = $request->has('status') ? 1 : 0;
        $testimonial->sort_order = ($request->sort_order != '') ? $request->sort_order : 0;
        if($request->type === 'text'){
            $testimonial->comment = $request->comment;
            $testimonial->video_path = null;
        } else if($request->type === 'video' && $request->hasFile('video_file')){
            $path = $request->file('video_file')->store('testimonials/videos', 'public');
            $testimonial->video_path = $path;
            $testimonial->comment = null;
        }

        // Save the changes
        $testimonial->save();

        flash(trans('messages.testimonial') . ' ' . trans('messages.updated_msg'))->success();
        return redirect()->route('testimonials.index')->with([
            'status' => "Testimonial details updated successfully"
        ]);
    }


    public function destroy($id)
    {
        Testimonials::destroy($id);

        flash('Testimonial '.trans('messages.deleted_msg'))->success();
        return redirect()->route('testimonials.index');
    }

    public function updateStatus(Request $request)
    {
        $test = Testimonials::findOrFail($request->id);

        $test->status = $request->status;
        if ($test->save()) {
            return 1;
        }
        return 0;
    }

}
