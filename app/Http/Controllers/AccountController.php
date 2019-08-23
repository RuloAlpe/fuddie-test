<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Account::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return response()->json($account);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        if($account->type == 'Credito'){
            if($account->amount < 0){
                return response()->json([
                    'status' => 'error',
                    "amount" => $account->amount,
                    'message' => 'Tienes que pagar la cuenta antes de eliminar'
                ]);
            }else{
                if(Account::deleteAccount($account->id)){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'La cuenta se elimino correctamente'
                    ]);
                }
            }
        }else{
            if(Account::deleteAccount($account->id)){
                return response()->json([
                    'status' => 'success',
                    'message' => 'La cuenta se elimino correctamente'
                ]);
            }
        }
    }

    public function amount_account(Request $request){

        if(User::usuarioValido($request->input('api_token'))){
            if($account = Account::accountValida($request->input('id_account'))){
                return response()->json([
                    'status' => 'success',
                    'name' => $account->name,
                    'amount' => $account->amount,
                    'type' => $account->type,
                    'message' => 'Monto actual de la cuenta'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cuanta no encontrada'
                ]);
            } 
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }
    }

    // public function withdraw_credit_account(Request $request){
    //     $fee = .10;
        
    //     if($user = User::usuarioValido($request->input('api_token'))){
    //         if($account = Account::accountValida($request->input('id_account'))){

    //             $amount_withdraw = $request->input('amount_withdraw');
    //             if($amount_withdraw >= 10){
    //                 $comision = $amount_withdraw * $fee;
    //                 $total_withdraw = $amount_withdraw + $comision;
    //                 $new_amount = $account->amount - $total_withdraw;

    //                 if(Account::makeWithdrawCredit($account->id, $new_amount)){
    //                     return response()->json([
    //                         'status' => 'success',
    //                         'amount' => $new_amount,
    //                         'fee' => $comision,
    //                         'withdraw' => $amount_withdraw,
    //                         'message' => 'Monto actual de la cuenta'
    //                     ]);
    //                 }else{
    //                     return response()->json([
    //                         'status' => 'error',
    //                         'message' => 'Hubo un error, verificar que su cuenta sea de credito o intentelo mas tarde'
    //                     ]);
    //                 }
    //             }else{
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Monto del retiro tiene que ser mayo a $10 pesos'
    //                 ]);
    //             }
    //         }else{
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Cuanta no encontrada'
    //             ]);
    //         } 
    //     }else{
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Usuario no encontrado'
    //         ]);
    //     }
    // }

    public function withdraw_account(Request $request){
        
        if($user = User::usuarioValido($request->input('api_token'))){
            if($account = Account::accountValida($request->input('id_account'))){
                
                /**
                 * * Cuenta de credito
                 */
                if($account->type == 'Credito'){
                    $fee = .10;

                    $amount_withdraw = $request->input('amount_withdraw');
                    if($amount_withdraw >= 10){
                        $comision = $amount_withdraw * $fee;
                        $total_withdraw = $amount_withdraw + $comision;
                        $amount_total = $account->amount - $total_withdraw;

                        if(Account::makeWithdraw($user->id, $account->id, $amount_total, $total_withdraw)){
                            return response()->json([
                                'status' => 'success',
                                'amount' => $amount_total,
                                'fee' => $comision,
                                'withdraw' => $amount_withdraw,
                                'message' => 'Retiro de la cuenta'
                            ]);
                        }else{
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Hubo un error, intentelo mas tarde'
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Monto del retiro tiene que ser mayo a $10 pesos'
                        ]);
                    }
                }else{

                    /**
                     * * Cuenta de Debito 
                     */    
                    $amount_withdraw = $request->input('amount_withdraw');
                    if($amount_withdraw > 0){                    
                        if($amount_withdraw < $account->amount){
                            $new_amount = $account->amount + $amount_withdraw;
                            if(Account::makeWithdraw($user->id, $account->id, $new_amount, $amount_withdraw)){
                                return response()->json([
                                    'status' => 'success',
                                    'amount' => $new_amount,
                                    'withdraw' => $amount_withdraw,
                                    'message' => 'Monto actual de la cuenta'
                                ]);
                            }else{
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Hubo un error, intentelo mas tarde'
                                ]);
                            }
                        }else{
                            return response()->json([
                                'status' => 'error',
                                'message' => 'No tienes los fondos suficientes para hacer el retiro'
                            ]);
                        }
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'La cantidad a retirar debe de ser mayor a $1 peso o mas'
                        ]);
                    }
                }


            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cuanta no encontrada'
                ]);
            } 
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }
    }

    public function pay_account_credit(Request $request){
        if($user = User::usuarioValido($request->input('api_token'))){
            if($account = Account::accountValida($request->input('id_account'))){

                if($account->type == 'Credito'){
                    $amount_pay = $request->input('amount_pay');
                    $amount_total = $account->amount - $amount_pay;

                    if($account_change = Account::payCredito($user->id, $account->id, $amount_pay, $amount_total)){
                        return response()->json([
                            'status' => 'success',
                            'amount' => $account_change->amount,
                            'pay' => $amount_pay,
                            'message' => 'El pago de la cuenta se realizo correctamente'
                        ]);
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Hubo un error, intentelo mas tarde'
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'La cuenta no es de credito'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cuanta no encontrada'
                ]);
            } 
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }
    }

    public function deposit_account(Request $request){
        if($user = User::usuarioValido($request->input('api_token'))){
            if($account = Account::accountValida($request->input('id_account'))){

                if($account->type == 'Debito'){
                    $amount_deposit = $request->input('amount_deposit');
                    $amount_total = $account->amount + $amount_deposit;

                    if($account_change = Account::depositDebito($user->id, $account->id, $amount_deposit, $amount_total)){
                        return response()->json([
                            'status' => 'success',
                            'amount' => $account_change->amount,
                            'pay' => $amount_deposit,
                            'message' => 'El pago de la cuenta se realizo correctamente'
                        ]);
                    }else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Hubo un error, intentelo mas tarde'
                        ]);
                    }
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'La cuenta no es de debito'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cuanta no encontrada'
                ]);
            } 
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }
    }
}
