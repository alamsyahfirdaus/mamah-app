@extends('layouts.main')

@section('title', $title)

@php
    $segment1 = Request::segment(1);
    $segment2 = Request::segment(2);
    $segment3 = Request::segment(3);

    $isListPage = $segment1 && !$segment2;
    $isFormPage = $segment2 === 'create' || $segment3 === 'edit';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title {{ $isListPage ? 'pt-1' : '' }}">
                {{ $isListPage ? 'Daftar' : (isset($data) ? 'Edit' : 'Tambah') }} {{ $title }}
            </h4>
            @if ($isListPage)
                <div class="card-tools">
                    <a href="{{ route($segment1 . '.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
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
                                    <th>Judul<span style="font-size: 6px; color: #fff;">_</span>Materi</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td><a href="" title="Lihat Materi">{{ $item->title }}</a></td>
                                        <td title="{{ $item->description }}">
                                            {{ Str::limit(strip_tags($item->description), 100) }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $item->is_visible ? 'success' : 'secondary' }}">
                                                {{ $item->is_visible ? 'Ditampilkan' : 'Disembunyikan' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <i class="fas fa-cogs"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route($segment1 . '.edit', Crypt::encrypt($item->id)) }}">Edit</a>
                                                    <a class="dropdown-item"
                                                        href="">{{ $item->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        data-action="{{ route($segment1 . '.delete', Crypt::encrypt($item->id)) }}"
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
                    <form action="{{ route($segment1 . '.store') }}" method="POST" id="form-data"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($data))
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($data->id) }}">
                        @endif
                        <div class="form-group">
                            <label for="title">Judul Materi<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                                id="title" placeholder="Masukkan Judul Materi" autocomplete="off"
                                value="{{ old('title', $data->title ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-title">
                                @error('title')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="media_type">
                                Jenis Media<small class="text-danger">*</small>
                            </label>
                            @php
                                $mediaOptions = [
                                    'video' => 'Video',
                                    'image' => 'Gambar',
                                    'document' => 'Dokumen',
                                ];
                            @endphp
                            <select class="form-control select2 @error('media_type') is-invalid @enderror" name="media_type"
                                id="media_type" autocomplete="off">
                                <option value="">-- Pilih Jenis Media --</option>
                                @foreach ($mediaOptions as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('media_type', $data->media_type ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback d-block" id="error-media_type">
                                @error('media_type')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="file_name">File Media<small class="text-danger">*</small></label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file_name') is-invalid @enderror"
                                        name="file_name" id="file_name" accept="image/*,video/*,.pdf">
                                    <label class="custom-file-label" for="file_name">
                                        Pilih File Media <small>(Upload file dengan format: .jpg, .jpeg, .png, .mp4,
                                            .pdf)</small>
                                    </label>
                                </div>
                                {{-- <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div> --}}
                            </div>
                            <span class="invalid-feedback d-block" id="error-file_name">
                                @error('file_name')
                                    {{ $message }}
                                @enderror
                            </span>
                            @if (isset($data) && $data->file_name)
                                <small class="text-muted">
                                    File saat ini: {{ $data->file_name }}
                                </small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                placeholder="Masukkan Deskripsi">{{ old('description', $data->description ?? '') }}</textarea>
                            <span class="invalid-feedback d-block" id="error-description">
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
                <a href="{{ route($segment1 . '.index') }}" type="button" class="btn btn-default">Batal</a>
                <button type="button" id="btn-submit" class="btn btn-primary float-right">Simpan</button>
            </div>
        @endif
    </div>
    <script>
        $('#btn-submit').click(function() {
            let isValid = true;

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('.select2-selection').removeClass('border border-danger');

            const rules = [{
                    id: 'title',
                    name: 'Judul materi',
                    required: true,
                    type: 'text'
                },
                {
                    id: 'media_type',
                    name: 'Jenis media',
                    required: true,
                    type: 'select2'
                },
                {
                    id: 'file_name',
                    name: 'File media',
                    required: true,
                    type: 'file'
                },
            ];

            rules.forEach(rule => {
                const el = $('#' + rule.id);
                const val = el.val()?.trim();

                if (rule.required && (!val || val === '')) {
                    isValid = false;

                    if (rule.type === 'select2') {
                        el.addClass('is-invalid');
                        el.next('.select2-container').find('.select2-selection')
                            .addClass('border border-danger');
                    } else {
                        el.addClass('is-invalid');
                    }

                    $('#error-' + rule.id).text(rule.name + ' wajib diisi.');
                }
            });

            if (isValid) {
                $('#form-data').submit();
            }
        });

        $('#form-data input, #form-data select, #form-data textarea').on('keyup change', function() {
            $(this).removeClass('is-invalid');
            $('#error-' + $(this).attr('id')).text('');

            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).next('.select2-container').find('.select2-selection')
                    .removeClass('border border-danger');
            }
        });
    </script>


@endsection
