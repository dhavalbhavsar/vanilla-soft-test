<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckFileName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if(empty($value)) 
            return true;

        // Means single object of attachment
        if(array_key_exists('file_name',$value)){
            if(empty($value['file_name']))
                return false;
            
            if (empty($value['file_name'])) {
              return false;
            }
        }

        // Means multiple object of attachment
        if(is_array($value) && !array_key_exists('file_name',$value)){
            foreach($value as $file){
                if (empty($file['file_name'])) {
                  return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The attachment file name must be required.';
    }
}
