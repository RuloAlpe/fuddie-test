<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\User;
use App\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function by_user(Request $request){
        if($user = User::usuarioValido($request->input('api_token'))){
            $transactions = Transaction::getTransactionsUser($user->id);

            return response()->json([
                'status' => 'success',
                "count" => count($transactions),
                'transacciones' => $transactions
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ]);
        }
    }

    public function by_account(Request $request){
        if($account = Account::accountValida($request->input('id_account'))){
            $transactions = Transaction::getTransactionsAccount($account->id);

            return response()->json([
                'status' => 'success',
                "count" => count($transactions),
                'transacciones' => $transactions
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Cuenta no encontrada'
            ]);
        }
    }
}
