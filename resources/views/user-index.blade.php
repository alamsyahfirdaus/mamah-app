@extends('layouts.main')

@section('title', $title)

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title pt-1">Daftar {{ $title }}</h4>
            <div class="card-tools">
                <a href="{{ route('user.create') }}" type="button" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i>
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
                        <th>No.<span style="font-size: 6px; color: #fff;">_</span>HP</th>
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
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                @if ($user->role == 'kia')
                                    Admin (Tim KIA)
                                @else
                                    {{ Str::ucfirst($user->role) }}
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @if ($user->role == 'kia')
                                            <a class="dropdown-item"
                                                href="{{ route('user.edit', Crypt::encrypt($user->id)) }}">Edit</a>
                                        @else
                                            <a class="dropdown-item"
                                                href="{{ route('user.show', Crypt::encrypt($user->id)) }}">Detail</a>
                                        @endif
                                        @if ($user->id !== Auth::id())
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                data-action="{{ route('user.delete', Crypt::encrypt($user->id)) }}"
                                                onclick="deleteData(this)">Hapus</a>
                                        @endif
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
