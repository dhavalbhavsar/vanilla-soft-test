<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckImageBase64 implements Rule
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
        if(array_key_exists('base64_image',$value)){

            if(empty($value['base64_image']))
                return false;

            if (strpos($value['base64_image'], ';base64') !== false) {
                [, $value['base64_image']] = explode(';', $value['base64_image']);
                [, $value['base64_image']] = explode(',', $value['base64_image']);
            }

            if (base64_decode($value['base64_image'], true) === false) {
              return false;
            }
        }

        // Means multiple object of attachment
        if(is_array($value) && !array_key_exists('base64_image',$value)){
            foreach($value as $file){
                if(empty($file['base64_image']))
                    return false;

                if (strpos($file['base64_image'], ';base64') !== false) {
                    [, $file['base64_image']] = explode(';', $file['base64_image']);
                    [, $file['base64_image']] = explode(',', $file['base64_image']);
                }
                
                if (base64_decode($file['base64_image'], true) === false) {
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
        return 'The attachment must be base64 encoded.';
    }
}
