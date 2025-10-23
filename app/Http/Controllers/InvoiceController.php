<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = [
            ['title' => 'test title', 'description' => 'test', 'quantity' => 2, 'unit_price' => 150],
            ['title' => 'test title', 'description' => 'test 2', 'quantity' => 3, 'unit_price' => 100],
        ];
        $sumtotals = $this->calculate($invoices);
        $discount = 50;
        $final_total = $sumtotals - $discount;

        return view('invoice', compact( 'invoices', 'discount', 'sumtotals', 'final_total'));
    }

    public function generate(Request $request)
    {
        $invoices = json_decode($request->invoice, true);

        $sumtotals = $this->calculate($invoices);
        $discount = 50;
        $final_total = $sumtotals - $discount;

        $pdf = Pdf::loadView('invoice', compact('invoices' , 'discount', 'sumtotals', 'final_total' ))
            ->setPaper('a4')
            ->setOptions([
                'dpi' => 150,
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        $fileName = 'invoice_' . now()->timestamp . '.pdf';
        $path = 'invoices/' . $fileName;

        Storage::disk('public')->put($path, $pdf->output());
        return back()->with('pdf_path', $fileName );
    }


    public function download($file)
    {

        $path = storage_path('app/public/invoices/' . $file);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }



    private function calculate(array &$invoices)
    {
        foreach ($invoices as &$item) {
            $item['subtotal'] = $item['quantity'] * $item['unit_price'];
        }

        $sumtotals = collect($invoices)->sum('subtotal');

        return $sumtotals;
    }

}
