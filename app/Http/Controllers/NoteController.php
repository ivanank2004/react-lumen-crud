<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // user yang terautentikasi otomatis tersedia via auth()->user()
    $user = $request->user();
    $notes = \App\Models\Note::where('user_id', $user->id)->get();

    return response()->json($notes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);

        $data = [
            'user_id' => $request->user()->id, 
            "title"=> $request->input("title"),
            'content' => $request->input('content'),
        ];

        Note::create($data);

        return response()->json($data);
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
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $note = Note::where('id', $id)
                    ->where('user_id', $request->user()->id)
                    ->first();
        
        return response()->json($note);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $this->validate($request, [
        'title' => 'required',
        'content' => 'required',
    ]);

    $note = Note::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();

    $note->update($request->only('title','content'));

    return response()->json([
        'message' => 'Note updated successfully',
        'data'    => $note
    ]);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $note = Note::where('id', $id)
                    ->where('user_id', $request->user()->id)
                    ->first();

        if (! $note) {
            return response()->json([
                'message' => 'Note tidak ditemukan atau Anda tidak memiliki akses.'
            ], 404);
        }

        $note->delete();

        return response()->json([
            'message' => 'Note berhasil dihapus'
        ]);
    }
}
