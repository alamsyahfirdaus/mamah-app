@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ isset($user) ? 'Edit' : 'Tambah' }} {{ $title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST" id="form-data" enctype="multipart/form-data">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif
                <input type="hidden" name="id" value="{{ isset($user) ? Crypt::encrypt($user->id) : '' }}">
                <div class="form-group">
                    <label for="name">Nama<small class="text-danger">*</small></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        id="name" placeholder="Nama Lengkap" autocomplete="off"
                        value="{{ old('name', $user->name ?? '') }}">
                    <span class="invalid-feedback" id="error-name">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="email">Email<small class="text-danger">*</small></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        id="email" placeholder="Alamat Email" autocomplete="off"
                        value="{{ old('email', $user->email ?? '') }}">
                    <span class="invalid-feedback" id="error-email">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="phone">No HP</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                        id="phone" placeholder="08xxxxxxxxxx" autocomplete="off"
                        value="{{ old('phone', $user->phone ?? '') }}">
                    <span class="invalid-feedback" id="error-phone">
                        @error('phone')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date"
                        id="birth_date" value="{{ old('birth_date', $user->birth_date ?? '') }}">
                    <span class="invalid-feedback" id="error-birth_date">
                        @error('birth_date')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                        placeholder="Alamat Lengkap">{{ old('address', $user->address ?? '') }}</textarea>
                    <span class="invalid-feedback" id="error-address">
                        @error('address')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="role">Peran<small class="text-danger">*</small></label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="">-- Pilih Peran --</option>
                        @php
                            $roles = [
                                'kia' => 'Admin (Tim KIA)',
                                'ibu' => 'Ibu',
                                'bidan' => 'Bidan',
                            ];
                        @endphp
                        @foreach ($roles as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('role', $user->role ?? '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback" id="error-role">
                        @error('role')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </form>
        </div>
        <div class="card-footer"
            style="background-color: #fff; padding-top: 0; border-bottom-left-radius: 0.25rem; border-bottom-right-radius: 0.25rem;">
            <a href="{{ route('user.index') }}" type="button" class="btn btn-default">Batal</a>
            <button type="button" id="btn-submit" class="btn btn-primary float-right">Simpan</button>
        </div>
    </div>
    <script>
        $('#btn-submit').click(function() {
            let isValid = true;

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            let name = $('#name').val().trim();
            let email = $('#email').val().trim();
            let role = $('#role').val().trim();

            if (name === '') {
                isValid = false;
                $('#name').addClass('is-invalid');
                $('#error-name').text('Nama wajib diisi.');
            }

            if (email === '') {
                isValid = false;
                $('#email').addClass('is-invalid');
                $('#error-email').text('Email wajib diisi.');
            } else if (!validateEmail(email)) {
                isValid = false;
                $('#email').addClass('is-invalid');
                $('#error-email').text('Format email tidak valid.');
            }

            if (role === '') {
                isValid = false;
                $('#role').addClass('is-invalid');
                $('#error-role').text('Peran wajib dipilih.');
            }

            if (!isValid) return;

            $(this).text('Memproses...').prop('disabled', true);
            $('#form-data').submit();
        });

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }

        $('[id][name]').on('keyup change', function() {
            const fieldId = $(this).attr('id');
            $(this).removeClass('is-invalid');
            $('#error-' + fieldId).text('');
        });
    </script>
@endsection
