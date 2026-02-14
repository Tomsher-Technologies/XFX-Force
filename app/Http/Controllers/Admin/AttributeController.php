<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:manage_attributes',  ['only' => ['index','destroy']]);
        $this->middleware('permission:add_attribute',  ['only' => ['create','store']]);
        $this->middleware('permission:edit_attribute',  ['only' => ['edit','update']]);
    }

    /**
     * Function to display a listing of the attributes.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Attribute::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $attributes = $query
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('backend.attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new attribute.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.attribute.create');
    }

    /**
     * Function to store the newly created attributes.
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'is_active' => 'required',
            'values.*' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {

            // Save attribute
            $attribute = Attribute::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);

            // Save values
            if ($request->values) {
                foreach ($request->values as $index => $value) {
                    if ($value) {
                        AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $value,
                            'is_active' => $request->value_status[$index],
                        ]);
                    }
                }
            }
        });

        flash(trans('messages.attribute') . trans('messages.created_msg'))->success();
        return redirect()->route('attributes.index');
    }

    /**
     * Function to show the form for editing an attribute.
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);
        return view('backend.attribute.edit', compact('attribute'));
    }

    /**
     * Function to update an existing attribute.
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        DB::transaction(function () use ($request, $attribute) {

            $attribute->update([
                'name' => $request->name,
                'is_active' => $request->is_active
            ]);

            // Delete old values
            AttributeValue::where('attribute_id', $attribute->id)->delete();

            // Insert new
            foreach ($request->values as $i => $val) {
                if ($val) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $val,
                        'is_active' => $request->value_status[$i]
                    ]);
                }
            }
        });
        flash(trans('messages.attribute') . trans('messages.updated_msg'))->success();
        return redirect()->route('attributes.index', ['page' => $request->page]);
    }

    /**
     * Function to delete an attribute.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Attribute::findOrFail($id)->delete();
        Attribute::destroy($id);

        flash(trans('messages.attribute') . trans('messages.deleted_msg'))->success();
        return redirect()->route('attributes.index');
    }

    /**
     * Function to view attribute details.
     * 
     * @param int $id
     */
    public function show(int $id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);

        return view('backend.attribute.show', compact('attribute'));
    }
}
