<?php

namespace App\Http\Controllers\Api;

use App\Models\Pengusul;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PengusulController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return void
     */
    
    public function index()
    {        
        $posts = Pengusul::all();

        //return collection of posts as a resource
        return response()->json(new PostResource(true, 'List Data Pengusul', $posts));

    }

    /**
     * Store a newly created resource in storage
    * @param  mixed $request
    * @return void
    */
   public function store(Request $request)
   {
       //define validation rules
       $validator = Validator::make($request->all(), [            
           'namaLengkap' => 'required',
           'username' => 'required',
           'nik' => 'required',
           'nomorTelepon' => 'required',
           'jenisKelamin' => 'required',
           'email' => 'required',
           'password' => 'required',
       ]);

       //check if validation fails
       if ($validator->fails()) {
           return response()->json($validator->errors(), 422);
       }        

       //create pengusul
       $pengusul = Pengusul::create([         
        'namaLengkap' => $request->namaLengkap,        
        'username' => $request->username,                   
        'nik' => $request->nik,                   
        'nomorTelepon' => $request->nomorTelepon,                   
        'jenisKelamin' => $request->jenisKelamin,                   
        'email' => $request->email,                   
        'password' => $request->password,                   
       ]);

       //return response
       return response()->json(new PostResource(true, 'Data Pengusul Berhasil Ditambahkan!', $pengusul), 201);

   }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengusul = Pengusul::find($id);
        return response()->json(new PostResource(true, 'Detail data Pengusul!', $pengusul), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'namaLengkap' => 'required',
            'username' => 'required',
            'nik' => 'required',
            'nomorTelepon' => 'required',
            'jenisKelamin' => 'required',
            'email' => 'required|email', // Menambahkan validasi email
            'password' => 'nullable|min:8', // Mengatur password sebagai nullable
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the Pengusul by ID
        $pengusul = Pengusul::find($id);

        // Check if Pengusul exists
        if (!$pengusul) {
            return response()->json(['message' => 'Data Pengusul tidak ditemukan!'], 404);
        }

        // Update the Pengusul
        $pengusul->namaLengkap = $request->namaLengkap;
        $pengusul->username = $request->username;
        $pengusul->nik = $request->nik;
        $pengusul->nomorTelepon = $request->nomorTelepon;
        $pengusul->jenisKelamin = $request->jenisKelamin;
        $pengusul->email = $request->email;
        // Save the changes
        $pengusul->save();

        // Return a success response with the updated data
        return response()->json(new PostResource(true, 'Data Pengusul Berhasil Diperbarui!', $pengusul), 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengusul = Pengusul::find($id);        

        //delete pengusul
        $pengusul->delete();

        //return response
        return new PostResource(true, 'Data Pengusul Berhasil Dihapus!', null);

    }
}
