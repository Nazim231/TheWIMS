<?php

namespace App\Http\Controllers\Employee;

use App\Models\Shop;
use App\Models\Invoice;
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

    private function getShopId(): int
    {
        return Shop::where('emp_id', Auth::user()->id)->get()[0]->id;
    }
}
