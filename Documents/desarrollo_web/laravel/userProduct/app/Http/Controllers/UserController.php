<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use TheSeer\Tokenizer\Exception;

class UserController extends Controller
{
    public function index()
    {
       try {
            return response()->json([
               'Error' => false,
               'Users' => User::get()
           ]);
       } catch (Exception $e) {
            return response()->json([
                'Error' => true,
                'message' => "Error: {$e->getMessage()}"
            ],500);
       }
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
       try {
           $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
           ]);
           if($validator->fails()){
            return response()->json([
                   'Error' => true,
                   'message' => $validator->errors()
               ], 400);
           }

            $user = User::create(array_merge(
               $validator->validate(), [
                'name'=>strtoupper($request->name),
                'email'=>strtoupper($request->email),
                'password'=>bcrypt($request->password), 
               ]
           ));

           return response()->json([
               'Error' => false,
               'message' => 'Usuario registrado',
               'user' => $user
            ]);

       } catch (Exception $e) {
           return response()->json([
               'Error' => true,
               'message' => "Error: {$e->getMessage()}"
           ],500);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
             'name' => 'required',
            ]);
            if($validator->fails()){
             return response()->json([
                    'Error' => true,
                    'message' => $validator->errors()
                ], 400);
            }
 
            $user = User::findOrfail($id);
            $user->name = strtoupper($request->name);
            $user->save();
 
            return response()->json([
                'Error' => false,
                'message' => 'Actualizado',
                'user' => $user
            ]);
 
        } catch (Exception $e) {
            return response()->json([
                'Error' => true,
                'message' => "Error: {$e->getMessage()}"
            ],500);
        }
    }

    
    public function destroy($id)
    {
       try {
           $validateData = User::where('id','=',$id)->first();
           if(!isset($validateData->id)){
                return response()->json([
                    'Error' => false,
                    'Message' => 'Usuario no exitente',
                    $validateData
                ]);
           }
           $user = User::destroy($id);
            return response()->json([
                'Error' => false,
                'Message' => 'Usuario eliminado'
            ]);
       } catch (Exception $e) {
          return response()->json([
              'Error' => true,
              'Message' => "Error : ${$e->getMessage()}"
          ],500);
       }
    }
}
