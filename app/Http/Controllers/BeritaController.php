<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $berita = Berita::orderBy('time', 'DESC')->get();
        $response= [
            'message' => 'Daftar Berita order by time',
            'data' => $berita
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function tampil(){
        $berita = DB::table('beritas')->where('kategori', 'hiburan')->get();
        $response= [
            'message' => 'Daftar Berita order by hiburan',
            'data' => $berita
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    public function olahraga(){
        $berita = DB::table('beritas')->where('kategori', 'olahraga')->get();
        $response= [
            'message' => 'Daftar Berita order by olahraga',
            'data' => $berita
        ];
        return response()->json($response, Response::HTTP_OK);
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
        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required',
            'isi_berita' => 'required',
            'kategori' => ['required'],
            'image' => 'required'
            // 'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
            
        ]);

        // $request->file('image')->store('images');


        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $berita = Berita::create($request->all()); 
            // if($request->hasFile('image')){
            //     $image = $request->file('image');
            //     $ext = $image->extension();
            //     $file = time() . '.' . $ext;
            //     $image->storeAs('public/berita', $file);
            //     $berita->image = $file;
            // }
            $response = [
                'message' => 'Berita Created',
                'data' => $berita
            ];

            return response()->json($response, Response::HTTP_CREATED);
            
        }catch(QueryException $e){
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
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
        $berita = Berita::findOrFail($id);
        $response = [
            'message' => 'Detail Berita',
            'data' => $berita
        ];

        return response()->json($response, Response::HTTP_OK);

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

    public function views(Response $request, $id)
    {
        $berita = Berita::findOrFail($id);

        try{
            $berita->update($request->all()); 
            $response = [
                'message' => 'Berita Views Updated',
                'data' => $berita
            ];

            return response()->json($response, Response::HTTP_OK);
            
        }catch(QueryException $e){
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
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
        $berita = Berita::findOrFail($id);
        

        $validator = Validator::make($request->all(), [
            'judul_berita' => 'required',
            'isi_berita' => 'required',
            'kategori' => ['required', 'in:hiburan,ekonomi,olahraga,']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $berita->update($request->all()); 
            $response = [
                'message' => 'Berita Updated',
                'data' => $berita
            ];

            return response()->json($response, Response::HTTP_OK);
            
        }catch(QueryException $e){
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        try{
            $berita->delete(); 
            $response = [
                'message' => 'Berita Deleted',
            ];

            return response()->json($response, Response::HTTP_OK);
            
        }catch(QueryException $e){
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }
}
