<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
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

    protected function prepareForValidation()
    {
        // セッションのdate_changedをチェックして、リクエストに追加
        $this->merge([
            'date_changed_session' => session('date_changed', false),
        ]);
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // セッションのdate_changedをチェック
            $dateChanged = $this->input('date_changed_session', false);

            // 日付が変更されていない場合（date_changedがfalseまたはセッションにない）はエラー
            if (!$dateChanged) {
                $validator->errors()->add('date', $this->messages()['date.date_not_selected']);
            }
        });
    }

    public function messages()
    {
        return [
            'date.required' => '予約日を選択してください。',
            'date.date_not_selected' => '予約日を選択してください。',
            'time.required' => '予約時刻を選択してください。',
            'number.required' => '予約人数を選択してください。',
        ];
    }
}
