@extends('layouts.main')

@section('content')
    <div class="pagetitle">
        <h1>Data Buku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Data Buku</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-12">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-octagon me-1"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <div class="d-flex align-items-center justify-content-between m-3">
                            <h5 class="card-title">Total : {{$total_buku}} Buku</h5>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fas fa-plus fa-sm text-white-50"></i> Data Baru
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table datatable" id="pegawai">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tittle</th>
                                        <th>Kategori</th>
                                        <th>Penulis</th>
                                        <th>Stock</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buku as $itembuku)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $itembuku->title }}</td> <!-- Menggunakan 'title' bukan 'name' -->
                                            <td>{{ $itembuku->kategori->name ?? '-' }}</td> <!-- Menggunakan relasi 'kategori' dan kolom 'name' -->
                                            <td>{{ $itembuku->penulis->name ?? '-' }}</td> <!-- Menggunakan relasi 'penulis' dan kolom 'name' -->
                                            <td>{{ $itembuku->stock }}</td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <a href="{{ route('data-penulis.edit', $itembuku->id) }}" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $itembuku->id }}">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                            
                                                <!-- Tombol Hapus -->
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $itembuku->id }}">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            
                                                <!-- Modal Konfirmasi Hapus -->
                                                <div class="modal fade" id="deleteModal{{ $itembuku->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $itembuku->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $itembuku->id }}">Konfirmasi Hapus</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus data ini?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <form action="{{ route('data-buku.destroy', $itembuku->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal{{ $itembuku->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('data-buku.update', $itembuku->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="title-{{ $itembuku->id }}" class="form-label">Judul Buku</label>
                                                                        <input type="text" name="title" id="title-{{ $itembuku->id }}" class="form-control" value="{{ $itembuku->title }}" required>
                                                                    </div>
                                                            
                                                                    <div class="mb-3">
                                                                        <label for="kategori_id-{{ $itembuku->id }}" class="form-label">Kategori</label>
                                                                        <select name="kategori_id" id="kategori_id-{{ $itembuku->id }}" class="form-select" required>
                                                                            @foreach($kategori as $kat)
                                                                                <option value="{{ $kat->id }}" {{ $itembuku->kategori_id == $kat->id ? 'selected' : '' }}>
                                                                                    {{ $kat->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                            
                                                                    <div class="mb-3">
                                                                        <label for="penulis_id-{{ $itembuku->id }}" class="form-label">Penulis</label>
                                                                        <select name="penulis_id" id="penulis_id-{{ $itembuku->id }}" class="form-select" required>
                                                                            @foreach($penulis as $item)
                                                                                <option value="{{ $item->id }}" {{ $itembuku->penulis_id == $item->id ? 'selected' : '' }}>
                                                                                    {{ $item->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                            
                                                                    <div class="mb-3">
                                                                        <label for="stock-{{ $itembuku->id }}" class="form-label">Stok</label>
                                                                        <input type="number" name="stock" id="stock-{{ $itembuku->id }}" class="form-control" value="{{ $itembuku->stock }}" min="0" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Tambahkan kelas modal-lg dan modal-dialog-centered -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('data-buku.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Input Judul Buku -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Buku</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
    
                        <!-- Dropdown Kategori -->
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Dropdown Penulis -->
                        <div class="mb-3">
                            <label for="penulis_id" class="form-label">Penulis</label>
                            <select name="penulis_id" id="penulis_id" class="form-select" required>
                                <option value="">Pilih Penulis</option>
                                @foreach($penulis as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <!-- Input Stok -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" name="stock" id="stock" class="form-control" min="0" required>
                        </div>
                    </div>
    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    
    </div>
@endsection
