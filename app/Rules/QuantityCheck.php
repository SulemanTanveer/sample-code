<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product\Product;

class QuantityCheck implements Rule
{
    protected $product;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value < $this->product->quantity) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The quantity is in valid.';
    }
}
