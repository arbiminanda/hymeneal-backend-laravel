<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Confirmation;
use Illuminate\Support\Facades\Validator;

class ConfirmationController extends Controller
{
    public function greetings()
    {
        $confirmation = Confirmation::get(['id', 'name', 'greetings']);
        return response()->json([
            'data'      => $confirmation,
            'message'   => 'Berhasil mendapatkan data seluruh konfirmasi.',  
        ], 200);
    }

    public function index()
    {
        $confirmation = Confirmation::all();
        return response()->json([
            'data'      => $confirmation,
            'message'   => 'Berhasil mendapatkan seluruh salam hangat.',  
        ], 200);
    }

    public function create(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required|unique:confirmations',
            'greetings' => 'required',
            'presences'  => 'in:yes,no',
            'amount'  => 'in:1,2',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create user
        $confirmation = Confirmation::create([
            'name'      => $request->name,
            'greetings' => $request->greetings,
            'presences' => $request->presences,
            'amount'    => $request->amount,
        ]);

        //return response JSON user is created
        if($confirmation) {
            return response()->json([
                'data'      => $confirmation,
                'message'   => 'Berhasil melakukan konfirmasi.',  
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
            'message'   => 'Konfirmasi gagal, silakan coba kembali atau hubungi pengelola website.',
        ], 409);
    }
}

