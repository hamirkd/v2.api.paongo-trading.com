<?php

namespace App\Http\Controllers;
// require 'vendor/autoload.php';

use Illuminate\Http\Request;
use Binance;


class BinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $titre)
    {
        $api = new Binance\API("hSO8CYM9ekfuIoOdQ6BvLUdjHZVTZQTkKav4lUS97UeOn0LX87a8E6fkZr0WDVsN",
        "ky57SN7MygAIJq7CQ5lZm3EvAQzBSNz9LOBXE80euls3Bk2ElJA7QEEgH5Oo0huG");

        $ticker = $api->prices(); // Make sure you have an updated ticker object for this to work
        // print_r($ticker);
        $balances = $api->balances($ticker);
        //[available][onOrder][btcValue][btcTotal]
        $btcTotal=0;
        
        foreach($balances as $balance){
            $btcTotal += $balance['btcTotal'];
        }
        $btc = json_decode(file_get_contents("https://api.coinbase.com/v2/prices/BTC-USD/buy"));
        $usdTotal=0;
        if($btc!=null){
            $usdTotal=$btc->data->amount*$btcTotal;
        }
        
        echo json_encode(array("balance"=>$btcTotal,"balance_usd"=>$usdTotal));
        
    }
    
}
