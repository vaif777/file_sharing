@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Название: {{ $file->title }}.{{ $file->file_extension}}</h4>
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
                        @if ($file->pin && $userId != $file->user_id)
                        <form action="{{ route ('download', ['id' => $file->id]) }}" method="post" class="float-left">
                            @csrf
                            <input type="hidden" name="pin" id="setPin" value="">
                            <button type="submit" class="btn btn-success btn-sm float-left mr-1" id="getPin">
                                <i class="fas fa-arrow-down"></i> Cкачать
                            </button>
                        </form>
                        @else
                        <a href="{{ route ('download', ['id' => $file->id]) }}" class="btn btn-success btn-sm float-left mr-1">
                            <i class="fas fa-arrow-down"></i> Cкачать
                        </a>
                        @endif
                        @if ($userRole == 'administrator' || $userId == $file->user_id)
                        <a href="{{ route('files.edit', ['file' => $file->id]) }}" class="btn btn-info btn-sm float-left mr-1">
                            <i class="fas fa-pencil-alt"></i> Редактировать
                        </a>

                        <form action="{{ route('files.destroy', ['file' => $file->id]) }}" method="post" class="float-left">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Подтвердите удаление')">
                                <i class="fas fa-trash-alt"></i> Удалить
                            </button>
                        </form>
                        @if ($file->access->tag == 'all' || $file->access->tag == 'authorized' || $file->access->tag == 'specificUsers')
                        </br></br><label>Ссылка на скачивание: </label>
                        {{ route('file', ['file' => $file->id]) }}</br>
                        @endif
                        @if ($file->access->tag == 'all' || $file->access->tag == 'authorized' || $file->access->tag == 'specificUsers')
                        <label>Статус пин: </label> {{ $file->pin == true ? 'Устоновлен' : 'Отсутствует' }}</br>
                        @endif
                        @if ($file->pin == true)
                        <label>Тип пин: </label> {{$file->pins->first()->reusable == 1 ? 'Многоразовый' : 'Одноразовый' }}</br>
                        <label>Пин код: </label> {{ $file->pins->pluck('pin')->join(' ') }}</br>
                        @endif
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if ($userId != $file->user->id )
                        <p><label>Файл зарузил пользователь: </label> {{ $file->user->surname }} {{ $file->user->name }}</p>
                        @endif
                        @if ($userRole == 'administrator')
                        <p><label>Роль пользователя: </label> {{ $file->user->role->title }}</p>
                        @endif
                         <p><label>Оригенальное имя: </label> {{ $file->original_name }}</p>
                        <p><label>Тип файлов: </label> {{ $file->type }}</p>
                        <p><label>Размер файла: </label> {{ formatBytes($file->file_size) }}</p>
                        <p><label>Описание: </label> {{ $file->content ?: 'Отсутствует'}}</p>
                        <p><label>Дата добавления: </label> {{ $file->getFileDate() }}</p>
                        @if ($userRole == 'administrator' || $userId == $file->user_id)
                        <p><label>Доступ: </label> {{ $file->access->title }}</p>
                        @if ($file->access->tag == 'specificUsers')
                        <p><label>Доступ предоставлен следующим пользователя: </label>
                            @foreach ($specificUsersList as $k => $v)
                            {{$k}} {{$v}}, 
                            @endforeach
                            {{ !count($specificUsersList) ? 'Пользователь был удален.' : '' }}
                        </p>
                        @endif
                        @endif
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