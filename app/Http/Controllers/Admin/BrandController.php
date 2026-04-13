<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandSection;
use App\Models\BrandTab;
use App\Models\BrandTranslation;
use App\Models\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_brands',  ['only' => ['index','destroy']]);
        $this->middleware('permission:add_brand',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_brand',  ['only' => ['edit','update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $brands = Brand::orderBy('name', 'asc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%' . $sort_search . '%');
        }
        $brands = $brands->paginate(15);
        return view('backend.brands.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.brands.create');
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

        $brand = new Brand;
        $brand->name = $request->name ?? NULL;
        $brand->is_active = $request->is_active;
        if ($request->hasFile('logo')) {
            $brand->logo = $request->logo->store('brand/images', 'public');
        }
        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $slug = Str::lower($slug);
        $same_slug_count = Brand::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;
        $brand->slug = $slug;
        if(!empty($request->input('details'))){
            $brand->details = json_encode($request->input('details'));
        }
        $brand->save();

        // save brand seo
        $brand_translation                       = BrandTranslation::firstOrNew(['brand_id' => $brand->id]);
        $brand_translation->meta_title           = $request->meta_title;
        $brand_translation->meta_description     = $request->meta_description;
        $brand_translation->meta_keywords        = $request->meta_keywords;
        $brand_translation->og_title             = $request->og_title;
        $brand_translation->og_description       = $request->og_description;
        $brand_translation->twitter_title        = $request->twitter_title;
        $brand_translation->twitter_description  = $request->twitter_description;
        $brand_translation->save();

        // saving sections
        // if ($request->has('sections')) {
            
        //     foreach ($request->sections as $section) {

        //         // Skip completely empty section
        //         if (!empty($section['section_title'])) {
        //             $imagePath = "";
        //             if (isset($section['section_image']) && 
        //                 $section['section_image'] instanceof \Illuminate\Http\UploadedFile) {
        //                 $imagePath = $section['section_image']->store('brand_sections/images', 'public');
        //             }

        //             $brand->sections()->create([
        //                 'title' => $section['section_title'] ?? "",
        //                 'description' => $section['section_description'],
        //                 'status' => $section['section_status'] ?? 0,
        //                 'image' => $imagePath,
        //             ]);
        //         }
        //     }
        // }

        // saving Tabs
        if ($request->has('tabs')) {
            foreach ($request->tabs as $tab) {

                // Skip empty tab
                if (!empty($tab['tab_name'])) {
                    $tabImage = "";
                    if (isset($tab['tab_image']) && 
                        $tab['tab_image'] instanceof \Illuminate\Http\UploadedFile) {
                        $tabImage = $tab['tab_image']->store('brand_tabs/images', 'public');
                    }

                    $brand->tabs()->create([
                        'name' => $tab['tab_name'] ?? "",
                        'title' => $tab['tab_title'] ?? "",
                        'description' => $tab['tab_description'] ?? "",
                        'status' => $tab['tab_status'] ?? 0,
                        'sort_order' => $tab['tab_sort_order'] ?? 0,
                        'image' => $tabImage,
                    ]);
                }
            }
        }

        flash(trans('messages.brand') . trans('messages.created_msg'))->success();
        return redirect()->route('brands.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $brand  = Brand::findOrFail($id);
        $products = Product::where('published', 1)->get();
        return view('backend.brands.edit', compact('brand', 'lang', 'products'));
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

        $brand = Brand::findOrFail($id);

        $brand->name = $request->name ?? null;
        $brand->is_active = $request->is_active ?? 0;

        if ($request->hasFile('logo')) {
            $brand->logo = $request->logo->store('brand/images', 'public');
        }

        // Slug handling
        $slug = $request->slug
            ? Str::slug($request->slug, '-')
            : Str::slug($request->name, '-');

        $slug = Str::lower($slug);

        $same_slug_count = Brand::where('slug', 'LIKE', $slug . '%')
            ->where('id', '!=', $brand->id)
            ->count();

        if ($same_slug_count > 0) {
            $slug .= '-' . ($same_slug_count + 1);
        }

        $brand->slug = $slug;

        $brand->products = $request->products
            ? json_encode($request->products)
            : null;
        if(!empty($request->input('details'))){
            $brand->details = json_encode($request->input('details'));
        }
        $brand->save();

        // saving seo details
        $brand_translation                       = BrandTranslation::firstOrNew(['brand_id' => $brand->id]);
        $brand_translation->meta_title           = $request->meta_title;
        $brand_translation->meta_description     = $request->meta_description;
        $brand_translation->meta_keywords        = $request->meta_keywords;
        $brand_translation->og_title             = $request->og_title;
        $brand_translation->og_description       = $request->og_description;
        $brand_translation->twitter_title        = $request->twitter_title;
        $brand_translation->twitter_description  = $request->twitter_description;
        $brand_translation->save();

        // saving sections

        $existingSectionIds = [];
        if ($request->has('sections')) {
            foreach ($request->sections as $index => $section) {
                $sectionId = $section['id'] ?? null;

                // Handle Image
                $imagePath = null;
                if ($request->hasFile("sections.$index.section_image")) {
                    $imagePath = $request->file("sections.$index.section_image")
                        ->store('brand_sections/images', 'public');
                }

                if ($sectionId) {
                    // Update existing section
                    $existingSection = BrandSection::find($sectionId);
                    if ($existingSection) {
                        $existingSection->update([
                            'title'       => $section['section_title'] ?? "",
                            'description' => $section['section_description'] ?? "",
                            'status'      => $section['section_status'] ?? 0,
                            'image'       => $imagePath ?? $existingSection->image,
                        ]);
                        $existingSectionIds[] = $existingSection->id;
                    }

                } else {
                    // Create new section
                    $newSection = $brand->sections()->create([
                        'title'       => $section['section_title'] ?? "",
                        'description' => $section['section_description'] ?? "",
                        'status'      => $section['section_status'] ?? 0,
                        'image'       => $imagePath ?? "",
                    ]);

                    $existingSectionIds[] = $newSection->id;
                }
            }
        }

        // Delete sections that were removed in the form
        $brand->sections()
            ->whereNotIn('id', $existingSectionIds)
            ->delete();

        // saving Tabs

        $existingTabIds = [];
        if ($request->has('tabs')) {
            foreach ($request->tabs as $index => $tab) {
                $tabId = $tab['id'] ?? null;
                $imagePath = null;
                if ($request->hasFile("tabs.$index.tab_image")) {
                    $imagePath = $request->file("tabs.$index.tab_image")
                        ->store('brand_tabs/images', 'public');
                }

                if ($tabId) {
                    $existingTab = BrandTab::find($tabId);
                    if ($existingTab) {
                        $existingTab->update([
                            'name'        => $tab['tab_name'] ?? "",
                            'title'       => $tab['tab_title'] ?? "",
                            'description' => $tab['tab_description'] ?? "",
                            'status'      => $tab['tab_status'] ?? 0,
                            'image'       => $imagePath ?? $existingTab->image,
                        ]);

                        $existingTabIds[] = $existingTab->id;
                    }

                } else {

                    $newTab = $brand->tabs()->create([
                        'name'        => $tab['tab_name'] ?? "",
                        'title'       => $tab['tab_title'] ?? "",
                        'description' => $tab['tab_description'] ?? "",
                        'status'      => $tab['tab_status'] ?? 0,
                        'image'       => $imagePath ?? "",
                    ]);

                    $existingTabIds[] = $newTab->id;
                }
            }
        }

        $brand->tabs()
            ->whereNotIn('id', $existingTabIds)
            ->delete();

        flash(trans('messages.brand') . trans('messages.updated_msg'))->success();

        return redirect()->route('brands.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        Product::where('brand_id', $brand->id)->delete();
        foreach ($brand->brand_translations as $key => $brand_translation) {
            $brand_translation->delete();
        }
        Brand::destroy($id);

        flash(trans('messages.brand').trans('messages.deleted_msg'))->success();
        return redirect()->route('brands.index');
    }

    public function updateStatus(Request $request)
    {
        $brand = Brand::findOrFail($request->id);
        
        $brand->is_active = $request->status;
        $brand->save();
       
        return 1;
    }
}
