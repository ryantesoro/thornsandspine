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
        $faqs = Faq::select('*')->get()
            ->sortByDesc('id');
        
        return $faqs;
    }

    //Store Faq
    public function storeFaq($faq_details)
    {
        $store_faq = Faq::create($faq_details);

        return $store_faq;
    }
}
