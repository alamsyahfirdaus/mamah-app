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
                                    <th>Judul<span style="font-size: 6px; color: #fff;">_</span>Relaksasi</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                data-source="{{ $item->video_url ? 'link' : 'file' }}"
                                                data-url="{{ $item->video_url ? $item->video_url : asset('storage/uploads/modules/' . $item->file_name) }}"
                                                onclick="showEducation(this)" title="Lihat Materi">
                                                {{ $item->title }}
                                            </a>
                                        </td>
                                        <td title="{{ $item->description }}">
                                            {{ $item->description ? Str::limit(strip_tags($item->description), 100) : '-' }}
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
                                                        href="{{ route($segment1 . '.visibility', Crypt::encrypt($item->id)) }}">{{ $item->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</a>
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
                            <label for="title">Judul Relaksasi<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                                id="title" placeholder="Masukkan Judul Relaksasi" autocomplete="off"
                                value="{{ old('title', $data->title ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-title">
                                @error('title')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="video_url">Link YouTube<small class="text-danger">*</small></label>
                            <input type="url" class="form-control @error('video_url') is-invalid @enderror"
                                name="video_url" id="video_url" placeholder="Masukkan Link YouTube"
                                value="{{ old('video_url', $data->video_url ?? '') }}" autocomplete="off">
                            <span class="invalid-feedback d-block" id="error-video_url">
                                @error('video_url')
                                    {{ $message }}
                                @enderror
                            </span>
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

    <div class="modal fade" id="educationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Materi Edukasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="educationContent">
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#btn-submit').click(function() {
            let isValid = true;
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            if (!$('#title').val()?.trim()) {
                isValid = false;
                $('#title').addClass('is-invalid');
                $('#error-title').text('Judul relaksasi wajib diisi.');
            }

            const videoUrl = $('#video_url').val()?.trim();
            if (!videoUrl) {
                isValid = false;
                $('#video_url').addClass('is-invalid');
                $('#error-video_url').text('Link YouTube wajib diisi.');
            }

            if (isValid) {
                $('#form-data').submit();
            }
        });

        // Hapus error saat input diubah
        $('#form-data input, #form-data textarea').on('keyup change', function() {
            $(this).removeClass('is-invalid');
            $('#error-' + $(this).attr('id')).text('');
        });

        function showEducation(el) {
            let $el = $(el);
            let source = $el.data('source');
            let url = $el.data('url');
            let content = '';

            if (source === 'link') {
                let videoId = '';
                if (url.includes("watch?v=")) {
                    videoId = url.split("watch?v=")[1].split("&")[0];
                } else if (url.includes("youtu.be/")) {
                    videoId = url.split("youtu.be/")[1].split("?")[0];
                }
                let embedUrl = "https://www.youtube.com/embed/" + videoId;
                content = '<div class="embed-responsive embed-responsive-16by9">' +
                    '<iframe class="embed-responsive-item" src="' + embedUrl +
                    '" frameborder="0" allowfullscreen></iframe>' +
                    '</div>';
            } else {
                let ext = url.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    content =
                        '<div style="position:relative; width:100%; padding-top:100%; overflow:hidden; border-radius:10px;">' +
                        '<img src="' + url + '" alt="Materi Edukasi" ' +
                        'style="position:absolute; top:0; left:0; width:100%; height:500px; object-fit:contain;">' +
                        '</div>';
                } else if (ext === 'pdf') {
                    content = '<iframe src="' + url + '" width="100%" height="500px"></iframe>';
                } else {
                    content = '<a href="' + url + '" target="_blank">Lihat Materi</a>';
                }
            }

            $("#educationContent").html(content);
            $("#educationModal").modal("show");
        }
    </script>
@endsection
