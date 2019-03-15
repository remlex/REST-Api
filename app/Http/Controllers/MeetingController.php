<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//// this is for file upload
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\File;

class MeetingController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $meetings = Meeting::all();
        foreach($meetings as $meeting){
            $meeting->view_meeting = [
                'href' => 'api/v1/meeting/'. $meeting->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'msg' => 'List of all Meeting',
            'meetings' => $mmeting
        ];
        return response()->json($response, 200);
    }

    public function storeUpload(Request $request){
        // request()->validate([
        //     'name' => 'required',
        //     'author' => 'required',
        // ]);
        // $cover = $request->file('bookcover');
        // $extension = $cover->getClientOriginalExtension();
        // Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));

        // $book = new Book();
        // $book->name = $request->name;
        // $book->author = $request->author;
        // $book->mime = $cover->getClientMimeType();
        // $book->original_filename = $cover->getClientOriginalName();
        // $book->filename = $cover->getFilename().'.'.$extension;
        // $book->save();

        // return redirect()->route('books.index')
        //     ->with('success','Book added successfully...');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
