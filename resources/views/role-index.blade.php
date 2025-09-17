<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MaMaH | Maternal Mental Health</title>
    <link rel="stylesheet" href="{{ asset('assets/auth/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/vendors/base/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/logo-mamah.png') }}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light py-5 px-4 px-sm-5">
                            <div class="text-center" style="margin-bottom: 8px;">
                                <div class="brand-logo m-0">
                                    <img src="{{ asset('assets/mamah.png') }}" style="width: 100px; border-radius: 75%;"
                                        alt="MaMaH">
                                </div>
                                <a href="/"
                                    style="margin-top: 16px; font-size: 36px; font-weight: bold; color:#80d6dd; text-decoration: none;">MaMaH
                                </a>
                            </div>
                            <div style="margin-top: 32px; text-align: left;">
                                <a href="" type="button" class="btn btn-block btn-primary"
                                    style="font-size: 16px;">Unduh Aplikasi Android
                                </a>
                                <br>
                                <a href="{{ route('login') }}" type="button" class="btn btn-block btn-outline-primary"
                                    style="font-size: 16px;">Tim Kesehatan Ibu dan Anak
                                </a>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Butuh Bantuan? <a href="https://wa.me/6289693839624" target="_blank"
                                    rel="noopener noreferrer">WhatsApp</a>
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
