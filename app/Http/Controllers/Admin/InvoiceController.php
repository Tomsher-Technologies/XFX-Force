<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Upload;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    
    public function invoice_download($id)
    {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        
        $font_family = "'Roboto','sans-serif'";
        $order = Order::findOrFail($id);

        set_time_limit(300);

        $upload = Upload::find(get_setting('default_invoice_logo'));

        $imagePath = $upload && $upload->file_name
            ? public_path('storage/' . $upload->file_name)
            : null;

        $pdf = Pdf::loadView('backend.invoices.invoice', [
                    'order' => $order,
                    'font_family' => $font_family,
                    'direction' => $direction,
                    'text_align' => $text_align,
                    'not_text_align' => $not_text_align,
                    'imagePath' => $imagePath
                ]);
        
        return $pdf->download('order-' . $order->code . '.pdf');
    }
}
