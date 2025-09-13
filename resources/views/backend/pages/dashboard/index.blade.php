@extends('backend.layouts-new.app')

@section('content')
    <style>
        /* === General Style === */
        body {
            font-family: 'Comic Neue', cursive;
        }

        /* Select2 Custom */
        .select2-container--default .select2-selection--single {
            height: 50px;
            border-radius: 30px;
            border: 2px solid #FF6B6B;
            padding: 10px 20px;
            font-size: 18px;
            background: #fff3f3;
            transition: all 0.3s ease;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #ff4d4d;
            box-shadow: 0 0 8px rgba(255, 77, 77, 0.4);
        }

        /* Card styling */
        .card {
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            align-items: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            height: 160px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            border: none;
        }

        .card:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }

        .card-img-left {
            width: 140px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            flex-shrink: 0;
        }

        .card-body {
            flex: 1;
            padding: 15px 20px;
            text-align: left;
        }

        .card-title {
            font-weight: 800;
            font-size: 20px;
            margin-bottom: 8px;
            color: #ff4d4d;
        }

        .card-text {
            font-size: 16px;
            color: #444;
            margin: 0;
        }

        /* Empty message */
        .empty-message {
            font-size: 18px;
            font-weight: bold;
            color: #ff4d4d;
            background: #fff3f3;
            padding: 15px;
            border-radius: 15px;
            display: inline-block;
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
                height: 200px;
                margin: 0 0 15px 0;
            }

            .card-body {
                padding: 10px;
            }
        }
    </style>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 style="color:#ff4d4d; font-weight:900;">📚 Pilih Topik Belajar</h2>
            <select id="topic_filter" class="form-control mt-3" style="max-width: 400px; margin: 0 auto;">
                <option value="all">✨ Semua Topik</option>
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
                <div class="modal-content rounded-4">
                    <div class="modal-header" style="background:#ff6b6b; color:#fff;">
                        <h5 class="modal-title" id="articleModalLabel">📖 Detail Artikel</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
            // $('#topic_filter').select2({
            //     placeholder: "✨ Cari Berdasarkan Topik",
            //     allowClear: true,
            //     width: 'resolve'
            // });

            function loadArticles(topic_id = 'all') {
                $.get("{{ route('proses-search-article') }}", {
                    topic_id: topic_id
                }, function(data) {
                    let html = '';
                    if (data.articles.length > 0) {
                        data.articles.forEach(function(article, index) {
                            html += `
                                <div class="col-md-8 col-lg-6 mb-4">
                                    <a href="{{ url('admin/voice-ai') }}/${article.id}" 
                                       class="text-decoration-none">
                                        <div class="card h-100">
                                            <img src="{{ asset('assets/img/cover_article') }}/${article.cover}" 
                                                 class="card-img-left" alt="cover">
                                            <div class="card-body">
                                                <h5 class="card-title">🎨 ${article.judul}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        });
                    } else {
                        html = `<div class="col-12 text-center"><p class="empty-message">😢 Tidak ada materi ditemukan.</p></div>`;
                    }
                    $('#article_results').html(html);
                });
            }

            loadArticles();

            $('#topic_filter').on('change', function() {
                loadArticles($(this).val());
            });
        });
    </script>
@endsection
