<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use App\Models\SpecificationItem;
use Illuminate\Http\Request;

class SpecificationController extends Controller
{
    /**
     * Function to display a listing of the specifications.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $specifications = Specification::latest()->paginate(10);
        return view('backend.specifications.index', compact('specifications'));
    }

    /**
     * Function to store a newly created specification.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'main_title' => 'required',
            'display_title' => 'required',
            'status' => 'required'
        ]);

        Specification::create([
            'main_title' => $request->main_title,
            'display_title' => $request->display_title,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Saved successfully');
    }

    /**
     * Function to update an existing specification.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $spec = Specification::findOrFail($id);

        $spec->update([
            'main_title' => $request->main_title,
            'display_title' => $request->display_title,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Updated successfully');
    }

    /**
     * Function to show the form for editing a specification.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $specification = Specification::findOrFail($id);
        $items = SpecificationItem::with('subItems')
            ->where('main_specification_id', $id)
            ->where('parent_id', 0)
            ->get();

        return view('backend.specifications.edit', compact('specification', 'items'));
    }

    /**
     * Function to save specification details including hierarchical items.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveSpecificationDetails(Request $request, int $id)
    {
        $submittedLevel1Ids = [];

        if (isset($request->specifications['main']['children'])) {
            foreach ($request->specifications['main']['children'] as $level1Id => $child) {
                // ---- LEVEL 1 ----
                $level1 = SpecificationItem::updateOrCreate(
                    [
                        'id' => is_numeric($level1Id) ? $level1Id : null
                    ],
                    [
                        'main_specification_id' => $id,
                        'parent_id' => 0,
                        'title' => $child['title'] ?? null,
                        'display_title' => $child['display_title'] ?? null,
                        'status' => $child['status'] ?? 0,
                        'sort_order' => $child['sort_order'] ?? 0,
                    ]
                );

                $submittedLevel1Ids[] = $level1->id;
                $submittedLevel2Ids = [];

                // ---- LEVEL 2 ----
                if (isset($child['children'])) {
                    foreach ($child['children'] as $level2Id => $subChild) {
                        $level2 = SpecificationItem::updateOrCreate(
                            [
                                'id' => is_numeric($level2Id) ? $level2Id : null
                            ],
                            [
                                'main_specification_id' => $id,
                                'parent_id' => $level1->id,
                                'title' => $subChild['title'] ?? null,
                                'display_title' => $subChild['display_title'] ?? null,
                                'status' => $subChild['status'] ?? 0,
                                'sort_order' => $subChild['sort_order'] ?? 0,
                            ]
                        );

                        $submittedLevel2Ids[] = $level2->id;
                    }
                }

                // Delete removed LEVEL 2
                SpecificationItem::where('parent_id', $level1->id)
                    ->whereNotIn('id', $submittedLevel2Ids)
                    ->delete();
            }
        }

        // Delete removed LEVEL 1
        SpecificationItem::where('main_specification_id', $id)
            ->where('parent_id', 0)
            ->whereNotIn('id', $submittedLevel1Ids)
            ->delete();

        return back()->with('success', 'Specification saved successfully');
    }


    /**
     * Function to view specification details in a tree structure.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function viewSpecificationDetails(int $id)
    {
        $specification = Specification::findOrFail($id);
        $items = SpecificationItem::with('subItems')
            ->where('main_specification_id', $id)
            ->where('parent_id', 0)
            ->get();

        return view('backend.specifications.details', compact('items', 'specification'));
    }

    /**
     * Function to delete a specification.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Specification::findOrFail($id)->delete();
        Specification::destroy($id);

        return redirect()
            ->route('specifications.index')
            ->with('success', 'Specification deleted successfully');
    }
}
