<?php

namespace Modules\Finance\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Finance\app\Repository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = Repository::$invoices;
        $sumtotals = $this->calculate($invoices);
        $discount = 50;
        $final_total = $sumtotals - $discount;

        return view('finance::invoice', compact( 'invoices', 'discount', 'sumtotals', 'final_total'));
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
