@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h5>Личный кабинет пользователя: {{ Auth::user()->surname }} {{ Auth::user()->name }}</h5>
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
                        <h3 class="card-title">Количество загруженных файлов: {{ count( $files ) }} @if($totalFileSize) Общий размер всех файлов: {{ formatBytes($totalFileSize) }} @endif</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('users.update', ['user' => Auth::user()->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <label for="surname">Фамилию</label>
                            <div class="input-group mb-3">
                                <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" value="{{ Auth::user()->surname}}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <label for="surname">Имя</label>
                            <div class="input-group mb-3">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ Auth::user()->name}}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ( Auth::user()->role->tag == 'administrator')
                            <label for="surname">Изменить email</label>
                            <div class="input-group mb-3">
                                <input type="text" name="email" class="form-control" placeholder="email" value="{{  Auth::user()->email }}">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <label for="surname">Заменить пароль</label>
                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Пароль">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-group mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Потвердить пароль">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="page" value="personalCabinet">

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