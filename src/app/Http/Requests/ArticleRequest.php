<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            // 空白とスラッシュ(/)は含めないようにする
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }


    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'tag' => 'タグ'
        ];
    }


    /**
     * json形式のtagを連想配列にする
     *
     * passedValidation
     * バリデーションが成功した後に呼ばれる。
     */
    public function passedValidation()
    {
        // collect型に変換
        $this->tags = collect(json_decode($this->tags))
            // 最初の5個
            ->slice(0, 5)
            // コレクションの各要素に対して順に処理を行い、新しいコレクションを作成
            ->map(function ($requestTag) {
                return $requestTag->text;
            });
    }
}
