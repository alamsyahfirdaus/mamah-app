<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MaMaH | Login</title>
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
                                    style="margin-top: 16px; font-size: 36px; font-weight: bold; color: #80d6dd; text-decoration: none;">MaMaH
                                </a>
                            </div>
                            @if (session('message'))
                                <div id="alert-message" class="alert p-3"
                                    style="margin: 0; background-color: #d32535; color: #fff;">
                                    {{ session('message') }}
                                </div>
                            @endif
                            <form action="" class="pt-3" method="POST" id="form-login"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="email" style="display: none;">Email</label>
                                    <input type="text" class="form-control" name="email" id="email"
                                        placeholder="Email" value="{{ old('email') }}" autocomplete="off">
                                    <span id="error-email" class="error invalid-feedback"></span>
                                </div>
                                <div class="form-group">
                                    <label for="password" style="display: none;">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password">
                                    <span id="error-password" class="error invalid-feedback"></span>
                                </div>
                                <div class="mt-3">
                                    <button type="button" id="btn-login" class="btn btn-block btn-primary"
                                        style="font-weight: bold; font-size: 16px;">Masuk</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/auth/vendors/base/vendor.bundle.base.js') }}"></script>

    <script>
        $('#btn-login').click(function(e) {
            e.preventDefault();

            var isValid = true;

            $('#form-login').find(':input[name][id]').each(function() {
                var field = $(this);
                var errorField = $('#error-' + field.attr('id'));
                var labelText = $('label[for="' + field.attr('id') + '"]').text().trim();

                if (!field.val().trim()) {
                    field.addClass('is-invalid');
                    errorField.text(labelText + ' harus diisi.').show();
                    isValid = false;
                } else {
                    field.removeClass('is-invalid');
                    errorField.text('').hide();
                }
            });

            if (!isValid) return;

            $(this).text('Memproses...').prop('disabled', true);
            $('#form-login').submit();
        });

        $('#form-login').find(':input[name][id]').keyup(function() {
            $(this).removeClass('is-invalid').next('.invalid-feedback').hide();
        });

        $('#form-login').on('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                $('#btn-login').click();
            }
        });
    </script>

    @if (session('message'))
        <script>
            $(function() {
                setTimeout(function() {
                    $('#alert-message')
                        .fadeIn(500)
                        .delay(2000)
                        .slideUp(500);
                }, 2000);
            });
        </script>
    @endif
</body>

</html>
