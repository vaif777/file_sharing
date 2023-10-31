@extends('layouts.layout')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Загруска файлов</h1>
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

                    <form id="fileUploadForm" role="form" method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Название</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Название" value="{{ old('title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Комментарии</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" id="content" rows="7" placeholder="Контент ...">{{ old('content') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="access_id">Доступ</label>
                                <select class="form-control @error('access_id') is-invalid @enderror" id="access" name="access_id">
                                    @foreach($accesses as $k => $v)
                                    <option value="{{ $k }}" @if (old('access_id') == $k) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                                @error('access_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group" id="tags">
                                <label for="users_id">Пользователи</label>
                                <select name="users[]" class="select2" multiple="multiple" data-placeholder="Выберите пользователя" style="width: 100%;">
                                    @foreach($userList as $k => $v)
                                    <option value="{{ $k }}" 
                                    @if( !empty( old( 'users'))) 
                                        @if(in_array($k, old('users'))) selected @endif
                                    @endif>{{ $v }}</option>
                                    @endforeach
                                </select></br>
                            </div>

                            <div class="form-group" id="pin">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="pin" value="1" @if(!empty(old('pin'))) checked="" @endif>
                                    <label class="custom-control-label" for="customSwitch1">Создать пин код для скачивания</label>
                                </div></br>
                            </div>

                            <div class="form-group" id="typePin">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio1" name="type_pin" value="0" 
                                    @if(!empty(old('type_pin')))
                                        @if(old('type_pin') == 0 ) checked="" @endif
                                     @endif>
                                    <label for="customRadio1" class="custom-control-label">Одноразовый пин код</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="customRadio2" name="type_pin" value="1" 
                                    @if(!empty(old('type_pin')))
                                        @if(old('type_pin') == 1 ) checked="" @endif
                                    @else
                                        hecked=""
                                     @endif>
                                    <label for="customRadio2" class="custom-control-label">Многоразовые пин код</label>
                                </div></br>
                            </div>

                            <div class="form-group" id="countPin">
                                <div class="form-group">
                                    <label for="count">Количество одноразовый пин кодов</label>
                                    <input type="text" name="count" class="form-control @error('count') is-invalid @enderror" id="count" placeholder="Количество" value="{{ old('count') }}">
                                </div></br>
                            </div>

                            <div class="form-group">
                                <label for="file">Файл</label>
                                <div class="form-group mb-5">
                                    <input name="file" type="file" class="form-control @error('file') is-invalid @enderror" id="formFile">
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>

                </div>
                <!-- /.card -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
    <!-- /.content -->
    @endsection