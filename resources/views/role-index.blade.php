<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Maternal Mental Health (MaMaH)</title>
    <link rel="stylesheet" href="{{ asset('assets/auth/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/vendors/base/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/auth/images/favicon.ico') }}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-center py-5 px-4 px-sm-5">
                            <div class="brand-logo m-0">
                                <img src="{{ asset('assets/logo.jpg') }}" style="width: 75px; border-radius: 50%;"
                                    alt="MaMaH">
                            </div>
                            <h1 style="margin-top: 16px; font-size: 36px; font-weight: bold; color: #198754;">MaMaH
                            </h1>
                            <div style="margin-top: 32px; text-align: left;">
                                <p style="font-size: 16px;"> Login sebagai Ibu Postpartum atau
                                    Bidan?</p>
                                <a href="" type="button" class="btn btn-block btn-outline-success"
                                    style="font-size: 16px;">Unduh Aplikasi Android
                                </a>
                                <p style="font-size: 16px; margin-top: 16px;"> Login sebagai Tim
                                    KIA?</p>
                                <a href="{{ route('login') }}" type="button" class="btn btn-block btn-outline-success"
                                    style="font-size: 16px;">Tim Kesehatan Ibu dan Anak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/auth/vendors/base/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/auth/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/auth/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/auth/js/template.js') }}"></script>
    <script src="{{ asset('assets/auth/js/template.js') }}/js/todolist.js"></script>
</body>

</html>
