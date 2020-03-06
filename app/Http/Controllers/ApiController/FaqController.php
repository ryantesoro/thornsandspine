<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = $this->faq()->getFaqs();

        return response()->json([
            'success' => true,
            'data' => $faqs
        ]);
    }

    public function show($faq_id)
    {
        $faq = $this->faq()->getFaq($faq_id);

        return response()->json([
            'success' => true,
            'data' => $faq
        ]);
    }
}
