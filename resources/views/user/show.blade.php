@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Пользователей: {{ $user->surname }} {{ $user->name }} 
                    <a href="{{ route('users.edit', ['user' => $id]) }}" class="btn btn-info btn-sm float-left mr-1">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                </h1>
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
                        @if (count($files))
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Дата добавления</th>
                                        <th>Вес</th>
                                        <th>Доступ</th>
                                        <th>Cсылка на скачивание</th>
                                        <th>Пин код</th>
                                        <th>дествие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($files as $file)
                                    <tr>
                                        <td>
                                            @if ($file->access->tag == 'all')
                                            <a href="{{ route('file.sharedAccess', ['file' => $file->id]) }}">
                                                @else
                                                <a href="{{ route('file', ['file' => $file->id]) }}">
                                                    @endif
                                                    {{ $file->title }}.{{ $file->file_extension }}</a>
                                        </td>
                                        <td>{{ $file->getFileDate() }}</td>
                                        <td>{{ formatBytes($file->file_size) }}</td>
                                        <td>{{ $file->access->title }}</td>
                                        <td>@if ($file->access->tag == 'authorized' || $file->access->tag == 'specificUsers')
                                            {{ route('file', ['file' => $file->id]) }}
                                            @elseif ($file->access->tag == 'all')
                                            {{ route('file.sharedAccess', ['file' => $file->id]) }}
                                            @else
                                            {{ 'Отсутствует' }}
                                            @endif
                                        </td></a>
                                        <td>{{ $file->pin == 1 ? 'Устоновлен' : 'Отсутствует' }}</td>
                                        <td>
                                            @if ($file->pin && Auth::user()->id != $file->user_id)
                                            <form action="{{ route ('download', ['id' => $file->id]) }}" method="post" class="float-left">
                                                @csrf
                                                <input type="hidden" name="pin" id="setPin" value="">
                                                <button type="submit" class="btn btn-success btn-sm float-left mr-1" id="getPin">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                            </form>
                                            @else
                                            <a href="{{ route ('download', ['id' => $file->id]) }}" class="btn btn-success btn-sm float-left mr-1">
                                                <i class="fas fa-arrow-down"></i>
                                            </a>
                                            @endif

                                            <a href="{{ route('files.edit', ['file' => $file->id]) }}" class="btn btn-info btn-sm float-left mr-1">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            <form action="{{ route('files.destroy', ['file' => $file->id]) }}" method="post" class="float-left">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Подтвердите удаление')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $files->links('vendor.pagination.bootstrap-4') }}
                        </div>
                        @else
                        <p>У пользователя нет загруженх файлов...</p>
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