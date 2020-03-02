<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Storage;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = $this->customer()->getCustomers($request->get('filter'), $request->get('search'));

        $filters = [
            'last_name' => 'Last Name',
            'first_name' => 'First Name',
            'email' => 'Email',
            'province' => 'Province',
            'city' => 'City'
        ];

        return view('pages.customer.customer_index')
            ->with('filters', $filters)
            ->with('customers', $customers);
    }

    public function show($customer_id)
    {
        $customer_details = $this->customer()->getCustomer($customer_id);
        $user_details = $customer_details->user()->get()->first();
        
        return view('pages.customer.customer_show')
            ->with('customer_details', $customer_details)
            ->with('user_details', $user_details);
    }

    public function print(Request $request)
    {
        $customers = $this->customer()->getCustomers($request->get('filter'), $request->get('search'));

        $configurations = $this->configuration()->getConfigurations();

        $data = [
            'customers' => $customers,
            'logo_url' => route('image', ['logo', 'logo.jpg']),
            'configurations' => $configurations
        ];

        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pages.customer.customer_report', compact('data'));
        
        $path = "/app/reports";
        $now = Carbon::now()->format('m-d-Y_h-i-sA');
        $filename = "[".$now."]-"."_Customer_List.pdf";
        $full_path = storage_path().$path."/".$filename;
        $pdf->save($full_path);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Printed Customer Report'
        ]);

        return Storage::disk('local')->download('reports/'.$filename);
    }
}
