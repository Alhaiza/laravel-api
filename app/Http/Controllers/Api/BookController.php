<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // query data seperti biasa 
        $books = Book::orderBy('title', 'asc')->get();

        // kembalikan nilai dengan format JSON
        return response()->json(
            [
                'status' => true,
                'message' => 'Books Data Found',
                'books' => $books,
            ],
            // status respond (buat sesuai dengan aturan respond yang berlaku)
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi Data Menggunakan Validator (Lebih Customable dibandingkan validasi biasa, cocok untuk API)
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'publish_date' => 'required|date',
            ],
            [
                'title.required' => 'Judul buku wajib diisi.',
                'author.required' => 'Penulis wajib diisi.',
                'publish_date.required' => 'Tanggal terbit wajib diisi.',
                'publish_date.date' => 'Tanggal terbit tidak valid.',
            ]
        );

        // Jika Validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                // mengirim error nya dimana
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create Buku Jika Berhasil
        $book = Book::create($validator->validated());

        // Jika berhasil
        return response()->json([
            'status' => true,
            'message' => 'Book created successfully.',
            'book' => $book,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Book Found',
            'book' => $book,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan.',
            ], 404);
        }

        // Validasi data
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'publish_date' => 'required|date',
            ],
            [
                'title.required' => 'Judul buku wajib diisi.',
                'author.required' => 'Penulis wajib diisi.',
                'publish_date.required' => 'Tanggal terbit wajib diisi.',
                'publish_date.date' => 'Tanggal terbit tidak valid.',
            ]
        );

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari buku berdasarkan ID


        // Update data buku
        $book->update($validator->validated());

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Data buku berhasil diperbarui.',
            'book' => $book,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => false,
                'message' => 'Data buku tidak ditemukan.',
            ], 404);
        }

        // Simpan data sebelum dihapus (optional)
        $deletedBook = $book;

        // Hapus data
        $book->delete();

        // Response sukses
        return response()->json([
            'status' => true,
            'message' => 'Data buku berhasil dihapus.',
            'book' => $deletedBook,
        ], 200);
    }
}
