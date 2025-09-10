@extends('backend.layouts-new.app')

@section('content')
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Data Article</h4>
                    <a href="{{ route('article.create') }}" class="btn btn-primary float-right mb-3">Tambah Article</a>
                    <div class="clearfix"></div>
                    @include('backend.layouts.partials.messages')
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Topic</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($article as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->judul }}</td>
                                <td>{{ $item->topic->topic ?? '-' }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>
                                    <a href="{{ route('article.edit', $item->id) }}" class="btn btn-success text-white"><i class="fa fa-edit"></i></a>
                                    <a onclick="confirmDelete('{{ route('article.destroy', $item->id) }}')" class="btn btn-danger text-white"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function confirmDelete(url) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
@endsection
