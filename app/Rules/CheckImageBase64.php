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
        if(array_key_exists('base64_file',$value)){

            if(empty($value['base64_file']))
                return false;

            if (strpos($value['base64_file'], ';base64') !== false) {
                [, $value['base64_file']] = explode(';', $value['base64_file']);
                [, $value['base64_file']] = explode(',', $value['base64_file']);
            }

            if (base64_decode($value['base64_file'], true) === false) {
              return false;
            }
        }

        // Means multiple object of attachment
        if(is_array($value) && !array_key_exists('base64_file',$value)){
            foreach($value as $file){
                if(empty($file['base64_file']))
                    return false;

                if (strpos($file['base64_file'], ';base64') !== false) {
                    [, $file['base64_file']] = explode(';', $file['base64_file']);
                    [, $file['base64_file']] = explode(',', $file['base64_file']);
                }
                
                if (base64_decode($file['base64_file'], true) === false) {
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
