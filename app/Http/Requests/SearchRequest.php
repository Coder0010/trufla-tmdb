<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'genre_id'  => 'sometimes|nullable|exists:genres,id',
            'tmdb_type' => 'sometimes|nullable|in:'.implode(',', config('system.records_type'))
        ];
    }
}
