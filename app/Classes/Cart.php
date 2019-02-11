<?php

namespace App\Classes;

use App\Models\Product\Product;

class Cart
{
	protected $inValidProductQuantity = [];
    /**
     * [validate cart product quantity]
     * @param [type] $product  [description]
     * @param [type] $quantity [description]
     */
	public function ValidateProductQuantity($product, $quantity)
	{
	    $notValidQuantity = Product::checkQuantity($product, $quantity);

        if ($notValidQuantity) {
            $this->notValidProduct($notValidQuantity);
        }
        return $this->inValidProductQuantity;
	}
    /**
     * [checking the quantity]
     * @param  [type] $product [description]
     * @return [type]          [description]
     */
    protected function notValidProduct($product)
    {
        if (empty($this->inValidProductQuantity)) {

            $this->inValidProductQuantity[0]['product_id'] = $product->id;
            $this->inValidProductQuantity[0]['valid_quantity']  = $product->quantity;

        } else {
        
            array_push($this->inValidProductQuantity, array(
                'product_id' =>  $product->id,
                'valid_quantity' => $product->quantity )
            );
        
        }
    }
}