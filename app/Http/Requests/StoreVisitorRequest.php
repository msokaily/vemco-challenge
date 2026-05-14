<?php

namespace App\Http\Requests;

use App\Models\Sensor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreVisitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'sensor_id' => ['required', 'integer', 'exists:sensors,id'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'count' => ['required', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $locationId = $this->input('location_id');
                $sensorId = $this->input('sensor_id');

                if (! $locationId || ! $sensorId) {
                    return;
                }

                $sensorBelongsToLocation = Sensor::query()
                    ->whereKey($sensorId)
                    ->where('location_id', $locationId)
                    ->exists();

                if (! $sensorBelongsToLocation) {
                    $validator->errors()->add(
                        'sensor_id',
                        'The selected sensor does not belong to the selected location.'
                    );
                }
            },
        ];
    }
}
