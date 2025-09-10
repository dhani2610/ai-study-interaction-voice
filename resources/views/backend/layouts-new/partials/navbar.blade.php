<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar" style="    background: #7d727200 !important;
    border: 2px solid #4d4545">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" id="searchInput" class="form-control border-0 shadow-none ps-1 ps-sm-2"
                    placeholder="Search..." aria-label="Search..." style="background: transparent;" />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <img src="{{ asset('assets/img/cover/flag-indo.png') }}" alt class="w-px-40 h-auto " />
            <li class="nav-item navbar-dropdown dropdown-user dropdown ">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/logos/logo-study-english.webp') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">

                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/logos/logo-study-english.webp') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    @php
                                        $usr = Auth::guard('admin')->user();
                                    @endphp
                                    @if ($usr != null)
                                        <span class="fw-medium d-block">{{ Auth::guard('admin')->user()->name }}</span>
                                    @else
                                        <span class="fw-medium d-block">Quest</span>
                                    @endif
                                    {{-- <small class="text-muted">Admin</small> --}}
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        @if ($usr != null)
                            <form id="admin-logout-form" action="{{ route('admin.logout.submit') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>

                            <a class="dropdown-item" href="javascript:void(0);"
                                onclick="event.preventDefault();
                        document.getElementById('admin-logout-form').submit();">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        @else
                            <a class="dropdown-item" href="{{ url('admin/login') }}">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Login</span>
                            </a>
                        @endif

                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Event listener untuk input search
        $('#searchInput').on('input', function() {
            var query = $(this).val().toLowerCase(); // Ambil nilai input dan convert ke lowercase
            var noResults = true; // Flag untuk mengecek jika tidak ada hasil

            // Jika input kosong, tampilkan semua course
            if (query === '') {
                $('.course-item').show(); // Tampilkan semua materi
                $('#noResultsMessage').hide(); // Sembunyikan pesan "Tidak ada hasil"
            } else {
                // Looping setiap course untuk menyaring hasil berdasarkan pencarian
                $('.course-item').each(function() {
                    var title = $(this).find('.course-title').text()
                .toLowerCase(); // Ambil judul kursus dan convert ke lowercase

                    // Jika judul mengandung query, tampilkan materi, jika tidak sembunyikan
                    if (title.includes(query)) {
                        $(this).show(); // Tampilkan item
                        noResults = false; // Set flag ke false jika ada hasil
                    } else {
                        $(this).hide(); // Sembunyikan item
                    }
                });

                // Jika tidak ada hasil pencarian
                if (noResults) {
                    $('#noResultsMessage').show(); // Tampilkan pesan "Tidak ada hasil"
                    $('#searchQueryText').text(query); // Tampilkan keyword yang dicari
                } else {
                    $('#noResultsMessage').hide(); // Sembunyikan pesan jika ada hasil
                }
            }
        });
    });
</script>
