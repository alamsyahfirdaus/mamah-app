@extends('layouts.main')

@section('title', $title)

@php
    $isListPage = Request::segment(1) === 'discussion' && Request::segment(2) === null;
    $isFormPage = Request::segment(2) === 'create' || Request::segment(3) === 'edit';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title {{ $isListPage ? 'pt-1' : '' }}">
                {{ $isListPage ? 'Daftar' : (isset($data) ? 'Edit' : 'Tambah') }} Grup Diskusi
            </h4>
            @if ($isListPage)
                <div class="card-tools">
                    <a href="{{ route('discussion.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane {{ $isListPage ? 'active' : '' }}" id="list-tab">
                    @if ($isListPage)
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Nama<span style="font-size: 6px; color: #fff;">_</span>Grup</th>
                                    <th>Deskripsi</th>
                                    <th>Dibuat<span style="font-size: 6px; color: #fff;">_</span>Oleh</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description ?? '-' }}</td>
                                        <td>{{ $item->creator->name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <i class="fas fa-cogs"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('discussion.show', Crypt::encrypt($item->id)) }}">Detail</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('discussion.edit', Crypt::encrypt($item->id)) }}">Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        data-action="{{ route('discussion.delete', Crypt::encrypt($item->id)) }}"
                                                        onclick="deleteData(this)">Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="tab-pane {{ $isFormPage ? 'active' : '' }}" id="form-tab">
                    <form action="{{ route('discussion.store') }}" method="POST" id="form-data"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($data))
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($data->id) }}">
                        @endif
                        <div class="form-group">
                            <label for="name">Nama Grup<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                id="name" placeholder="Masukkan Nama Grup" autocomplete="off"
                                value="{{ old('name', $data->name ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-name">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                placeholder="Deskripsi">{{ old('description', $data->description ?? '') }}</textarea>
                            <span class="invalid-feedback" id="error-description">
                                @error('description')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($isFormPage)
            <div class="card-footer"
                style="background-color: #fff; padding-top: 0; border-bottom-left-radius: 0.25rem; border-bottom-right-radius: 0.25rem;">
                <a href="{{ route('discussion.index') }}" type="button" class="btn btn-default">Batal</a>
                <button type="button" id="btn-submit" class="btn btn-primary float-right">Simpan</button>
            </div>
        @endif
    </div>
    <script>
        $('#btn-submit').click(function() {
            let isValid = true;

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            let name = $('#name').val().trim();
            if (name === '') {
                isValid = false;
                $('#name').addClass('is-invalid');
                $('#error-name').text('Nama grup wajib diisi.');
            }

            if (isValid) {
                $('#form-data').submit();
            }
        });

        $('#name').keyup(function() {
            $(this).removeClass('is-invalid');
            $('#error-name').text('');
        });
    </script>


@endsection
