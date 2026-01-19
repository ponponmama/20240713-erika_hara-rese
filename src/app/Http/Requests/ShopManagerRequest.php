<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopManagerRequest extends FormRequest
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
            'description' => 'nullable|string|max:160',
            'open_time' => [
                'nullable',
                'date_format:H:i',
                'before:close_time',
            ],
            'close_time' => [
                'nullable',
                'date_format:H:i',
                'after:open_time',
            ],
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'description.max' => '店舗紹介は160文字以内で入力してください。',
            'open_time.before' => 'オープン時間はクローズ時間より前の時間を設定してください。',
            'close_time.after' => 'クローズ時間はオープン時間より後の時間を設定してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はjpeg, jpg, png, gif形式のみアップロード可能です。',
            'image.max' => '画像サイズは2MB以下にしてください。',
        ];
    }
}