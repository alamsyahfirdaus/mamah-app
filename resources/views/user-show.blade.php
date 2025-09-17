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
                    <option value="" disabled>-- Daftar Ibu & Bidan --</option>
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
                    <span>Nama</span> <span class="float-right">{{ $user->name }}</span>
                </li>
                <li class="list-group-item">
                    <span>Email</span> <span class="float-right">{{ $user->email }}</span>
                </li>
                <li class="list-group-item">
                    <span>No. HP</span> <span class="float-right">{{ $user->phone ?? '-' }}</span>
                </li>
                <li class="list-group-item">
                    <span>Tanggal Lahir</span>
                    <span class="float-right">
                        {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d F Y') : '-' }}
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Desa/Kelurahan</span>
                    <span class="float-right">
                        {{ $user->village->name ?? '-' }},
                        {{ $user->village->district->name ?? '-' }},
                        {{ $user->village->district->city->name ?? '-' }},
                        {{ $user->village->district->city->province->name ?? '-' }}
                    </span>
                </li>
                <li class="list-group-item">
                    <span>Alamat</span> <span class="float-right">{{ $user->address ?? '-' }}</span>
                </li>
            </ul>
        </div>
    </div>

    @if ($user->role == 'ibu')
        {{-- <div class="card">
            <div class="card-header">
                <h4 class="card-title">Ibu Hamil</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item border-top-0 pt-0">
                        <span>Sedang Hamil</span>
                        <span class="float-right">
                            {{ $mother ? 'Ya' : 'Tidak' }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Usia Ibu</span>
                        <span class="float-right">
                            @if ($user->birth_date)
                                {{ \Carbon\Carbon::parse($user->birth_date)->age }} Tahun
                            @else
                                -
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Kehamilan Ke-</span>
                        <span class="float-right">
                            {{ $user->pregnantMother ? $user->pregnantMother->pregnancy_number : '-' }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Anak Hidup</span>
                        <span class="float-right">
                            {{ $user->pregnantMother ? $user->pregnantMother->live_children_count : '-' }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Riwayat Keguguran</span>
                        <span class="float-right">
                            {{ $user->pregnantMother ? $user->pregnantMother->miscarriage_history : '-' }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <span>Riwayat Penyakit Ibu</span>
                        <span class="float-right">
                            {{ $user->pregnantMother ? $user->pregnantMother->mother_disease_history : '-' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div> --}}
        {{-- <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">bs-stepper</h3>
            </div>
            <div class="card-body p-0">
                <div class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#logins-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="logins-part"
                                id="logins-part-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Logins</span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#information-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="information-part"
                                id="information-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Various information</span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="logins-part" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1"
                                    placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1"
                                    placeholder="Password">
                            </div>
                            <button class="btn btn-primary" onclick="stepper.next()">Next</button>
                        </div>
                        <div id="information-part" class="content" role="tabpanel"
                            aria-labelledby="information-part-trigger">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    @endif

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
