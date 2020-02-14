<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\User;
use App\Product;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        //Remove image size restriction
        ini_set('memory_limit','256M');
    }

    public function user()
    {
        $user = new User();
        return $user;
    }

    public function product()
    {
        $product = new Product();
        return $product;
    }
}
