<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\ClientRepository;
use App\Http\Resources\AppsCollection;
use Laravel\Passport\Passport;
use App\Http\Resources\AppsResource;

class AppsController extends Controller
{
    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    /**
     * Undocumented function
     *
     * @param ClientRepository $clients
     */
    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sortaz = $request->descending === 'true' ? 'desc' : 'asc';
        $sortby = $request->has('sortBy') ? $request->sortBy : null;
        $filter = $request->has('filter') ? $request->filter : null;

        if ($filter) {
            return new AppsCollection(
                Passport::client()->where('name', 'like', "%{$filter}%")->paginate($request->rowsPerPage)->makeVisible('secret')
            );
        } else {
            return new AppsCollection(
                Passport::client()->orderBy($sortby, $sortaz)->paginate($request->rowsPerPage)->makeVisible('secret')
            );
        }
    }

    /**
     * Store a new client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required|boolean',
        ]);

        return new AppsResource(
            $this->clients->create(null, $request->name, null, false, $request->type)->makeVisible('secret')
        );

        return $this->clients->create(
            null,
            $request->name,
            null,
            false,
            $request->type
        )->makeVisible('secret');
    }

    /**
     * Delete the given client.
     *
     * @param  Request  $request
     * @param  string  $clientId
     * @return Response
     */
    public function destroy($clientId)
    {
        $client = $this->clients->find($clientId);

        if (!$client) {
            return new Response('', 404);
        }

        return response()->json([
            'success' => $client->delete()
        ]);
    }
}
