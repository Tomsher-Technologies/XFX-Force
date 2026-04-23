<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use Cache;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
  
    public function index()
    {
        $sliders = HomeSlider::orderBy('sort_order', 'ASC')->paginate(15);
        return view('backend.home_sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('backend.home_sliders.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'slider_type' => 'required|in:image,video',
            'btn_text'    => 'nullable|string|max:255',
            'name' => 'required',
            'banner'        => 'required_if:slider_type,image',
            // 'mobile_banner' => 'required_if:slider_type,image',

            'video'         => 'required_if:slider_type,video',
            // 'mobile_video'  => 'required_if:slider_type,video',
            'link_type' => 'required',
            'status' => 'required',
            'link' => 'nullable|required_if:link_type,external',
            'link_ref_id' => 'nullable|required_if:link_type,product,category',
        ],[
            'link.required_if' => "Please enter a valid link",
            'link_ref_id.required_if' => "Please enter an option",
        ]);

        $slider = new HomeSlider();
        $slider->name        = $request->name;
        $slider->title        = $request->title;
        $slider->sub_title    = $request->sub_title;
        $slider->slider_type = $request->slider_type;
        $slider->btn_text    = $request->btn_text;
        $slider->link_type   = $request->link_type;
        $slider->sort_order  = $request->sort_order;
        $slider->status      = $request->status;

        if ($request->slider_type === 'image') {
            $slider->image        = $request->banner;
            $slider->mobile_image = $request->mobile_banner;
            $slider->video         = null;
            $slider->mobile_video  = null;
        }

        if ($request->slider_type === 'video') {
            $slider->video         = $request->video;
            $slider->mobile_video  = $request->mobile_video;
            $slider->image        = null;
            $slider->mobile_image = null;
        }

        if ($request->link_type === 'external') {
            $slider->link = $request->link;
            $slider->link_ref_id = null;
        } else {
            $slider->link = null;
            $slider->link_ref_id = $request->link_ref_id;
        }

        $slider->save();

        Cache::forget('homeSlider');

        flash(trans('messages.slider').' '.trans('messages.created_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    public function edit(HomeSlider $homeSlider)
    {
        return view('backend.home_sliders.edit', compact('homeSlider'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'slider_type' => 'required|in:image,video',
            'name'        => 'required',
            'btn_text'    => 'nullable|string|max:255',

            'banner'        => 'required_if:slider_type,image',
            // 'mobile_banner' => 'required_if:slider_type,image',

            'video'         => 'required_if:slider_type,video',
            // 'mobile_video'  => 'required_if:slider_type,video',

            'link_type'     => 'required',
            'link'          => 'nullable|required_if:link_type,external',
            'link_ref_id'   => 'nullable|required_if:link_type,product,category',
            'status'        => 'required',
        ]);

        $slider = HomeSlider::findOrFail($id);

        $slider->name        = $request->name;
        $slider->title       = $request->title;
        $slider->sub_title   = $request->sub_title;
        $slider->slider_type = $request->slider_type;
        $slider->btn_text    = $request->btn_text;
        $slider->link_type   = $request->link_type;
        $slider->sort_order  = $request->sort_order;
        $slider->status      = $request->status;

        if ($request->slider_type === 'image') {
            $slider->image        = $request->banner;
            $slider->mobile_image = $request->mobile_banner;
            $slider->video        = null;
            $slider->mobile_video = null;
        } else {
            $slider->video        = $request->video;
            $slider->mobile_video = $request->mobile_video;
            $slider->image        = null;
            $slider->mobile_image = null;
        }

        if ($request->link_type === 'external') {
            $slider->link = $request->link;
            $slider->link_ref_id = null;
        } else {
            $slider->link = null;
            $slider->link_ref_id = $request->link_ref_id;
        }

        $slider->save();

        Cache::forget('homeSlider');

        flash(trans('messages.slider').' updated successfully')->success();
        return redirect()->route('home-slider.index');
    }

   
    public function destroy($id)
    {
        HomeSlider::destroy($id);
        Cache::forget('homeSlider');
        flash(trans('messages.slider').' '.trans('messages.deleted_msg'))->success();
        return redirect()->route('home-slider.index');
    }

    public function updateStatus(Request $request)
    {
        $slider = HomeSlider::findOrFail($request->id);
        Cache::forget('homeSlider');
        $slider->status = $request->status;
        if ($slider->save()) {
            return 1;
        }
        return 0;
    }
}
