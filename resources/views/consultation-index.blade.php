@extends('layouts.main')

@section('title', $title)

@php
    $isListPage = Request::segment(1) === 'consultation' && Request::segment(2) === null;
    $isFormPage = Request::segment(2) === 'create' || Request::segment(3) === 'edit';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                {{ $isListPage ? 'Daftar' : 'Lihat' }} Konsultasi
            </h4>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane {{ $isListPage ? 'active' : '' }}" id="list-tab">
                    @if ($isListPage)
                        <table id="datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Nama<span style="font-size: 6px; color: #fff;">_</span>Ibu</th>
                                    <th>Nama<span style="font-size: 6px; color: #fff;">_</span>Bidan</th>
                                    <th>Topik</th>
                                    <th>Tanggal</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->ibu->name }}</td>
                                        <td>{{ $item->bidan->name }}</td>
                                        <td>{{ $item->topic }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <i class="fas fa-cogs"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item"
                                                        href="{{ route('consultation.show', Crypt::encrypt($item->id)) }}">Detail</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        data-action="{{ route('consultation.delete', Crypt::encrypt($item->id)) }}"
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
                    <form action="{{ route('consultation.store') }}" method="POST" id="form-data"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($data))
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id" value="{{ isset($data) ? Crypt::encrypt($data->id) : '' }}">
                        <div class="form-group">
                            <label for="question_text">Pertanyaan<small class="text-danger">*</small></label>
                            <input type="text" class="form-control @error('question_text') is-invalid @enderror"
                                name="question_text" id="question_text" placeholder="Masukkan Pertanyaan" autocomplete="off"
                                value="{{ old('question_text', $data->question_text ?? '') }}">
                            <span class="invalid-feedback d-block" id="error-question_text">
                                @error('question_text')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        @for ($i = 0; $i < 4; $i++)
                            <input type="hidden" name="score[]" value="{{ $i }}">
                            <div class="form-group">
                                <label for="label_{{ $i }}">Jawaban Skor {{ $i }}</label>
                                <input type="text" name="label[]" id="label_{{ $i }}"
                                    class="form-control @error("label.$i") is-invalid @enderror"
                                    placeholder="Masukkan Jawaban"
                                    value="{{ old("label.$i", $data->choices[$i]->label ?? '') }}" autocomplete="off">
                                <span class="invalid-feedback d-block" id="error-label_{{ $i }}">
                                    @error("label.$i")
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        @endfor
                    </form>

                </div>
            </div>
        </div>
        @if ($isFormPage)
            <div class="card-footer"
                style="background-color: #fff; padding-top: 0; border-bottom-left-radius: 0.25rem; border-bottom-right-radius: 0.25rem;">
                <a href="{{ route('consultation.index') }}" type="button" class="btn btn-default">Batal</a>
                <button type="button" id="btn-submit" class="btn btn-primary float-right">Simpan</button>
            </div>
        @endif
    </div>
    <script>
        $('#btn-submit').click(function() {
            let isValid = true;

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            let question = $('#question_text').val().trim();
            if (question === '') {
                isValid = false;
                $('#question_text').addClass('is-invalid');
                $('#error-question_text').text('Pertanyaan wajib diisi.');
            }

            $('input[name="label[]"]').each(function(index) {
                const val = $(this).val().trim();
                if (val === '') {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    $('#error-label_' + index).text('Jawaban untuk skor ' + index + ' wajib diisi.');
                }
            });

            if (isValid) {
                $('#form-data').submit();
            }
        });



        $('#question_text, input[name="label[]"]').on('keyup change', function() {
            $(this).removeClass('is-invalid');

            const id = $(this).attr('id');
            if (id === 'question_text') {
                $('#error-question_text').text('');
            } else if (id.startsWith('label_')) {
                const index = id.split('_')[1];
                $('#error-label_' + index).text('');
            }
        });
    </script>


@endsection
