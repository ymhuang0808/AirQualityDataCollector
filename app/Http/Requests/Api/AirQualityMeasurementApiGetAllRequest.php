<?php

namespace App\Http\Requests\Api;

use App\Site;
use Illuminate\Foundation\Http\FormRequest;

class AirQualityMeasurementApiGetAllRequest extends FormRequest
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
        /**
         * TODO: 1. Check the source exists or not
         */
        return [];
    }
}
