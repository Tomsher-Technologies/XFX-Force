<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuSection;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['sections.items','items'])->orderBy('sort_order')->get();
        return view('backend.menus.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $parent_id = $request->parent_id ?? null;
        $parent_type = $request->parent_type ?? null;

        if($parent_type === 'menu-section'){
            $section = new MenuSection();
            $section->menu_id = $parent_id;
            $section->title = $request->title;
            $section->save();
            return response()->json($section);
        }

        if($parent_type === 'section-item'){
            $item = new MenuItem();
            $item->menu_section_id = $parent_id;
            $item->title = $request->title;
            $item->link_type = $request->link_type;
            $item->link_value = $request->link_value;
            $item->save();
            return response()->json($item);
        }

        $menu = new Menu();
        $menu->title = $request->title;
        $menu->type = $request->type;
        $menu->link_type = $request->link_type;
        $menu->link_value = $request->link_value;
        $menu->save();
        return response()->json($menu);
    }

    public function edit($type, $id)
    {
        if($type=='menu') $data = Menu::find($id);
        elseif($type=='section') $data = MenuSection::find($id);
        else $data = MenuItem::find($id);

        return response()->json($data);
    }

    public function update(Request $request, $type, $id)
    {
        if($type=='menu'){
            $menu = Menu::findOrFail($id);
            $menu->title = $request->title;
            $menu->type = $request->type;
            $menu->link_type = $request->link_type;
            $menu->link_value = $request->link_value;
            $menu->save();
            return response()->json($menu);
        }

        if($type=='section'){
            $section = MenuSection::findOrFail($id);
            $section->title = $request->title;
            $section->link_type = $request->link_type;
            $section->link_value = $request->link_value;
            $section->save();
            return response()->json($section);
        }

        $item = MenuItem::findOrFail($id);
        $item->title = $request->title;
        $item->link_type = $request->link_type;
        $item->link_value = $request->link_value;
        $item->save();
        return response()->json($item);
    }

    public function destroy($type,$id)
    {
        if($type=='menu') Menu::findOrFail($id)->delete();
        elseif($type=='section') MenuSection::findOrFail($id)->delete();
        else MenuItem::findOrFail($id)->delete();

        return response()->json(['success'=>true]);
    }

    public function getItems($type)
    {
        if($type == 'product'){
            $items = \App\Models\Product::select('id','name')->where('published', 1)->get();
        } elseif($type == 'category'){
            $items = \App\Models\Category::select('id','name')->where('is_active', 1)->get();
        } elseif($type == 'brand'){
            $items = \App\Models\Brand::select('id','name')->where('is_active', 1)->get();
        } else {
            $items = [];
        }

        return response()->json($items);
    }

    public function updateOrder(Request $request)
    {
        $order = $request->order;
        $type = $request->type;

        foreach($order as $item){
            if($type == 'menu'){
                \App\Models\Menu::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            } elseif($type == 'section'){
                \App\Models\MenuSection::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            } elseif($type == 'item'){
                \App\Models\MenuItem::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        }

        return response()->json(['success'=>true]);
    }
}