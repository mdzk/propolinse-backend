<?php

namespace App\Http\Controllers\Produk;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use App\Http\Resources\BarangResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ControllerBarang extends Controller
{
    public function index()
    {

        $barang = Barang::all();
        return response()->json(['data' => $barang]);
        //return BarangResource::collection($barang);
    }
    public function show($id)
    {
        $barang1 = Barang::findOrfail($id);
        return response()->json(['data' => $barang1]);
        //return new BarangResource($barang1);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kd_brg' => 'required|unique:barang|numeric',
            'hrg_brg' => 'required',
            'stok' => 'nullable',
            'nm_brg' => 'required',
            'tag' => 'required',
            'type_size' => 'required',
            'ket_brg' => 'required',
            'desk_umum' => 'required',
            'berat_brg' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:5048',
            'jenis_brg' => 'required',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $image = Barang::create([
            'image'     => $image->hashName(),
            'kd_brg'     => $request->kd_brg,
            'hrg_brg'     => $request->hrg_brg,
            'stok'     => $request->stok,
            'nm_brg'     => $request->nm_brg,
            'tag'     => $request->tag,
            'ket_brg'     => $request->ket_brg,
            'desk_umum'     => $request->desk_umum,
            'berat_brg'     => $request->berat_brg,
            'jenis_brg'     => $request->jenis_brg,
            'type_size'     => $request->type_size,

        ]);

        //return response($image, Response::HTTP_CREATED);

        return [
            'message' => 'Berhasil Upload Barang'
        ];
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'kd_brg' => "required|unique:barang,kd_brg,$id",
            'hrg_brg' => 'required',
            'stok' => 'nullable',
            'nm_brg' => 'required',
            'ket_brg' => 'required',
            'desk_umum' => 'required',
            'berat_brg' => 'required',
            'jenis_brg' => 'required',
        ]);


        //$this->authorize('update', $barang);
        $post = Barang::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {
            $validator = Validator::make($request->all(), [
                'image' => 'required|file|mimes:jpg,png,jpeg,gif,svg|max:5048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors(),
                ], 422);
            }
            //upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/' . basename($post->image));

            //update post with new image
            $post->update([
                'image'     => $image->hashName(),
                'kd_brg'     => $request->kd_brg,
                'hrg_brg'     => $request->hrg_brg,
                'stok'     => $request->stok,
                'nm_brg'     => $request->nm_brg,
                'tag'     => $request->tag,
                'ket_brg'     => $request->ket_brg,
                'desk_umum'     => $request->desk_umum,
                'type_size'     => $request->type_size,
                'berat_brg'     => $request->berat_brg,
                'jenis_brg'     => $request->jenis_brg,
            ]);
        } else {

            //update post without image
            $post->update([
                'title'     => $request->title,
                'kd_brg'     => $request->kd_brg,
                'hrg_brg'     => $request->hrg_brg,
                'stok'     => $request->stok,
                'nm_brg'     => $request->nm_brg,
                'tag'     => $request->tag,
                'ket_brg'     => $request->ket_brg,
                'desk_umum'     => $request->desk_umum,
                'type_size'     => $request->type_size,
                'berat_brg'     => $request->berat_brg,
                'jenis_brg'     => $request->jenis_brg,
                //'content'   => $request->content,
            ]);
        }

        //return response
        //return response($image, Response::HTTP_CREATED);

        return [
            'message' => 'Berhasil update barang'
        ];
    }

    //MENGHAPUS BARANG DENGAN NO ID
    public function destroy($id)
    {
        // Cari data berdasarkan ID
        $data = Barang::find($id);
        //$data = 'public/posts';
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        //$gambar = 'public/posts'; // Ganti dengan path gambar yang sesuai
        Storage::delete('public/posts/' . $data->image);

        // Lakukan penghapusan
        $data->delete();

        return response()->json(['message' => 'Data Barang berhasil dihapus'], 200);
    }


    //Menampilkan Kategori Barang Berdasarkan TYPE DAN SIZE NYA
    public function kategori(Request $request)
    {
        $categoryName = $request->input('category_name');

        if (!$categoryName) {
            return response()->json(['error' => 'kategori yang anda cari tidak ada!'], 400);
        }

        $products = Barang::where('type_size', $categoryName)->get();

        return response()->json(['barang' => $products]);
    }


    //MENAMPILKAN JUMLAH BARANG YANG ADA
    public function hitungJumlahBarang()
    {
        // Menggunakan Query Builder untuk menghitung jumlah barang
        $jumlahBarang = DB::table('barang')->count();

        return response()->json(['jmlh_products' => $jumlahBarang]);
    }


    //UPDATE GAMBAR
    public function gbrupdate(Request $request, $id)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            //'title'     => 'required',
            //'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $post = Barang::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/' . basename($post->image));

            //update post with new image
            $post->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                //'content'   => $request->content,
            ]);
        } else {

            //update post without image
            $post->update([
                'title'     => $request->title,
                //'content'   => $request->content,
            ]);
        }
        return [
            'message' => 'Berhasil mengubah gambar barang'
        ];
    }
}
