@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title pt-1">Daftar {{ $title }}</h4>
            <div class="card-tools">
                <a href="{{ route('education.create') }}" type="button" class="btn btn-primary btn-sm"><i
                        class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="card-body">
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
                            <td>{{ $item->title }}</td>
                            <td title="{{ $item->content }}">{{ Str::limit(strip_tags($item->content), 100) }}</td>
                            <td>
                                <span class="badge badge-{{ $item->is_visible ? 'success' : 'secondary' }}">
                                    {{ $item->is_visible ? 'Ditampilkan' : 'Disembunyikan' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item"
                                            href="{{ route('education.edit', Crypt::encrypt($item->id)) }}">Edit</a>
                                        <a class="dropdown-item"
                                            href="">{{ $item->is_visible ? 'Sembunyikan' : 'Tampilkan' }}</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                            data-action="{{ route('education.delete', Crypt::encrypt($item->id)) }}"
                                            onclick="deleteData(this)">Hapus</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
