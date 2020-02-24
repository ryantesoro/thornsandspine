<?php

namespace App\Http\Controllers\WebController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = $this->faq()->getFaqs();
        
        return view('pages.faq.faq_index')->with('faqs', $faqs);
    }

    public function create()
    {
        return view('pages.faq.faq_create');
    }

    public function store(Request $request)
    {
        $faq_details = [
            'question' => $request->post('question'),
            'answer' => $request->post('answer')
        ];

        $validator = Validator::make($faq_details, [
            'question' => 'required',
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            Alert::warning('Add FAQ Failed', 'Invalid Input!');
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }

        $store_faq = $this->faq()->storeFaq($faq_details);

        Alert::success('Add FAQ Successful', 'Success!');
        return redirect()->route('admin.faq.index');
    }
}
