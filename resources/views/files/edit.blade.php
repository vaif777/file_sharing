@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Редактирование файла</h1>
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
                        <h3 class="card-title">Файл "{{ $file->title }}"</h3>
                    </div>
                    <!-- /.card-header -->

                    <form role="form" method="post" action="{{ route('files.update', ['file' => $file->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Название</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ $file->title }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Контент</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" id="content" rows="7">{{ $file->content }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="access_id">Доступ</label>
                                <select class="form-control @error('access_id') is-invalid @enderror" id="access" name="access_id">
                                    @foreach($accesses as $k => $v)
                                    <option value="{{ $k }}" @if($k==$file->access_id) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                                @error('access_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group" id="tags">
                                <label for="tags">Пользователи</label>
                                <select name="users[]" class="select2" multiple="multiple" data-placeholder="Выбор тегов" style="width: 100%;">
                                    @foreach($userList as $k => $v)
                                        <option value="{{ $k }}" @if(in_array($k, $file->users->pluck('id')->all())) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select></br>
                            </div>

                            <div class="form-group" id="pin">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="pin" id="customSwitch1" value="1" @if($file->pin == 1) checked="" @endif>
                                    <label class="custom-control-label" for="customSwitch1">Создать пин код для скачивания</label>
                                </div></br>
                            </div>

                            <div class="form-group" id="typePin">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="type_pin" value="0" @if($reusable==0) checked="" @endif>
                                    <label for="customRadio1" class="custom-control-label">Одноразовый пин код</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="type_pin" value="1" @if($reusable==1) checked="" @endif>
                                    <label for="customRadio2" class="custom-control-label">Многоразовые пин код</label>
                                </div></br>
                            </div>

                            <div class="form-group" id="countPin">
                                <label for="count">Количество одноразовый пин кодов</label>
                                <input type="text" name="count" class="form-control @error('count') is-invalid @enderror" id="count" value="{{ count($pinCodes)?:1 }}">
                            </br></div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Редактирование</button>
                        </div>
                    </form>

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