<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\AirQualityMeasurementApiGetAllRequest;
use App\Transformers\Api\AirQualityMeasurementResponse;
use App\Transformers\Api\MultipleAirQualityCollection;
use App\Transformers\GeoJSON\GeoJSONSerializer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

class AirQualityMeasurementApiController extends Controller
{
    public function showAll(AirQualityMeasurementApiGetAllRequest $request)
    {
        $remoteSource = config('aqdc.remote_source');
        $needleSource = $this->needleSource($request);
        $collection = new MultipleAirQualityCollection();

        $groupMeasurements = $this->getLatestGroupMeasurementsBySources($needleSource);
        $groupMeasurements->each(function ($measurements, $source) use ($remoteSource, $collection) {
            $transformerClass = $remoteSource[$source]['api_transformer'];
            $collection->add($source, $measurements, new $transformerClass);
        });

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $resource = new Item($collection, new AirQualityMeasurementResponse());

        $result = $manager->createData($resource)->toArray();

        return response()->json($result);
    }

    public function getAllGeoJson(AirQualityMeasurementApiGetAllRequest $request)
    {
        $remoteSource = config('aqdc.remote_source');
        $needleSource = $this->needleSource($request);
        $collection = new MultipleAirQualityCollection(new GeoJSONSerializer());

        $groupMeasurements = $this->getLatestGroupMeasurementsBySources($needleSource);
        $groupMeasurements->each(function ($measurements, $source) use ($remoteSource, $collection) {
            $transformerClass = $remoteSource[$source]['point_transformer'];
            $collection->add($source, $measurements, new $transformerClass);
        });

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $resource = new Item($collection, new AirQualityMeasurementResponse());
        $resource->setResourceKey('measurements');

        // Sets source names in meta
        $data = $collection->all();
        $source = array_keys($data);
        $resource->setMeta(['source' => $source]);

        $result = $manager->createData($resource)->toArray();

        return response()->json($result);
    }

    protected function needleSource(AirQualityMeasurementApiGetAllRequest $request): array
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

        return $needleSource;
    }

    protected function getLatestGroupMeasurementsBySources(array $sources)
    {
        $remoteSource = config('aqdc.remote_source');
        $measurements = collect();

        foreach ($sources as $name) {
            $modelClass = $remoteSource[$name]['model'];
            $models = Cache::remember('model-' . $modelClass . ':latest', 5, function () use ($modelClass) {
                return $modelClass::with('site')->latest()->get();
            });

            $measurements->put($name, $models);
        }

        return $measurements;
    }
}
