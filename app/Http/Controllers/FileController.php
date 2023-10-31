<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\File;
use App\Models\Option;
use App\Models\Pin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role->tag != 'administrator') abort(404);
        $files = File::query()->with('access')->where('user_id', '<>', Auth::user()->id)->orderBy('user_id')->paginate(10);
        
        return view('files.index', compact('files'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accesses = Access::query()->pluck('title', 'id')->all();
        $userList = User::select("id", DB::raw("CONCAT(users.surname, ' ', users.name) as full_name"))->where('id', '<>', Auth::user()->id)->pluck('full_name', 'id');
        return view('files.create', compact('accesses', 'userList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'access_id' => 'required|integer',
            'file' => 'required|max:104857600',
        ]);

        $folder = date('Y-m-d');
        $data = $request->all();
        $data['href'] = $request->file('file')->store("files/{$folder}");
        $data['user_id'] = Auth::user()->id;
        $data['original_name'] = $request->file('file')->getClientOriginalName();
        $data['type'] = $request->file('file')->getClientMimeType();
        $data['file_extension'] = $request->file('file')->getClientOriginalExtension();
        $data['file_size'] = $request->file('file')->getSize();

        $file = File::create($data);

        if (isset($request->pin)) {
            $pinCodes = $this->pinskGeneration($request->type_pin == 0 ? $request->count : 1, $request->type_pin, $file->id);
            $file->pins()->saveMany($pinCodes);
        }

        if (is_array($request->users)) $file->users()->sync($request->users);

        return redirect()->route('index')->with('success', 'Файл загружен');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $file = File::query()->find($id);

        if ($file->access->tag == 'all'){
            return redirect()->route('file.sharedAccess', ['file' => $file->id]);
        }

        $userRole = Auth::user()->role->tag;
        $userId = Auth::user()->id;

        if (!$file) abort(404);
        if (Auth::user()->role->tag != 'administrator')
            if ($file->user_id != Auth::user()->id && !count($file->users->where('id', Auth::user()->id)) && !($file->access->tag == 'all' || $file->access->tag == 'authorized')) abort(404);

        $specificUsersList = $file->users->pluck('surname', 'name');

        return view('files.show', compact('file', 'specificUsersList', 'userRole', 'userId'));
    }
    public function sharedAccessFile($id)
    {
        $file = File::query()->find($id);
        $reg = Option::query()->where('tag', 'registration')->first();

        if (!$file || $file->access->tag != 'all') abort(404);

        if (Auth::check()){
            $userRole = Auth::user()->role->tag;
            $userId = Auth::user()->id;
        } else {
            $userRole = false;
            $userId = false;
        }

        return view('files.sharedAccessFile', compact('file', 'reg', 'userRole', 'userId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $file = File::query()->find($id);

        if (!$file) abort(404);
        if (Auth::user()->role->tag != 'administrator')
            if ($file->user_id != Auth::user()->id) abort(404);

        $accesses = Access::query()->pluck('title', 'id')->all();
        $userList = User::select("id", DB::raw("CONCAT(users.surname, ' ', users.name) as full_name"))->where('id', '<>', Auth::user()->id)->pluck('full_name', 'id');
        $pinCodes = Pin::query()->where('file_id', $id)->get();
        $reusable = $file->pin == 1 ? $pinCodes->first()->reusable : 'no';
        return view('files.edit', compact('accesses', 'userList', 'file', 'pinCodes', 'reusable'));
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
        $file = File::query()->find($id);

        if (!$file) abort(404);
        if (Auth::user()->role->tag != 'administrator')
            if ($file->user_id != Auth::user()->id) abort(404);

        $request->validate([
            'title' => 'required',
            'access_id' => 'required|integer',
        ]);

        if (($file->access_id == 4 && $request->access_id != 4) || !isset($request->users)) {
            DB::table('file_user')->where('file_id', $file->id)->delete();
        }

        if (isset($request->pin)) {
            Pin::query()->where('file_id', $file->id)->delete();
            $pinCodes = $this->pinskGeneration($request->type_pin == 0 ? $request->count : 1, $request->type_pin, $file->id);
            $file->pins()->saveMany($pinCodes);
        } elseif (!isset($request->pin) && $file->pin == 1) {
            $file->update(['pin' => 0]);
            Pin::query()->where('file_id', $file->id)->delete();
        }

        $data = $request->all();
        
        $file->update($data);

        if (is_array($request->users)) $file->users()->sync($request->users);

        return redirect()->route('index')->with('success', 'Файл отредактирован');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::query()->find($id);
        if ($file->user_id != Auth::user()->id) abort(404);

        DB::table('file_user')->where('file_id', $file->id)->delete();
        Pin::query()->where('file_id', $file->id)->delete();
        Storage::delete($file->href);
        $file->delete();

        return redirect()->route('index')->with('success', 'Файл удален');
    }

    protected function pinskGeneration($count, $type_pin, $id): array
    {
        $pinCodes = [];
        for ($i = 0; $i < $count; $i++) {
            $pin = '';
            for ($j = 0; $j < 5; $j++) {
                $pin .= rand(0, 9);
            }
            $pinCodes[] = new Pin(['pin' => $pin, 'reusable' => $type_pin, 'file_id' => $id]);
        }
        return $pinCodes;
    }
}
