<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = "faqs";

    protected $fillable = [
        'question', 'answer'
    ];

    public $timestamps = false;

    protected $hidden = ['pivot'];

    //Get Faqs
    public function getFaqs()
    {
        $faqs = Faq::select('*')->orderBy('id', 'DESC')->get();
        
        return $faqs;
    }

    //Get Faq (1 row)
    public function getFaq($faq_id)
    {
        $faq_details = Faq::select('*')->where('id', $faq_id)
            ->get()
            ->first();
        
        return $faq_details;
    }

    //Store Faq
    public function storeFaq($faq_details)
    {
        $store_faq = Faq::create($faq_details);

        return $store_faq;
    }

    //Update Faq
    public function updateFaq($faq_id, $faq_details)
    {
        $update_faq = Faq::where('id', $faq_id)->update($faq_details);

        return $update_faq;
    }

    //Check if Faq exists
    public function faqExists($faq_id)
    {
        $faq = Faq::find($faq_id)->get()
            ->count();
        
        return $faq != 0;
    }
}
