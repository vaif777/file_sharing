@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
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
                        <h3 class="card-title">Все пользователи</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Добавить пользователя</a>
                        @if (count($users))
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Фамилия имя</th>
                                        <th>Роль</th>
                                        <th>Почта</th>
                                        <th>Активация</th>
                                        <th>Общий вес файлов</th>
                                        <th>Дата регистрации</th>
                                        <th>дествие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td><a href="{{ route ('users.show', ['user' => $user->id]) }}">{{ $user->surname }} {{ $user->name }}</a></td>
                                        <td>@if (isset($user->role->title)) {{ $user->role->title }} @endif</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->activation)
                                            Активирован
                                            @else
                                            Не Активирован
                                            @endif
                                        </td>
                                        <td>{{ formatBytes($user->myfiles->pluck('file_size')->sum()) }}</td>
                                        <td>{{ $user->getUserDate() }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-info btn-sm float-left mr-1">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="post" class="float-left">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Подтвердите удаление')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @if (!$user->activation)
                                    <tr>
                                        <td colspan="7">
                                            <form id="activationForm" role="form" method="post" action="{{ route('user.activation', ['id' => $user->id]) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="access_id">Выберите роль что бы актевировать пользователя: {{ $user->surname }} {{ $user->name }}</label>
                                                    <select class="form-control" id="role_id" name="role_id">
                                                        @foreach($roles as $k => $v)
                                                        <option value="{{ $k }}">{{ $v }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <button type="submit" class="btn btn-success btn-sm float-left mr-1">Активировать</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $users->links('vendor.pagination.bootstrap-4') }}
                        </div>
                        @else
                        <p>Зарегистрированых пользователей нет...</p>
                        @endif
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