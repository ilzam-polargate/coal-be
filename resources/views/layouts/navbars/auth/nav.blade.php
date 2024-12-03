<!-- Navbar -->

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (Popper included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-2 px-3">
        {{-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">{{ str_replace('-', ' ', Request::path()) }}</li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ str_replace('-', ' ', Request::path()) }}</h6>
        </nav> --}}
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <ul class="navbar-nav justify-content-end">
                <!-- Dropdown User Profile -->
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="#" class="nav-link text-body p-0 dropdown-toggle" id="dropdownUserMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person me-sm-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownUserMenu">
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="{{ url('/user-profile') }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="bi bi-person me-sm-2"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">My Profile</h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item border-radius-md" href="{{ url('/logout') }}">
                                <div class="d-flex py-1">
                                    <div class="my-auto">
                                        <i class="bi bi-box-arrow-right me-sm-2"></i>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="text-sm font-weight-normal mb-1">Sign Out</h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Toggler for sidenav (for mobile view) -->
                <li class="nav-item d-xl-none px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>

                {{-- <!-- Settings icon -->
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                </li> --}}

                <!-- Notifications dropdown -->
                @php
                    use App\Models\Notification;
                    // Ambil 5 notifikasi terbaru yang belum dibaca (read_at = null)
                    $notifications = Notification::whereNull('read_at')->orderBy('created_at', 'desc')->take(5)->get();
                    // Hitung jumlah notifikasi yang belum dibaca
                    $unreadCount = $notifications->count();
                @endphp
                <li class="nav-item dropdown d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                        <!-- Tampilkan badge hanya jika ada notifikasi -->
                        @if($unreadCount > 0)
                            <span class="badge bg-danger" id="unread-count">{{ $unreadCount }}</span> <!-- Jumlah notifikasi yang belum dibaca -->
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownMenuButton">
                        <!-- Jika ada notifikasi -->
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notification)
                            <li class="mb-2">
                                <a class="dropdown-item border-radius-md mark-as-read" href="{{ route('clients.index') }}" data-id="{{ $notification->id }}">
                                    <div class="d-flex py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal mb-1">{{ $notification->body }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="fa fa-clock me-1"></i> {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        @else
                            <!-- Tampilkan pesan jika tidak ada notifikasi -->
                            <!-- Tampilkan pesan jika tidak ada notifikasi -->
                            <li class="mb-2">
                                <div class="dropdown-item border-radius-md text-center">
                                    <i class="fa fa-bell-slash fa-2x text-muted mb-2"></i>
                                    <h6 class="text-sm font-weight-normal mb-1">Tidak ada notifikasi saat ini</h6>
                                </div>
                            </li>
                        @endif
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
