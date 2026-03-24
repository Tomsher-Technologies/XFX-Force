<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use Storage;

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
            'video_source' => 'nullable|required_if:type,video',
            'video_path' => 'nullable|required_if:video_source,upload|mimes:mp4,mov,avi',
            'youtube_link' => 'nullable|required_if:video_source,youtube',
            'youtube_embed' => 'nullable|required_if:video_source,youtube',
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
        } else if($request->type === 'video'){
            $testimonial->video_source = $request->video_source;

            if($request->video_source == 'upload' && $request->hasFile('video_path')) {
                $path = $request->file('video_path')->store('testimonials/videos', 'public');
                $testimonial->video_path = $path;
            }

            if($request->video_source == 'youtube') {
                $testimonial->common_link = $request->youtube_link;
                $testimonial->embed_link = $request->youtube_embed;
            }
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
            'type' => 'required|in:text,video',
            'sort_order'  => 'nullable|integer',
            'video_path'  => 'nullable|file|mimes:mp4,mov,avi,webm',
            'common_link' => 'nullable|url',
        ]);

        $testimonial->name = $request->name;
        $testimonial->sub_title = $request->sub_title;
        $testimonial->type = $request->type;
        $testimonial->sort_order = $request->sort_order ?? 0;
        $testimonial->status     = $request->status ?? 0;

        if ($request->type === 'text') {

            // Delete old video if exists
            if ($testimonial->video_path) {
                Storage::disk('public')->delete($testimonial->video_path);
            }

            $testimonial->comment       = $request->comment;
            $testimonial->video_source  = null;
            $testimonial->video_path    = null;
            $testimonial->common_link   = null;
            $testimonial->embed_link    = null;
        }

        if ($request->type === 'video') {

            $testimonial->video_source = $request->video_source;
            $testimonial->comment      = null;

            // NORMAL UPLOAD
            if ($request->video_source === 'upload') {
                if ($request->hasFile('video_path')) {
                    // Delete old file
                    if ($testimonial->video_path) {
                        Storage::disk('public')->delete($testimonial->video_path);
                    }
                    $path = $request->file('video_path')->store('testimonials/videos', 'public');
                    $testimonial->video_path = $path;
                }

                $testimonial->common_link = null;
                $testimonial->embed_link  = null;
            }

            // YOUTUBE
            if ($request->video_source === 'youtube') {
                // Delete uploaded file if switching from normal upload
                if ($testimonial->video_path) {
                    Storage::disk('public')->delete($testimonial->video_path);
                }

                $testimonial->video_path  = null;
                $testimonial->common_link = $request->common_link;
                $testimonial->embed_link  = $request->embed_link;
            }
        }

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
