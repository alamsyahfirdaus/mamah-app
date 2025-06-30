@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Hasil {{ $title }}</h4>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama<span style="font-size: 6px; color: #fff;">_</span>Ibu</th>
                        <th>Tanggal</th>
                        <th>Skor</th>
                        <th>Kategori</th>
                        <th style="text-align: left;">Rekomendasi</th>
                        {{-- <th style="width: 5%;">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</td>
                            <td>{{ $item->score }}</td>
                            <td>{{ Str::ucfirst($item->category) }}</td>
                            <td style="text-align: left;">{{ $item->recommendation }}</td>
                            {{-- <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item"
                                            href="{{ route('user.edit', Crypt::encrypt($item->id)) }}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                            data-action="{{ route('user.delete', Crypt::encrypt($item->id)) }}"
                                            onclick="deleteData(this)">Hapus</a>
                                    </div>
                                </div>
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
