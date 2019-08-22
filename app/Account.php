<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    static function accountValida($id_account){
        $account = DB::table('accounts')->where('id', $id_account)->first();
        if($account)
            return $account;

        return false;
    }

    // static function makeWithdrawCredit($id_account, $amount){
    //     $account = DB::table('accounts')->where([
    //         ['id', '=', $id_account],
    //         ['type', 'like', 'Credito']
    //     ])->update(['amount' => $amount]);
    //     if($account)
    //         return true;

    //     return false;
    // }


    static function makeWithdraw($id_user, $id_account, $amount, $withdraw){
        $account = DB::table('accounts')->where('id', $id_account)->update(['amount' => $amount]);
        if($account){
            DB::table('transactions')->insert([
                "id_user" => $id_user,
                "id_account" => $id_account,
                "type" => "Retiro",
                "amount" => $withdraw
            ]);

            return true;
        }

        return false;
    }

    static function payCredito($id_user, $id_account, $amount_pay, $amount_total){
        DB::table('accounts')->where('id', $id_account)->update(['amount' => $amount_total]);
        $account = DB::table('accounts')->where('id', $id_account)->first();
        if($account){
            DB::table('transactions')->insert([
                "id_user" => $id_user,
                "id_account" => $id_account,
                "type" => "Pago",
                "amount" => $amount_pay
            ]);

            return $account;
        }

        return false;
    }

    static function depositDebito($id_user, $id_account, $amount_deposit, $amount_total){
        DB::table('accounts')->where('id', $id_account)->update(['amount' => $amount_total]);
        $account = DB::table('accounts')->where('id', $id_account)->first();
        if($account){
            DB::table('transactions')->insert([
                "id_user" => $id_user,
                "id_account" => $id_account,
                "type" => "Deposito",
                "amount" => $amount_deposit
            ]);

            return $account;
        }

        return false;
    }

    static function deleteAccount($id){
        if(DB::table('accounts')->where('id', $id)->delete())
            return true;

        return false;
    }
}
