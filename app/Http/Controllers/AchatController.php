<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achat;
use Illuminate\Support\Facades\Auth;


class AchatController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $achat = Achat::all();
        return $achat->where("supprimer",false);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Achat::create($request->all());
        return response()->json([
            'message' => 'Titre add',
            'status' => 200
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function show(Achat $achat)
    {
        return $achat;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Achat $achat)
    {
        $achat->update($request->all());
    }

    /**
     * Payer son titre
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function payer(Request $request, Achat $achat)
    {   $achat->etat_paiement="PAYE";
        $achat->date_paiement=new \DateTime('NOW');
        $achat->update($request->all());
        return response()->json([
            'message' => 'Le titre a été payé',
            'status' => 200
        ], 200);
    }

    /**
     * Payer son titre
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function supprimer(Request $request, Achat $achat)
    {   $achat->supprimer=true;
        $achat->save();
        return response()->json([
            'message' => 'Le titre a été supprimé',
            'status' => 200
        ], 200);
    }

    public function getforme(){
        Auth::user()->name;
        $achat = Achat::all();
        // die(auth()->user()->email);
        
        return response()->json($achat->where("supprimer",false)->where("emailadresse", auth()->user()->email));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Achat $achat)
    {
        $achat->delete();
    }
}

