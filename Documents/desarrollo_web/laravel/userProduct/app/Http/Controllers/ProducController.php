<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Facades\Validator;


class ProducController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();
        try {
            return response()->json([
               'Error' => false,
               'Elementos' =>$user->products()->count(),
               'Productos' =>$user->products()->get()
           ]);
       } catch (Exception $e) {
            return response()->json([
                'Error' => true,
                'message' => "Error: {$e->getMessage()}"
            ],500);
       }
    }

   
    public function create()
    {
        
    }

  
    public function store(Request $request)
    {
        $user = Auth::user();
        try {
            
        $validator = Validator::make($request->all(),[
            'name' => 'required', 
            'price' => 'required'   
        ]);
        if($validator->fails()){
            return response()->json([
                'Error' => true,
                'message' => $validator->errors()
            ], 400);
        }

        $product = Product::create(array_merge(
            $validator->validate(), [
                'user_id' => $user->id,
                'name' => strtoupper($request->name), 
                'price' => $request->price 
            ]
        ));
 
        return response()->json([
            'Error' => false,
            'Message' => 'Peoducto Registrado',
            'produc' => $product
        ]);
        } catch (Exception $th) {
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Elimina todo los elementos que haya ingresado un usuraio, 
     * esto lo hace por medio de la relaccion 
     */
    public function destroy()
    {
        $user = Auth::user();
        try { 
            $product = $user->products()->delete();
            if($product == 0){
                return response()->json([
                    'Error' => false,
                    'Message' => 'No exiten productos en este momento',
                ]);
           }
             return response()->json([
                 'Error' => false,
                 'Message' => 'Produstos eliminado eliminado',
                 $product
             ]);
        } catch (Exception $e) {
           return response()->json([
               'Error' => true,
               'Message' => "Error : ${$e->getMessage()}"
           ],500);
        }
    }

    public function destroyParam($id)
    {
        $user = Auth::user();
        try {
           $product = $user->products()->where('id', $id)->delete();
           if($product == 0){
                return response()->json([
                    'Error' => false,
                    'Message' => 'Producto no exitente, por favor verifique el ID ',
                ]);
           }
             return response()->json([
                 'Error' => false,
                 'Message' => 'Usuario eliminado',
                 $product
             ]);
        } catch (Exception $e) {
           return response()->json([
               'Error' => true,
               'Message' => "Error : ${$e->getMessage()}"
           ],500);
        }
    }
}
