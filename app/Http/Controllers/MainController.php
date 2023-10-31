<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index (){
        
        $files = File::query()->with('access')->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        $filesDownload = File::query()->with('access', 'user')->where('user_id', '<>', Auth::user()->id )->whereIn('access_id', [2, 3])->get();
        $filesDownload = $filesDownload->merge(Auth::user()->files->where('user_id', '<>', Auth::user()->id ));
        
        return view('index', compact('files', 'filesDownload'));
    }
}
