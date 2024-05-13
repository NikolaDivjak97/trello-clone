<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string',
            'board_id' => 'required|exists:boards,id',
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string',
            "members" => 'nullable|array',
            "members.*" => 'required|exists:users,id',
            "difficulty" => 'required',
            "labels" => 'nullable|array',
            "labels.*" => 'required|exists:labels,id',
        ];
    }
}
