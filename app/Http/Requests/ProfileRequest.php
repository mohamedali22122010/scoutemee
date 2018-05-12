<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends Request
{
    //
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'full_name'=>'required',
            'profile_image'=>'image',
            'profile_url'=>'required|regex:/^[a-zA-Z0-9]*$/|unique:profiles,profile_url,'.$this->route()->getParameter('profile'),
            'location'=>'required',
            'gender'=>'required',
            'profile_video'=>'required',
        ];
        return $rules;
    }
}
