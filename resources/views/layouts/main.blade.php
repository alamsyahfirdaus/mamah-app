<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MaMaH | @yield('title', 'Beranda')</title>
    <link rel="shortcut icon" href="{{ asset('assets/logo-mamah.png') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/admin/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/bs-stepper/css/bs-stepper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/dropzone/min/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/adminlte.css') }}">
    <style>
        @media (max-width: 991px) {
            body:not(.sidebar-open) .main-sidebar {
                transform: translateX(-250px);
            }
        }

        body {
            font-family: 'Poppins', sans-serif !important;
        }

        #datatable thead th {
            border-top: 0;
            border-bottom: 0;
        }

        #datatable tbody td {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        #datatable thead th:nth-child(1),
        #datatable tbody td:nth-child(1) {
            text-align: center;
        }

        #datatable thead th:last-child,
        #datatable tbody td:last-child {
            text-align: center;
        }

        .swal2-confirm:focus {
            box-shadow: 0 0 0 0.2rem rgba(211, 51, 51, 0.5) !important;
            border-color: #d33 !important;
        }

        .user-profile:active,
        .user-profile:hover {
            color: #212529;
            text-decoration: none;
            background-color: #fff;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <script src="{{ asset('assets/admin/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/admin/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/admin/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/admin/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('assets/admin/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/admin/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/admin/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('assets/admin/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/admin/adminlte.min.js') }}"></script>
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item">
                            <div class="media">
                                <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item">
                            <div class="media">
                                <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item">
                            <div class="media">
                                <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i
                                                class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li> --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle p-2" data-toggle="dropdown" href="javascript:void(0)">
                        <i class="fas fa-user-alt"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-item user-profile text-center" title="{{ Auth::user()->name }}">
                            <div class="mx-auto mb-2 img-circle d-flex align-items-center justify-content-center bg-primary text-white"
                                style="width: 50px; height: 50px; font-weight: bold; border-radius: 50%; font-size: 20px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                            </div>
                            <h3 class="dropdown-item-title mb-0" style="font-weight: bold; font-size: 14px;">
                                {{ Str::limit(Auth::user()->name, 16) }}
                            </h3>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item user-profile pt-0 text-center">
                            <a href="{{ route('logout') }}" class="btn btn-outline-primary btn-sm btn-block">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                        </div>
                    </div>

                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary">
            <a href="" class="brand-link" style="background-color: #fff; color: #007bff;">
                <img src="{{ asset('assets/logo-mamah.png') }}" alt="MaMaH" class="brand-image">
                <span class="brand-text" style="font-weight: bold;">MaMaH</span>
            </a>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-header">MENU ADMINISTRATOR</li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Beranda</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}"
                                class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item {{ Request::is('screening*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)"
                                class="nav-link {{ Request::is('screening*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-notes-medical"></i>
                                <p>
                                    Skrining
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @php
                                    $isScreeningActive =
                                        Request::is('screening') ||
                                        Request::is('screening/create') ||
                                        (Request::segment(1) === 'screening' && Request::segment(3) === 'edit');
                                @endphp

                                <li class="nav-item">
                                    <a href="{{ route('screening.index') }}"
                                        class="nav-link {{ $isScreeningActive ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Daftar Pertanyaan</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('screening.result') }}"
                                        class="nav-link {{ Request::is('screening/result') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Hasil Skrining</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('education.index') }}"
                                class="nav-link {{ Request::is('education*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>Materi Edukasi</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ Request::is('discussion*') || Request::is('consultation*') ? 'menu-open' : '' }}">
                            <a href="javascript:void(0)"
                                class="nav-link {{ Request::is('discussion*') || Request::is('consultation*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>
                                    Interaksi Ibu
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('discussion.index') }}"
                                        class="nav-link {{ Request::is('discussion*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Grup Diskusi</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('consultation.index') }}"
                                        class="nav-link {{ Request::is('consultation*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Konsultasi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>@yield('title', 'Dashboard')</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                @yield('content')
            </section>
        </div>

        <footer class="main-footer">
            <span>Copyright &copy; 2025-{{ date('Y') }} <a href="javascript:void(0)">Maternal Mental Health
                    (MaMaH)</a>.</span>
        </footer>
    </div>

    <script>
        $(function() {
            $('#datatable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true,
                order: [],
                columnDefs: [{
                    targets: [0, -1],
                    orderable: false,
                }],
                language: {
                    emptyTable: "Tidak ada data yang tersedia di tabel",
                    search: "Cari:",
                    infoFiltered: "",
                    zeroRecords: "Tidak ditemukan data yang cocok",
                },
                rowCallback: function(row, data, index) {
                    var pageInfo = this.api().page.info();
                    $('td:eq(0)', row).html(pageInfo.start + index + 1);
                },
            });

            $('.select2').select2();

            $('.datetimepicker-input').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });

        function deleteData(element) {
            const actionUrl = $(element).data('action');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $('<form>', {
                        method: 'POST',
                        action: actionUrl
                    });

                    const token = $('meta[name="csrf-token"]').attr('content');
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_token',
                        value: token
                    }));
                    form.append($('<input>', {
                        type: 'hidden',
                        name: '_method',
                        value: 'DELETE'
                    }));

                    $('body').append(form);
                    form.submit();
                }
            });
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        </script>
    @endif

</body>

</html>
