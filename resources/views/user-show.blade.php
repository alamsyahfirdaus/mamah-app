@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detail {{ Str::ucfirst($user->role) }}</h4>
            <div class="card-tools">
                <a href="{{ route('user.edit', Crypt::encrypt($user->id)) }}" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-user-edit"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="user_id_ibu" style="display: none;">Daftar {{ Str::ucfirst($user->role) }} <small
                        class="text-danger">*</small></label>
                <select name="user_id_ibu" id="user_id_ibu"
                    class="form-control select2 @error('user_id_ibu') is-invalid @enderror">
                    <option value="" disabled>-- Daftar {{ Str::ucfirst($user->role) }} --</option>
                    @foreach ($users as $id => $name)
                        <option value="{{ Crypt::encrypt($id) }}" {{ $id == $user->id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                <span class="invalid-feedback d-block" id="error-user_id_ibu">
                    @error('user_id_ibu')
                        {{ $message }}
                    @enderror
                </span>
            </div>
            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b>Nama</b> <a class="float-right" style="color: #212529;">{{ $user->name }}</a>
                </li>
                <li class="list-group-item">
                    <b>Email</b> <a class="float-right" style="color: #212529;">{{ $user->email }}</a>
                </li>
                <li class="list-group-item">
                    <b>No. HP</b> <a class="float-right" style="color: #212529;">{{ $user->phone ?? '-' }}</a>
                </li>
                <li class="list-group-item">
                    <b>Tanggal Lahir</b>
                    <a class="float-right" style="color: #212529;">
                        {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d F Y') : '-' }}
                    </a>
                </li>
                <li class="list-group-item">
                    <b>Kecamatan</b>
                    <a class="float-right" style="color: #212529;">
                        {{ $user->district->name ?? '-' }},
                        {{ $user->district->city->name ?? '-' }},
                        {{ $user->district->city->province->name ?? '-' }}
                    </a>
                </li>
                <li class="list-group-item">
                    <b>Alamat</b> <a class="float-right" style="color: #212529;">{{ $user->address ?? '-' }}</a>
                </li>
            </ul>
        </div>
    </div>
    <script>
        $('#user_id_ibu').change(function() {
            let encryptedId = $(this).val();
            if (encryptedId) {
                let url = "{{ route('user.show', ':id') }}";
                url = url.replace(':id', encryptedId);
                window.location.href = url;
            }
        });
    </script>
@endsection
