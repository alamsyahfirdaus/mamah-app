@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar {{ $title }}</h3>
            <div class="card-tools">
                <a href="{{ route('user.create') }}" type="button" class="btn btn-tool" title="Tambah {{ $title }}">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No<span style="font-size: 6px; color: #fff;">_</span>HP</th>
                        <th>Peran</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $i => $user)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}
                            <td>{{ Str::ucfirst($user->role) }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item"
                                            href="{{ route('user.edit', Crypt::encrypt($user->id)) }}">Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            data-action="{{ route('user.destroy', Crypt::encrypt($user->id)) }}"
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
