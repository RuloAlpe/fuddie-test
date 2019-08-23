<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    static function getTransactionsUser($id_user){
        $transacciones = DB::table('transactions')->where('id_user', $id_user)->get();

        return $transacciones;
    }

    static function getTransactionsAccount($id_account){
        $transacciones = DB::table('transactions')->where('id_account', $id_account)->get();

        return $transacciones;        
    }
}
