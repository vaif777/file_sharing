@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Поиск</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@if (count($files))
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ваши файлы</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
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
                                           <a href="{{ route('file.sharedAccess', ['file' => $file->id]) }}" >
                                        @else
                                            <a href="{{ route('file', ['file' => $file->id]) }}"  >
                                        @endif
                                        {{ $file->title }}.{{ $file->file_extension }}</a></td>
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
@endif
@if (count($filesDownload))
<!-- Main content2 -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->role->tag == 'administrator')
                            <h3 class="card-title">Файлы других пользователей</h3>
                        @else
                            <h3 class="card-title">Файлы которые вы можите скачать</h3>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Дата добавления</th>
                                        <th>Вес</th>
                                        <th>Пользователь</th>
                                        <th>Пин код</th>
                                        <th>дествие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filesDownload as $fileDownload)
                                    <tr>
                                        <td><a 
                                        @if($fileDownload->access->tag == 'all')
                                            href="{{ route( 'file.sharedAccess', ['file' => $fileDownload->id]) }}"
                                        @else
                                            href="{{ route( 'file', ['file' => $fileDownload->id]) }}"
                                        @endif      
                                        >{{ $fileDownload->title }}.{{ $fileDownload->file_extension }}</a></td>
                                        <td>{{ $fileDownload->getFileDate() }}</td>
                                        <td>{{ formatBytes($fileDownload->file_size) }}</td>
                                        <td>{{ $fileDownload->user->surname }} {{ $fileDownload->user->name }}</td>
                                        <td>{{ $fileDownload->pin == 1 ? 'Устоновлен' : 'Отсутствует' }}</td>
                                        <td>
                                            @if ($fileDownload->pin)
                                            <form action="{{ route ('download', ['id' => $fileDownload->id]) }}" method="post" class="float-left">
                                                @csrf
                                                <input type="hidden" name="pin" id="setPin" value="">
                                                <button type="submit" class="btn btn-success btn-sm float-left mr-1" id="getPin">
                                                    <i class="fas fa-arrow-down"></i>
                                                </button>
                                            </form>
                                            @else
                                            <a href="{{ route ('download', ['id' => $fileDownload->id]) }}" class="btn btn-success btn-sm float-left mr-1">
                                                <i class="fas fa-arrow-down"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
<!-- /.content2 -->
@endif
@if (!count($files) && !count($filesDownload))
<!-- Main content2 -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Файлы которые вы можите скачать</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                           <p> По вашемму запросу "{{ $search }}" файлов не найденно. </p>
                        </div>
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
<!-- /.content2 -->
@endif
@endsection