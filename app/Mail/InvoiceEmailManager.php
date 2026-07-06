<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceEmailManager extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $array;

    public function __construct($array)
    {
        $this->array = $array;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->array['order'];
        
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        $font_family = "'Roboto','sans-serif'";
        
        $upload = \App\Models\Upload::find(get_setting('default_invoice_logo'));
        $imagePath = $upload && $upload->file_name
            ? public_path('storage/' . $upload->file_name)
            : null;

        $html = view('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align,
            'imagePath' => $imagePath
        ])->render();

        $html = shape_arabic_html($html);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);

        return $this->view($this->array['view'])
            ->from($this->array['from'], env('MAIL_FROM_NAME'))
            ->subject($this->array['subject'])
            ->with([
                'order' => $order
            ])
            ->attachData($pdf->output(), 'order-' . $order->code . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
