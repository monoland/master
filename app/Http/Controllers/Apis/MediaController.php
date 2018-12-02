<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Media;

class MediaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->has('completed')) {
            return Media::storeChunk($request);
        }

        return Media::combineChunk($request);
    }

    public function download(Request $request, $filename)
    {
        return response()->download(storage_path('media' . DIRECTORY_SEPARATOR . $filename));
    }

    public function destroy($filename)
    {
        return Media::destroyMedia($filename);
    }
}
