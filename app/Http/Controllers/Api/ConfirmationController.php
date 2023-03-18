<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Confirmation;
use Illuminate\Support\Facades\Validator;

class ConfirmationController extends Controller
{
    public function greetings(Request $request)
    {
        $useQueryParam = false;
        $sort_value = $request->query("sort");
        if (!empty($request->query())) {
            if ($sort_value == 'desc') {
                $confirmation = Confirmation::orderBy('id','desc')->get(['id', 'name', 'greetings']);
            } elseif ($sort_value == 'random') {
                $confirmation = Confirmation::inRandomOrder()->get(['id', 'name', 'greetings']);
            }
            $useQueryParam = true;
        }
        if ($useQueryParam == false) {
            $confirmation = Confirmation::get(['id', 'name', 'greetings']);
        }
        return response()->json([
            'data'      => $confirmation,
            'message'   => 'Berhasil mendapatkan seluruh salam hangat.',  
        ], 200);
    }

    public function index(Request $request)
    {
        $useQueryParam = false;
        $search_value = $request->query("search_name");
        $sort_value = $request->query("sort");
        if (!empty($request->query())) {
            if ($sort_value == 'desc') {
                $confirmation = Confirmation::where('name','LIKE','%'.$search_value.'%')->orderBy('id','desc')->get();    
                $useQueryParam = true;
            }
        }
        if ($useQueryParam == false) {
            $confirmation = Confirmation::where('name','LIKE','%'.$search_value.'%')->get();
        }
        return response()->json([
            'data'      => $confirmation,
            'message'   => 'Berhasil mendapatkan data seluruh konfirmasi.',  
        ], 200);
    }

    public function create(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'      => 'required|unique:confirmations',
            'greetings' => 'required',
            'presences'  => ['required', 'in:yes,no'],
            'amount'  => 'required_if:presences,=,yes',
        ]);
        
        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //validation if presences input is yes
        if (($request->input("presences") == 'yes') and (!in_array($request->input("amount"), array("1", "2")))) {
            return response()->json([
                'message' => 'Amount field is required and value should be 1 or 2'
            ], 422);
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

    public function update($id, Request $request)
    {
        Confirmation::where('id', $id)->update([
            'name'      => $request->name,
            'greetings' => $request->greetings,
            'presences' => $request->presences,
            'amount'    => $request->amount,
        ]);
        $confirmation = Confirmation::all();
        return response()->json([
            'data'      => $confirmation,
            'message'   => 'Berhasil memperbarui konfirmasi.',  
        ], 201);
    }

    public function delete($id)
    {
        Confirmation::where('id', $id)->delete();
        return response()->json([
            'message'   => 'Berhasil menghapus konfirmasi.',  
        ], 204);
    }
}

