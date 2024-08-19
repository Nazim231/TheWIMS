<?php

namespace App\Http\Controllers\Employee;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{

    public function index()
    {
        try {
            $invoices = Invoice::withCount('products')->where('shop_id', $this->getShopId())->get();
            return view('employees.sell_history', compact('invoices'));
        } catch (\Throwable $th) {
            return redirect()->route('employee.home');
        }
    }

    public function getById($id)
    {
        $invoicesExists = Invoice::where(['id' => $id, 'shop_id' => $this->getShopId()])->exists();
        if ($invoicesExists) {
            $invoice = Invoice::with('products')->where('id', $id)->get()[0];
            return view('employees.invoice', compact('invoice'));
        } else {
            return redirect()->route('employee.invoices')->withErrors([
                'message' => 'Invalid invoice'
            ]);
        }
    }

    public function printInvoice($id)
    {
        $invoiceExists = Invoice::where(['id' => $id, 'shop_id' => $this->getShopId()])->exists();
        if ($invoiceExists) {
            $invoice = Invoice::with('products')->where('id', $id)->get()[0]->toArray();
            $invoice['current_date'] = Carbon::now()->format('d M, Y');
            Pdf::setOption(['defaultFont' => 'Ubuntu']);
            view()->share('invoice', $invoice);
            $pdf = Pdf::loadView('layouts.invoice', $invoice);
            $file_name = 'invoice-' . $invoice['id'] . '-' . Carbon::now()->toDateString() . '.pdf';
            return $pdf->download($file_name);
        } else {
            return redirect()->route('employee.invoices')->withErrors([
                'message' => 'Invoice with ID: #' . $id . ' doesn\'t exists',
            ]);
        }
    }

    private function getShopId(): int
    {
        $empAssignedToShop = Shop::where('emp_id', Auth::user()->id)->exists();
        if ($empAssignedToShop)
            return Shop::where('emp_id', Auth::user()->id)->get()[0]->id;
        else
            return -1;
    }
}
