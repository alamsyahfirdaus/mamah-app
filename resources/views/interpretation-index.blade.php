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
                                    <th>Skor</th>
                                    <th>Kategori</th>
                                    <th>Interpretasi</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            {{ $item->min_score }}
                                            {{ $item->max_score ? '- ' . $item->max_score : '' }}
                                        </td>
                                        <td>{{ $item->category }}</td>
                                        <td>{{ $item->recommendation }}</td>
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
                            <label for="min_score">Skor Minimal<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('min_score') is-invalid @enderror"
                                name="min_score" id="min_score" placeholder="Masukkan Skor Minimal" autocomplete="off"
                                value="{{ old('min_score', $data->min_score ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-min_score">
                                @error('min_score')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="max_score">Skor Maksimal<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('max_score') is-invalid @enderror"
                                name="max_score" id="max_score" placeholder="Masukkan Skor Maksimal" autocomplete="off"
                                value="{{ old('max_score', $data->max_score ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-max_score">
                                @error('max_score')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="category">Kategori<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror"
                                name="category" id="category" placeholder="Masukkan Kategori" autocomplete="off"
                                value="{{ old('category', $data->category ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-category">
                                @error('category')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="recommendation">Interpretasi<small class="text-danger">*</small></label>
                            <textarea name="recommendation" id="recommendation" class="form-control @error('recommendation') is-invalid @enderror"
                                placeholder="Masukkan Interpretasi">{{ old('recommendation', $data->recommendation ?? '') }}</textarea>
                            <span class="invalid-feedback d-block" id="error-recommendation">
                                @error('recommendation')
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

            // Reset error
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Validasi Skor Minimal
            let minScore = $('#min_score').val().trim();
            if (minScore === '') {
                isValid = false;
                $('#min_score').addClass('is-invalid');
                $('#error-min_score').text('Skor Minimal wajib diisi.');
            } else if (isNaN(minScore)) {
                isValid = false;
                $('#min_score').addClass('is-invalid');
                $('#error-min_score').text('Skor Minimal harus berupa angka.');
            }

            // Validasi Skor Maksimal
            let maxScore = $('#max_score').val().trim();
            if (maxScore === '') {
                isValid = false;
                $('#max_score').addClass('is-invalid');
                $('#error-max_score').text('Skor Maksimal wajib diisi.');
            } else if (isNaN(maxScore)) {
                isValid = false;
                $('#max_score').addClass('is-invalid');
                $('#error-max_score').text('Skor Maksimal harus berupa angka.');
            }

            if (!isNaN(minScore) && !isNaN(maxScore)) {
                if (parseFloat(minScore) > parseFloat(maxScore)) {
                    isValid = false;
                    $('#min_score, #max_score').addClass('is-invalid');
                    $('#error-min_score').text('Skor Minimal tidak boleh lebih besar dari Skor Maksimal.');
                    $('#error-max_score').text('Skor Maksimal tidak boleh lebih kecil dari Skor Minimal.');
                }
            }

            let category = $('#category').val().trim();
            if (category === '') {
                isValid = false;
                $('#category').addClass('is-invalid');
                $('#error-category').text('Kategori wajib diisi.');
            }

            let recommendation = $('#recommendation').val().trim();
            if (recommendation === '') {
                isValid = false;
                $('#recommendation').addClass('is-invalid');
                $('#error-recommendation').text('Interpretasi wajib diisi.');
            }

            if (isValid) {
                $(this).text('Memproses...').prop('disabled', true);
                $('#form-data').submit();
            }
        });

        $('#min_score, #max_score, #category, #recommendation').on('keyup change', function() {
            $(this).removeClass('is-invalid');
            let id = $(this).attr('id');
            $('#error-' + id).text('');
        });
    </script>

@endsection
