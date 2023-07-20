<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserUniqueRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $user = [];
    public function __construct($user = [])
    {
        $this->user = $user;
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
        $thismodel = User::where($attribute,$value);
        if(!empty($this->user->id)){
            $thismodel->where('id', '!=', $this->user->id);
        }
        if($thismodel->count() == 0){
            return true;
        }
       
    }


   

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This :attribute is already taken';
    }
}
