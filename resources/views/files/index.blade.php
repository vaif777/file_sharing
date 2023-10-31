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
                            <h3 class="card-title">Все файлы на сервере (кроме ваших)</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <a href="{{ route('files.create') }}" class="btn btn-primary mb-3">Загрузить файл</a>
                            @if (count($files))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-nowrap">
                                        <thead>
                                        <tr>
                                            <th>Название</th>
                                            <th>Расширение</th>
                                            <th>Вес</th>
                                            <th>Доступ</th>
                                            <th>Пользователь</th>
                                            <th>Пин код</th>
                                            <th>дествие</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($files as $file)
                                            <tr>
                                                <td><a href="{{ route('files.show', ['file' => $file->id]) }}">{{ $file->title }}</a></td>
                                                <td>{{ $file->file_extension }}</td>
                                                <td>{{ formatBytes($file->file_size) }}</td>
                                                <td>{{ $file->access->title }}</td>
                                                <td>{{ $file->user->surname }} {{ $file->user->name }}</td>
                                                <td>{{ $file->pin == 1 ? 'Устоновлен' : 'Отсутствует' }}</td>
                                                <td>
                                                    <a href="{{ asset('storage/'.$file->href) }}"
                                                       class="btn btn-success btn-sm float-left mr-1" download>
                                                       <i class="fas fa-arrow-down"></i>
                                                    </a>

                                                    <a href="{{ route('files.edit', ['file' => $file->id]) }}"
                                                       class="btn btn-info btn-sm float-left mr-1">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('files.destroy', ['file' => $file->id]) }}"
                                                        method="post" class="float-left">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Подтвердите удаление')">
                                                            <i
                                                                class="fas fa-trash-alt"></i>
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
                                <p>У вас пока нет загруженных файлов...</p>
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

