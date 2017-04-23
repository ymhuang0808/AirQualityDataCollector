<?php

namespace App\Http\Controllers;


use App\Http\Requests\Api\GetAllSitesApiRequest;
use App\Site;
use App\Transformers\Api\SiteResponseTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection as ResouceCollection;
use League\Fractal\Serializer\ArraySerializer;

class SiteApiController extends Controller
{
    public function getAll(GetAllSitesApiRequest $request)
    {
        $sites = Site::all();

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