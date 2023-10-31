<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index (Request $request)
    {
          $request->validate([
            's' => 'required',
        ]);

        $search = $request->s;

        $files = File::query()->where('user_id', Auth::user()->id)->where('title', 'LIKE', "%{$search}%")->orderBy('id', 'DESC')->paginate(10);
        $filesDownload = File::query()->where('user_id', '<>', Auth::user()->id )->where('title', 'LIKE', "%{$search}%")->whereIn('access_id', [2, 3])->get();
        $filesDownload = $filesDownload->merge(Auth::user()->files->where('user_id', '<>', Auth::user()->id )->where('title', 'LIKE', "%{$search}%"));
        
        //dd($filesDownload);

        return view('search.index', compact('files', 'filesDownload', 'search'));
    }

    public function searchAdmin (Request $request)
    {
          $request->validate([
            's' => 'required',
        ]);

        $search = $request->s;

        $files = File::query()->where('user_id', Auth::user()->id)->where('title', 'LIKE', "%{$search}%")->orderBy('id', 'DESC')->paginate(10);
        $filesDownload = File::query()->where('user_id', '<>', Auth::user()->id )->where('title', 'LIKE', "%{$search}%")->paginate(10);

        return view('search.index', compact('files', 'filesDownload', 'search'));
        
    }
}
