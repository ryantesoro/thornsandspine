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

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Added New FAQ ID: '.$store_faq->id
        ]);

        Alert::success('Add FAQ Successful', 'Success!');
        return redirect()->route('admin.faq.index');
    }

    public function edit($faq_id)
    {
        if (!$this->faq()->faqExists($faq_id)) {
            Alert::error('Edit FAQ Failed', 'FAQ does not exist!');
            return redirect()->route('admin.faq.index');
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

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Updated FAQ ID: '.$faq_id
        ]);


        Alert::success('Update FAQ Successful', 'Success!');
        return redirect()->route('admin.faq.index');
    }

    public function destroy($faq_id)
    {
        if (!$this->faq()->faqExists($faq_id)) {
            Alert::error('Edit FAQ Failed', 'FAQ does not exist!');
            return redirect()->route('admin.faq.index');
        }

        $delete_faq = $this->faq()->updateFaq($faq_id, ['active' => 0]);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Hide FAQ ID: '.$faq_id
        ]);

        Alert::success('Hide FAQ Successful', 'Success!');
        return redirect()->route('admin.faq.index');
    }

    public function restore($faq_id)
    {
        if (!$this->faq()->faqExists($faq_id)) {
            Alert::error('Edit FAQ Failed', 'FAQ does not exist!');
            return redirect()->route('admin.faq.index');
        }

        $update_faq = $this->faq()->updateFaq($faq_id, ['active' => 1]);

        $store_logs = $this->logs()->storeLog([
            'user_id' => auth()->user()->id,
            'action' => 'Restore FAQ ID: '.$faq_id
        ]);

        Alert::success('Restore FAQ Successful', 'Success!');
        return redirect()->route('admin.faq.index');
    }
}
