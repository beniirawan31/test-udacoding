@extends('layout.app')

@section('title', "Siswa")

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Siswa</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="float-left">
                <a href="{{ route('siswa.tambah') }}" class="btn btn-primary">Tambah Siswa</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Nama Admin</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nis }}</td>
                                <td>{{ $item->tanggal_lahir }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->nama_user }}</td>
                                <td>
                                    <a href="{{ route('siswa.edit', ['id' => $item->id]) }}" class="btn btn-warning">Edit</a>
                                    <button class="btn btn-danger" onclick="hapusData('{{ $item->id }}')">Hapus</button>
                                    <form id="deleteForm{{ $item->id }}" action="{{ route('siswa.delete', ['id' => $item->id]) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Tambahkan skrip JavaScript -->
<script>
    function hapusData(id) {
        // Tampilkan konfirmasi
        if (confirm('Apakah Anda yakin ingin menghapus data?')) {
            // Temukan formulir dengan ID yang sesuai dan kirimkan permintaan POST
            document.getElementById(`deleteForm${id}`).submit();
        }
    }
</script>

@endsection
