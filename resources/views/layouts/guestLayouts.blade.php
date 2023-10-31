<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Oблако файлообменник | FM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front/css/front.css') }}">
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                    </div>
                    <div class="info">
                         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('login.create') }}" class="nav-link">
                                <i class="nav-icon fas fa-door-closed"></i>
                                <p>Войти</p>
                            </a>
                        </li>
                        @if ( $reg->condition )
                            <li class="nav-item">
                                <a href="{{ route('registration') }}" class="nav-link">
                                    <i class="nav-icon fas fa-address-card"></i>
                                    <p>Регистрация</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <div class="container mt-2">
                <div class="row">
                    <div class="col-12">

                        @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        @if (session()->has('danger'))
                        <div class="alert alert-danger">
                            {{ session('danger') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.0.5
            </div>
            <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <script src="{{ asset('assets/front/js/front.js') }}"></script>
    <script>
        let selectAccess = document.querySelector("#access")
        let selectTags = document.querySelector("#tags")
        let inputPin = document.querySelector("#pin")
        let inputTypePin = document.querySelector("#typePin")
        let inputCountPin = document.querySelector("#countPin")

        if (selectAccess.value == 1) {
            inputPin.style.display = 'none';
        }

        if (selectAccess.value != 4) {
            selectTags.style.display = 'none';
        }

        if (!inputPin.childNodes[1].childNodes[1].checked) {
            inputTypePin.style.display = 'none';
        }

        if (!inputTypePin.childNodes[1].childNodes[1].checked) {
            inputCountPin.style.display = 'none';
        }

        selectAccess.addEventListener('change', function(e) {

            if (e.target.value != 1) {
                inputPin.style.display = 'initial';
            } else {
                inputPin.style.display = 'none';
                inputTypePin.style.display = 'none';
                inputCountPin.style.display = 'none';
                inputPin.childNodes[1].childNodes[1].checked = false;
                inputTypePin.childNodes[1].childNodes[1].checked = false;
                inputTypePin.childNodes[3].childNodes[1].checked = true;
            }

            if (e.target.value == 4) {
                selectTags.style.display = 'initial';
            } else {
                selectTags.style.display = 'none';
            }

        })

        inputPin.addEventListener('change', function(e) {

            if (inputPin.childNodes[1].childNodes[1].checked) {
                inputTypePin.style.display = 'initial';
            } else {
                inputTypePin.style.display = 'none';
            }
        })

        inputTypePin.addEventListener('change', function(e) {

            if (e.target.value == 0) {
                inputCountPin.style.display = 'initial';
            } else {
                inputCountPin.style.display = 'none';
            }
        })
    </script>
    <script>
        let button = document.querySelector('#getPin');

        if (button) {
            button.addEventListener('click', function() {
                let pin = prompt('Введите пин код');
                document.getElementById("setPin").value = pin;
            });
        }
    </script>
    <script>
        $('.nav-sidebar a').each(function() {
            let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
            let link = this.href;
            if (link == location) {
                $(this).addClass('active');
                $(this).closest('.has-treeview').addClass('menu-open');
            }
        });
    </script>

    <script src="{{ asset('assets/front/ckeditor5/build/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/front/ckfinder/ckfinder.js') }}"></script>
    </section>



</body>

</html>