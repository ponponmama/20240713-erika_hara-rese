<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
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
        return [
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {   
       return [
            'date.required' => '予約日付を入力してください。',
            'time.required' => '予約時刻を入力してください。',
            'number.required' => '予約人数を入力してください。',
        ];
    }
}
