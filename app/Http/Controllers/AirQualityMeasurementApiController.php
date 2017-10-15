<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AirQualityMeasurementApiGetAllRequest;
use App\Transformers\Api\AirQualityMeasurementResponse;
use App\Transformers\Api\MultipleAirQualityCollection;
use Illuminate\Support\Facades\Cache;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

class AirQualityMeasurementApiController extends Controller
{
    public function getAll(AirQualityMeasurementApiGetAllRequest $request)
    {
        $needleSource = [];

        $remoteSource = config('aqdc.remote_source');
        $availableSourceTypes = array_keys($remoteSource);

        if ($request->has('source')) {
            $source = explode(',', $request->input('source'));

            foreach ($source as $value) {
                if (in_array($value, $availableSourceTypes)) {
                    $needleSource[] = $value;
                }
            }
        } else {
            $needleSource = $availableSourceTypes;
        }

        $multiAQCollection = new MultipleAirQualityCollection();

        foreach ($needleSource as $sourceName) {
            $modelClass = $remoteSource[$sourceName]['model'];
            $collection = Cache::remember('model-' . $modelClass . ':latest', 5, function () use ($modelClass) {
                return $modelClass::with('site')->latest()->get();
            });
            $transformerClass = $remoteSource[$sourceName]['api_transformer'];
            $multiAQCollection->add($sourceName, $collection, new $transformerClass);
        }

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $resource = new Item($multiAQCollection, new AirQualityMeasurementResponse());

        $result = $manager->createData($resource)->toArray();

        return response()->json($result);
    }
}
