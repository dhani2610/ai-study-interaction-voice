@extends('backend.layouts-new.app')

@section('content')
    <style>
        /* Select2 custom search bar style */
        .select2-container--default .select2-selection--single {
            height: 40px;
            border-radius: 25px;
            border: 1px solid #ced4da;
            padding: 5px 15px;
            font-size: 16px;
            box-shadow: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            right: 10px;
            width: 20px;
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 39px !important;
            user-select: none;
            -webkit-user-select: none;
        }

        /* Card styling with image left and text right */
        .card {
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 139px;
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card-img-left {
            width: 140px;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            margin-left: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .card-body {
            flex: 1;
            padding: 15px 20px;
            text-align: left;
        }

        .card-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .card-text {
            font-size: 15px;
            color: #555;
            margin: 0;
        }

        /* Responsive for smaller devices */
        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                height: auto;
                text-align: center;
            }

            .card-img-left {
                width: 100%;
                height: 180px;
                margin: 0 0 15px 0;
                border-radius: 15px;
            }

            .card-body {
                padding: 0 10px 15px 10px;
            }
        }
    </style>

    <div class="container py-5">
        <div class="text-center mb-4">
            <select id="topic_filter" class="form-control" style="max-width: 400px; margin: 0 auto;">
                <option value="all">Cari Berdasarkan Topik</option>
                @foreach (\App\Models\Topic::all() as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->topic }}</option>
                @endforeach
            </select>
        </div>

        <div id="article_results" class="row justify-content-center">
            {{-- Artikel akan muncul di sini --}}
        </div>

        <!-- Modal Detail Artikel -->
        <div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="articleModalLabel">Detail Artikel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                            <div id="youtubePlayerContainer" class="mb-3 text-center"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#topic_filter').select2({
                placeholder: "Cari Berdasarkan Topik",
                allowClear: true,
                width: 'resolve'
            });

            function loadArticles(topic_id = 'all') {
                $.get("{{ route('proses-search-article') }}", {
                    topic_id: topic_id
                }, function(data) {
                    let html = '';
                    if (data.articles.length > 0) {
                        data.articles.forEach(function(article, index) {
                            html += `
                <div class="col-md-8 col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm article-card" data-cover="${article.cover}" data-judul="${article.judul}" data-content="${encodeURIComponent(article.content)}">
                        <img src="{{ asset('assets/img/cover_article') }}/${article.cover}" class="card-img-left" alt="cover">
                        <div class="card-body">
                            <h5 class="card-title">${article.judul}</h5>
                        </div>
                    </div>
                </div>
                `;
                        });
                    } else {
                        html = `<div class="col-12 text-center"><p>Tidak ada artikel ditemukan.</p></div>`;
                    }
                    $('#article_results').html(html);

                    // Tambahkan event click setelah rendering selesai
                    $('.article-card').on('click', function() {
                        const content = decodeURIComponent($(this).data(
                            'content')); // ini URL biasa
                        const judul = $(this).data('judul');

                        const embedUrl = convertToEmbed(content);
                        if (embedUrl) {
                            const iframe = `<iframe width="100%" height="400" src="${embedUrl}"
                                    frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>`;

                            $('#youtubePlayerContainer').html(iframe);
                            $('#articleModalLabel').text(judul);
                            $('#articleModal').modal('show');
                        } else {
                            $('#youtubePlayerContainer').html(
                                '<p>Video tidak valid atau tidak dikenali.</p>');
                        }
                    });

                });
            }

            function convertToEmbed(url) {
                const regExp = /^.*(youtu\.be\/|youtube\.com\/(watch\?v=|embed\/|v\/|shorts\/))([^#\&\?]*).*/;
                const match = url.match(regExp);
                return match && match[3] ? `https://www.youtube.com/embed/${match[3]}` : null;
            }


            loadArticles();

            $('#topic_filter').on('change', function() {
                loadArticles($(this).val());
            });
        });
    </script>
@endsection
