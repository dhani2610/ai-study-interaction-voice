@extends('backend.layouts-new.app')

@section('content')
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .select2 {
            width: 100% !important;
        }

        label {
            float: left;
            color: black;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 10px !important;
            margin-top: 10px !important;
        }
    </style>

    <div class="main-content-inner">
        <div class="row">
            <form action="{{ isset($article) ? route('article.update', $article->id) : route('article.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title text-center">{{ isset($article) ? 'Edit' : 'Tambah' }} Materi</h4>
                            <hr>
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" required name="judul" class="form-control"
                                    value="{{ $article->judul ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label>Topic</label>
                                <select required name="id_topic" class="form-control" required>
                                    @foreach ($topics as $topic)
                                        <option value="{{ $topic->id }}"
                                            {{ isset($article) && $article->id_topic == $topic->id ? 'selected' : '' }}>
                                            {{ $topic->topic }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" required name="tanggal" class="form-control"
                                    value="{{ $article->tanggal ?? date('Y-m-d') }}" required>
                            </div>


                            <div class="form-group">
                                <label>Cover</label>
                                <input type="file" name="cover" class="form-control">
                                @if (isset($article))
                                    <img src="{{ asset('assets/img/cover_article/' . $article->cover) }}" width="150"
                                        class="mt-2">
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Materi AI</label>
                                <textarea required name="content" class="form-control" rows="10">{{ $article->content }}</textarea>
                                <small class="text-danger">Materi ini bukan untuk ditampilkan ke murid, akan tetapi untuk knowladge AI</small>
                            </div>
                            
                            <button class="btn btn-primary mt-3" type="submit">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .then(editor => {
                editor.editing.view.change(writer => {
                    writer.setStyle('min-height', '400px', editor.editing.view.document.getRoot());
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
