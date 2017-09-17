<?php

namespace App\Http\Controllers;


use App\Http\Requests\Api\SitesApiGetAllRequest;
use App\Http\Resources\SiteResource;
use App\Site;
use App\Transformers\Api\SiteResponseTransformer;
use Illuminate\Support\Facades\Cache;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as ResouceCollection;
use League\Fractal\Serializer\ArraySerializer;

class SiteApiController extends Controller
{
    public function getAll(SitesApiGetAllRequest $request)
    {
        $sites = Cache::remember('model-site:all', 5, function () {
            return Site::all();
        });

        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        $resource = new ResouceCollection($sites, new SiteResponseTransformer);

        if ($request->has('include')) {
            $include = $request->input('include');
            $manager->parseIncludes($include);
        }

        $result = $manager->createData($resource)->toArray();

        return response()->json($result);

    }
}