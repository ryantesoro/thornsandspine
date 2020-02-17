<?php

namespace App\Http\Controllers\ApiController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    private $user_id;

    public function index(Request $request)
    {
        $this->setUserId(auth()->user()->id);
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
    
        return $customer->cart()->get();
    }

    public function store(Request $request)
    {
        $this->setUserId(auth()->user()->id);

        $cart_details = [
            'product_code' => $request->post('code'),
            'quantity' => $request->post('quantity')
        ];

        $validator = Validator::make($cart_details, [
            'product_code' => 'required|exists:products,code',
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $cart = $this->cart()->storeCart($cart_details['quantity']);
        $customer->cart()->save($cart);

        $product = $this->product()->getProductModel($cart_details['product_code']);
        $product->cart()->save($cart);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully added product to cart!'
        ]);
    }

    public function update(Request $request, $cart_id)
    {
        $cart_details = [
            'cart_id' => $cart_id,
            'quantity' => $request->post('quantity')
        ];

        $validator = Validator::make($cart_details, [
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $update_cart = $this->cart()->updateCart($cart_details);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully updated cart!'
        ]);
    }

    public function destroy(Request $request, $cart_id)
    {
        $validator = Validator::make(['cart_id' => $cart_id], [
            'cart_id' => 'required|exists:carts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $destroy_cart = $this->cart()->destroyCart($cart_id);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully deleted product in cart!'
        ]);
    }

    

    private function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}
