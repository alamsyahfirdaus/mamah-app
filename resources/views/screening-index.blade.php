@extends('layouts.main')

@section('title', $title)

@php
    $isListPage = Request::segment(1) === 'screening' && Request::segment(2) === null;
    $isFormPage = Request::segment(2) === 'create' || Request::segment(3) === 'edit';
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title {{ $isListPage ? 'pt-1' : '' }}">
                {{ $isListPage ? 'Daftar' : (isset($data) ? 'Edit' : 'Tambah') }} Pertanyaan
            </h4>
            @if ($isListPage)
                <div class="card-tools">
                    <a href="{{ route('screening.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
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
                                    <th>Pertanyaan</th>
                                    <th>Jawaban</th>
                                    <th style="width: 5%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $i => $item)
                                    <tr>
                                        <td class="{{ $item->is_special ? 'text-danger' : '' }}" title="Pertanyaan Khusus">
                                            {{ $i + 1 }}</td>
                                        <td>
                                            {{ $item->question_text }}
                                        </td>
                                        <td>
                                            <ul class="list-group list-group-unbordered">
                                                @foreach ($item->choices as $choice)
                                                    <li
                                                        class="list-group-item {{ $loop->first ? 'border-top-0 pt-0' : 'pt-2' }} {{ $loop->last ? 'border-bottom-0 pb-0' : 'pb-2' }}">
                                                        <span>{{ $choice->label }}</span>
                                                        <span class="float-right">{{ $choice->score }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
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
                                                        href="{{ route('screening.edit', Crypt::encrypt($item->id)) }}">Edit</a>
                                                    @if (!$loop->first)
                                                        <a class="dropdown-item"
                                                            href="{{ route('screening.reorder', [Crypt::encrypt($item->id), 'up']) }}">Naikkan</a>
                                                    @endif
                                                    @if (!$loop->last)
                                                        <a class="dropdown-item"
                                                            href="{{ route('screening.reorder', [Crypt::encrypt($item->id), 'down']) }}">Turunkan</a>
                                                    @endif
                                                    @if (!$item->is_special)
                                                        <a class="dropdown-item"
                                                            href="{{ route('screening.special', Crypt::encrypt($item->id)) }}">
                                                            Tandai {{ $item->is_special ? 'Umum' : 'Khusus' }}
                                                        </a>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                        data-action="{{ route('screening.delete', Crypt::encrypt($item->id)) }}"
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
                    <form action="{{ route('screening.store') }}" method="POST" id="form-data"
                        enctype="multipart/form-data">
                        @csrf
                        @if (isset($data))
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ Crypt::encrypt($data->id) }}">
                        @endif
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
                <a href="{{ route('screening.index') }}" type="button" class="btn btn-default">Batal</a>
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
