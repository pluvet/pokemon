<?php

namespace App\Http\Controllers;

use App\Http\Resources\PokemonCollection;
use App\Models\Pokemon;
use App\Models\Pokemon as ModelsPokemon;
use App\Models\Team;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

function Request($name){

    $client = new Client();

    try {
        $res = $client->get("https://pokeapi.co/api/v2/pokemon/$name", [
            'headers' => [
            'Content-type' =>  'application/json'
            ]
        ]);


    } catch (Exception $e) {

       // $response = $e->getResponse();
       // $responseBodyAsString = json_decode($response->getBody()->getContents());
       // $responseBodyAsString = json_decode(json_encode($responseBodyAsString), true);
        abort(404, 'pokemon not found');
    }

    $res = json_decode($res->getBody()->getContents());
    $res = json_decode(json_encode($res), true);
    return $res;
}
class PokemonController extends Controller
{
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
    public function store(Request $request)
    {
        $this->pokemonExist($request->name);

        $pokemon = new Pokemon($request->all());

        $pokemon->save();

        return response([
            'res' => true,
            'data' => 'Pokemon created'
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function show(pokemon $pokemon)
    {
        return Request($pokemon->name);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function edit(pokemon $pokemon)
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
    public function update(Request $request, pokemon $pokemon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pokemon  $pokemon
     * @return \Illuminate\Http\Response
     */
    public function destroy(pokemon $pokemon)
    {
        $pokemon->delete();
    }

    private function pokemonExist($name){

        $pokemon = Request($name);

        if ($pokemon == NULL) {
            abort(404, 'pokemon not found');
        }
    }

}
