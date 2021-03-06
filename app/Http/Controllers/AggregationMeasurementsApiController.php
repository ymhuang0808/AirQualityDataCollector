<?php

namespace App\Http\Controllers;


use App\Http\Requests\Api\AggregationMeasurementsApiShowRequest;
use App\Repository\Contracts\AggregationMeasurementRepositoryContract;
use App\Transformers\Api\AggregationMeasurementsTransformer;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

class AggregationMeasurementsApiController extends Controller
{
    protected $aggregationMeasurementRepository;

    public function __construct(AggregationMeasurementRepositoryContract $repository)
    {
        $this->aggregationMeasurementRepository = $repository;
    }

    public function show(AggregationMeasurementsApiShowRequest $request, $siteId)
    {
        $startDateTime = Carbon::parse($request->input('start_datetime'))->toDateTimeString();
        $endDatetime = Carbon::parse($request->input('end_datetime'))->toDateTimeString();
        $periodType = $request->input('period_type');
        $limit = $request->has('limit') ? $request->input('limit') : 30;
        $order = $request->has('order') ? $request->input('order') : 'asc';

        $site = $this->aggregationMeasurementRepository
            ->setSiteIdCondition($siteId)
            ->setPeriodTypeCondition($periodType)
            ->setStartDateTimeCondition($startDateTime)
            ->setEndDateTimeCondition($endDatetime)
            ->setLimit($limit)
            ->setOrderByDirection($order)
            ->get();

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());
        $resource = new Item($site, new AggregationMeasurementsTransformer);
        $result = $manager->createData($resource)->toArray();

        return response()->json($result);
    }
}