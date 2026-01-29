<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\Product;
use Illuminate\Support\Str;
use DB;

class ServiceController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_services', ['only' => ['index','create','store','edit','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $services = Service::orderBy('sort_order', 'asc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $services = $services->where('name', 'like', '%' . $sort_search . '%');
        }
        $services = $services->paginate(15);
        return view('backend.services.index', compact('services', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);
       
        $slug               = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $slug               = Str::lower($slug);
        $same_slug_count    = Service::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix        = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug              .= $slug_suffix;

        $service                = new Service;
        $service->name          = $request->name ?? NULL;
        $service->slug          = $slug;
        $service->price         = $request->price;
        $service->image         = $request->image;
        $service->status        = $request->status;
        $service->sort_order    = $request->sort_order;
        $service->save();

        $service_translation                        = ServiceTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'service_id' => $service->id]);
        $service_translation->name                  = $request->name;
        $service_translation->description           = $request->description;
        $service_translation->meta_title            = $request->meta_title;
        $service_translation->meta_description      = $request->meta_description;
        $service_translation->meta_keywords         = $request->meta_keywords;
        $service_translation->og_title              = $request->og_title;
        $service_translation->og_description        = $request->og_description;
        $service_translation->twitter_title         = $request->twitter_title;
        $service_translation->twitter_description   = $request->twitter_description;
        $service_translation->save();

        flash('Service '.trans('messages.created_msg'))->success();
        return redirect()->route('service.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $service  = Service::findOrFail($id);
        return view('backend.services.edit', compact('service', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
        ]);

        $service = Service::findOrFail($id);

        $slug = '';
        if ($request->slug != null) {
            $slug = strtolower(Str::slug($request->slug, '-'));
            $same_slug_count = Service::where('slug', 'LIKE', $slug . '%')->where('id', '!=', $service->id)->count();
            $slug_suffix = $same_slug_count > 0 ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;
        }

        if ($request->lang == env("DEFAULT_LANGUAGE",'en')) {
            $service->name          = $request->name;
            $service->status        = $request->status;
            $service->slug          = $slug;
            $service->price         = $request->price;
            $service->image         = $request->image;
            $service->sort_order    = $request->sort_order;
            $service->save();
        }

        $service_translation                       = ServiceTranslation::firstOrNew(['lang' => $request->lang, 'service_id' => $service->id]);
        $service_translation->name                 = $request->name;
        $service_translation->description          = $request->description;
        $service_translation->meta_title           = $request->meta_title;
        $service_translation->meta_description     = $request->meta_description;
        $service_translation->meta_keywords        = $request->meta_keywords;
        $service_translation->og_title             = $request->og_title;
        $service_translation->og_description       = $request->og_description;
        $service_translation->twitter_title        = $request->twitter_title;
        $service_translation->twitter_description  = $request->twitter_description;
        $service_translation->save();

        flash(trans('messages.service').trans('messages.updated_msg'))->success();
        return redirect()->route('service.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service::destroy($id);

        flash(trans('messages.service').trans('messages.deleted_msg'))->success();
        return redirect()->route('service.index');
    }

    public function updateStatus(Request $request)
    {
        $service = Service::findOrFail($request->id);
        
        $service->status = $request->status;
        $service->save();
       
        return 1;
    }
}
