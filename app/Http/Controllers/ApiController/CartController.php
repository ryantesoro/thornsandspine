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
        $customer_model = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_cart = $this->customer()->getCustomerCart($customer_model)->get();

        return response()->json([
            'success' => true,
            'data' => $customer_cart
        ]);
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

        $customer_model = $this->customer()->getCustomerDetailsByUser($this->user_id);
        $customer_cart = $this->customer()->getCustomerCart($customer_model)->get();
        $is_duplicate = false;
        
        foreach ($customer_cart as $cart) {
            $existing_cart = $cart->getCart($customer_model, [
                'product_id' => $cart_details['product_id'],
                'pot_id' => $cart_details['pot_id']
            ]);

            if (!empty($existing_cart)) {
                $cart_id = $existing_cart->id;
                $quantity = $existing_cart->quantity + $cart_details['quantity'];
                $update_cart = $this->cart()->updateCart($cart_id, ['quantity' => $quantity]);
                $is_duplicate = true;
                break;
            }
        }

        if (!$is_duplicate) {
            $cart = $this->cart()->storeCart($cart_details);
            $customer_model->cart()->save($cart);
        }

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

        $customer_cart = $this->customer()->getCustomerCart($customer);

        $this->cart()->clearCart($customer_cart);

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
