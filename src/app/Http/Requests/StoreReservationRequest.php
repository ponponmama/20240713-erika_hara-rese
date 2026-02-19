<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
            'date' => ['required', 'date', 'after_or_equal:' . Carbon::today()->toDateString()],
            'time' => 'required|date_format:H:i',
            'number' => 'required|integer|min:1',
        ];
    }

    /**
     * 日付はデフォルトで今日が表示されるため、
     * カレンダーで日付を選択したかどうかは date_acknowledged で判定する。
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('date') && $this->input('date_acknowledged') !== '1') {
                $validator->errors()->add('date', '予約日を選択してください。');
            }
        });
    }

    public function messages()
    {
        return [
            'date.required' => '予約日を選択してください。',
            'date.after_or_equal' => '予約日は今日以降の日付を選択してください。',
            'time.required' => '予約時刻を選択してください。',
            'time.date_format' => '予約時刻を選択してください。',
            'number.required' => '予約人数を選択してください。',
            'number.min' => '予約人数を選択してください。',
        ];
    }
}