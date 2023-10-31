@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h5>Редоктирование даннх</h5>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Пользователя: {{ $user->surname }} {{ $user->name }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label for="surname">Изменить фамилию или имя</label>
                            <div class="input-group mb-3">
                                <input type="text" name="surname" class="form-control" placeholder="Фамилия" value="{{ $user->surname }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Имя" value="{{ $user->name }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <label for="surname">Изменить email</label>
                            <div class="input-group mb-3">
                                <input type="text" name="email" class="form-control" placeholder="email" value="{{ $user->email }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="access_id">Изменить роль</label>
                                <select class="form-control @error('access_id') is-invalid @enderror" id="access_id" name="role_id">
                                    @foreach($roles as $k => $v)
                                    <option value="{{ $k }}" @if($k==$user->role_id) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="activation" value="1" @if($user->activation == 1) checked="" @endif>
                                    <label class="custom-control-label" for="customSwitch1">Деактивировать учетную запись</label>
                                </div>
                            </div>

                            <label for="surname">Заменить пароль</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Пароль">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Потвердить пароль">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="page" value="edit">

                            <div class="input-group mb-3">
                                <button type="submit" class="btn btn-primary">Редактирование</button>
                            </div>
                        </form>

                        <div class="card-footer clearfix">

                        </div>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection