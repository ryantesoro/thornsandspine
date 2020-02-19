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
            'product_id' => $request->post('product_id'),
            'pot_id' => $request->post('pot_id'),
            'quantity' => $request->post('quantity')
        ];

        $validator = Validator::make($cart_details, [
            'product_id' => 'required|exists:products,id',
            'pot_id' => 'required|exists:pots,id',
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $cart = $this->cart()->storeCart($cart_details);
        $customer->cart()->save($cart);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully added product to cart!'
        ]);
    }

    public function update(Request $request, $cart_id)
    {
        $cart_details = [
            'cart_id' => $cart_id,
            'quantity' => $request->post('quantity'),
            'pot_id' => $request->post('pot_id')
        ];

        $validator = Validator::make($cart_details, [
            'cart_id' => 'required|exists:carts,id',
            'pot_id' => 'required|exists:pots,id',
            'quantity' => 'required' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        unset($cart_details['cart_id']);
        $update_cart = $this->cart()->updateCart($cart_id, $cart_details);

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

        $this->setUserId(auth()->user()->id);
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);

        $cart_model = $this->cart()->getCartModel($cart_id);
        $cart_model->customer()->detach();

        $destroy_cart = $this->cart()->destroyCart($cart_id);

        return response()->json([
            'success' => true,
            'msg' => 'Successfully deleted product in cart!'
        ]);
    }

    public function clear(Request $request)
    {
        $this->setUserId(auth()->user()->id);
        $customer = $this->customer()->getCustomerDetailsByUser($this->user_id);

        $carts = $this->customer()->getCustomerCart($customer);

        foreach ($carts->get() as $cart) {
            $delete_cart = $this->cart()->destroyCart($cart->id);
        }

        $carts->detach();

        return response()->json([
            'success' => true,
            'msg' => 'Successfully cleared cart!'
        ]);
    }

    private function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}
