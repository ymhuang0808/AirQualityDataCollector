<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AggregationMeasurementsApiShowRequest extends FormRequest
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
            'start_datetime' => 'required|date_format:Y-m-d\TH:i:sO',
            'end_datetime' => 'required|date_format:Y-m-d\TH:i:sO|after:start_datetime',
            'period_type' => 'required|in:0,1',
            'limit' => 'numeric|max:365',
            'order' => 'in:asc,desc',
        ];
    }
}
