<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokemonRequest;
use App\Http\Resources\PokemonCollection;
use App\Models\Pokemon;
use App\Models\Pokemon as ModelsPokemon;
use App\Models\Team;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

function Request($name){

    $client = new Client();

    try {
        $res = $client->get("https://pokeapi.co/api/v2/pokemon/$name", [
            'headers' => [
            'Content-type' =>  'application/json'
            ]
        ]);


    } catch (Exception $e) {
        abort(404, 'pokemon not found');
    }

    $res = json_decode($res->getBody()->getContents());
    $res = json_decode(json_encode($res), true);
    return $res;
}
class PokemonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pokemons = Team::find($request->team_id)->pokemons;

        return PokemonCollection::collection($pokemons);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PokemonRequest $request)
    {
        $this->pokemonExist($request->name);

        $pokemon = new Pokemon($request->all());

        $pokemon->save();

        return response($pokemon,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function show(pokemon $pokemon)
    {
        return $pokemon;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function edit(Pokemon $pokemon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pokemon $pokemon)
    {
        $team = Team::find($pokemon->team_id);
        $this->check($team->user_id);
        $pokemon->update($request->all());

        return response($pokemon,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pokemon $pokemon)
    {
        $team = Team::find($pokemon->team_id);
        $this->check($team->user_id);
        $pokemon->delete();

        return response('',204);
    }

    private function pokemonExist($name){

        $pokemon = Request($name);

        if ($pokemon == NULL) {
            abort(404, 'pokemon not found');
        }
    }

    private function check($user){

        if ($user != Auth::id()) {
            abort(400, 'unauthorized user');
        }
    }


}
