<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\File;
use App\Models\Option;
use App\Models\Pin;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query()->where('id', '<>', Auth::user()->id)->orderBy('activation')->paginate(10);
        $roles = Role::query()->pluck('title', 'id');
        return view('user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::query()->pluck('title', 'id');

        return view('user.create', compact('roles'));
    }

    public function registration()
    {
        return view('user.registration');
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
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'string',
                'confirmed',
                'regex:/((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',
                Password::min(6)->letters()->uncompromised()->numbers()->mixedCase()->symbols(),
            ]
            
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        User::create($data);

        return redirect()->route($request->page == 'create' ? 'users.index' : 'login.create')->with('success', $request->page == 'create' ? 'Пользователь добавлен' : 'Вы успешно зарегистрировались');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::query()->select('name', 'surname')->where('id', $id)->first();
        $files = File::query()->where('user_id', $id)->orderBy('id', 'DESC')->paginate(10);
        $totalFileSize = 0;

        foreach ($files as $v) {
            $totalFileSize += $v->file_size;
        }

        return view('user.show', compact('files', 'totalFileSize', 'user', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::query()->find($id);
        $roles = Role::query()->pluck('title', 'id');

        return view('user.edit', compact('user', 'roles'));
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
        $user = User::query()->find($id);

        if (Auth::user()->role->tag != 'administrator')
            if ($user->id != Auth::user()->id) abort(404);

        $request->validate([
            'name' => 'required',
            'surname' => 'required',
        ]);

        $data = $request->all();

        if (empty($request->password)) {
            unset($data['password']);
        } else {
            $request->validate([
            'password' => [
                'string',
                'confirmed',
                'regex:/((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',
                Password::min(6)->letters()->uncompromised()->numbers()->mixedCase()->symbols(),
            ]
        ]);
            $data['password'] = bcrypt($request->password);
        }

        if (!isset($request->activation) && $request->page == 'edit') {
            $data['activation'] = 0;
        }

        $user->update($data);

        return redirect()->route($request->page == 'edit' ? 'users.index' : 'personalCabinet')->with('success', 'Изменения успешно сохранены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $filesIdArr = File::query()->select('id')->where('user_id', $id)->pluck('id');
        $filesHrefArr = File::query()->select('href')->where('user_id', $id)->pluck('href');
       
        Storage::delete($filesHrefArr->toArray());
        DB::table('file_user')->whereIn('file_id', $filesIdArr)->delete();
        Pin::query()->whereIn('file_id', $filesIdArr)->delete();
        File::query()->whereIn('id', $filesIdArr)->delete();
        User::query()->where('id', $id)->delete();
        
        return redirect()->route('users.index')->with('success', 'Пользователь удален');
    }

    public function loginForm()
    {
        $reg = Option::query()->where('tag', 'registration')->first();
        return view('user.login', compact('reg'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {

            return redirect()->route('index');
        }

        return redirect()->back()->with('error', 'неправильный логин или пароль');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.create');
    }

    public function personalCabinet()
    {
        $files = File::query()->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        $totalFileSize = 0;

        foreach ($files as $v) {
            $totalFileSize += $v->file_size;
        }

        return view('user.personalCabinet', compact('files', 'totalFileSize'));
    }

    public function activation(Request $request, $id)
    {
        $user = User::query()->find($id);
        $data = $request->all();
        $data['activation'] = 1;
        //dd($data);
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Пользователь активирован');
    }
}
