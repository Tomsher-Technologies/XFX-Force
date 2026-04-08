<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\PcBuilderCategorySetting;
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

        $settings = PcBuilderCategorySetting::orderBy('sort_order')
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
        DB::beginTransaction();

        try {
            $submittedCategoryIds = [];

            foreach ($request->categories as $key => $category) {

                $categoryId = $category['category_id'] ?? $key;

                $submittedCategoryIds[] = $categoryId;

                $setting = PCBuilderCategorySetting::updateOrCreate(
                    ['category_id' => $categoryId],
                    [
                        'min_select' => $category['min_select'] ?? 0,
                        'max_select' => $category['max_select'] ?? null,
                        'sort_order' => $category['sort_order'] ?? 0,
                        'has_subcategories' => 0
                    ]
                );

            }

            PCBuilderCategorySetting::whereNotIn('category_id', $submittedCategoryIds)->delete();
            
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

