<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\PcBuilderCategorySetting;
use App\Models\PcBuilderSubcategory;
use DB;

class PcBuilderCategorySettingController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
       
        $this->middleware('permission:manage_pc_builder',  ['only' => ['index','store']]);
    }
    public function index()
    {
        $allCategories = Category::with('childrenCategories')
            ->where('parent_id', 0)
            ->orderBy('name')
            ->get();

        $settings = PcBuilderCategorySetting::with('subcategories')
                                            ->orderBy('sort_order')
                                            ->get()
                                            ->keyBy('category_id');

        $categories = $allCategories->map(function($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'children' => $cat->childrenCategories->map(function($sub){
                    return ['id' => $sub->id, 'name' => $sub->name];
                })->toArray()
            ];
        });
        return view('backend.pc_builder.categories', compact('categories', 'settings'));
    }

    public function store(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die;
        DB::beginTransaction();

        try {

            foreach ($request->categories as $key => $category) {

                $categoryId = $category['category_id'] ?? $key;

                $setting = PCBuilderCategorySetting::updateOrCreate(
                    ['category_id' => $categoryId],
                    [
                        'min_select' => $category['min_select'] ?? 0,
                        'max_select' => $category['max_select'] ?? null,
                        'sort_order' => $category['sort_order'] ?? 0,
                        'has_subcategories' => isset($category['has_subcategories'])
                    ]
                );

                // Sync subcategories
                $setting->subcategories()->sync($category['subcategories'] ?? []);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Settings saved successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json([
                'status' => false,
                'message' => 'Failed to save settings'
            ]);
    }
}

