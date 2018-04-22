<?php

namespace App\Http\Controllers;


use App\Http\Requests\Api\AirQualityMeasurementApiShowBySiteIdRequest;
use App\Http\Requests\Api\SitesApiShowAllRequest;
use App\Site;
use App\Transformers\Api\SiteResponseTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as ResourceCollection;
use League\Fractal\Resource\Item as ResourceItem;
use League\Fractal\Serializer\ArraySerializer;

class SiteApiController extends Controller
{
    protected $fractalManager;

    public function __construct()
    {
        $this->fractalManager = new Manager();
        $this->fractalManager->setSerializer(new ArraySerializer());
    }

    public function show(Request $request, $id)
    {
        $site = Cache::remember('model-site:' . $id, 5, function () use ($id) {
            return Site::findOrFail($id);
        });

        $resource = new ResourceItem($site, new SiteResponseTransformer);

        if ($request->has('include')) {
            $include = $request->input('include');
            $this->fractalManager->parseIncludes($include);
        }

        $result = $this->fractalManager->createData($resource)->toArray();

        return response()->json($result);
    }


    public function showAll(SitesApiShowAllRequest $request)
    {
        $sites = Cache::remember('model-site:all', 5, function () {
            return Site::all();
        });

        $resource = new ResourceCollection($sites, new SiteResponseTransformer);

        if ($request->has('include')) {
            $include = $request->input('include');
            $this->fractalManager->parseIncludes($include);
        }

        $result = $this->fractalManager->createData($resource)->toArray();

        return response()->json($result);
    }
}