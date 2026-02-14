<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductsExport;
use App\Models\ProductsImport;
use Auth;
use Excel;
use Illuminate\Http\Request;

class ProductBulkUploadController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:import_product',  ['only' => ['index', 'export', 'bulk_upload']]);
    }

    public function index()
    {
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {

            return view('backend.products.bulk_upload.index');
        }
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'products_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function bulk_upload(Request $request)
    {
        $request->validate([
            'bulk_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if ($request->hasFile('bulk_file')) {
            set_time_limit(1800);
            try {
                $import = new ProductsImport;
                Excel::import($import, request()->file('bulk_file'));
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                $failures = $e->failures();
                foreach ($failures as $failure) {
                    $failure->values();
                    $row = $failure->row();
                    $column = $failure->attribute();
                    $messages = implode(', ', $failure->errors());
                    flash("Row $row, Column $column: $messages")->error();
                }
                return back()->withInput();
            } catch (\Illuminate\Validation\ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        flash($message)->error();
                    }
                }
                return back()->withInput();
            }
        }
        return back();
    }
}
