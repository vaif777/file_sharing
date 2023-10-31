<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function index()
    {
        $options = Option::query()->get();

        return view('option.index', compact('options'));
    }

    public function update(Request $request)
    {
        $options = $request->all();
        if (isset($request->registration)) {
            Option::query()->where('tag', 'registration')->update([
                'condition' => $request->registration
            ]);
        } else {
            Option::query()->where('tag', 'registration')->update([
                'condition' => false
            ]);
        }

        return redirect()->route('option.index')->with('success', 'Изменения сохранины');
    }
}
