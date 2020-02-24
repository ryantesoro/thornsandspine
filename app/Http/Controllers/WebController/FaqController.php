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

    public function edit($faq_id)
    {
        if (!$this->faq()->faqExists($faq_id)) {
            Alert::error('Edit FAQ Failed', 'FAQ does not exist!');
            return redirect()->route('admin.pot.index');
        }

        $faq_details = $this->faq()->getFaq($faq_id);

        return view('pages.faq.faq_edit')->with('faq_details', $faq_details);
    }

    public function update(Request $request, $faq_id)
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
        
        $update_faq = $this->faq()->updateFaq($faq_id, $faq_details);

        Alert::success('Update FAQ Successful', 'Success!');
        return redirect()->route('admin.faq.index');
    }
}
