<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>

    {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> --}}
    <link href="{{ asset('public/backend/css/style.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/backend/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @yield('css')

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">ระบบลาออนไลน์</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <ul class="navbar-nav  ms-auto me-0 me-md-3 my-2 my-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i>
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">การตั้งค่า</a></li>
                    {{-- <li><a class="dropdown-item" href="#!">Activity Log</a></li> --}}
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">เมนู</div>

                        <a class="nav-link" href="{{ route('noti') }}">
                            <div class="sb-nav-link-icon"><i id="iconnoti" class="fa-regular fa-bell"></i>
                                <span style="color: yellow" id='noti_span'></span>
                            </div>
                            แจ้งเตือน
                        </a>

                        @if (auth()->user()->type == 'user')
                            <a class="nav-link" href="{{ route('leave.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                การลาของฉัน
                            </a>
                            <a class="nav-link" href="{{ route('leave.request') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-reply"></i></div>
                                ยื่นเรื่องขอลา
                            </a>
                        @endif

                        @if (auth()->user()->type == 'admin')
                            <a class="nav-link" href="{{ route('admin.leaves.listdata') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                อนุมัติการลา

                            </a>
                            <a class="nav-link" href="{{ route('leaveType') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                ประเภทการลา

                            </a>
                            <a class="nav-link" href="{{ route('logLogin') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Log login

                            </a>
                        @endif
                  
                        <a class="nav-link" href="{{ route('logout') }}"   onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" >
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-right-from-bracket"></i></div>
                            ออกจากระบบ
                        </a>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Start Bootstrap
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
                @yield('content')

            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <input type="hidden" id="user_type_not" value="{{Auth::user()->type}}">
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"> --}}
    </script>
    <script src="{{ asset('public/backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/backend/js/scripts.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('public/backend/assets/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('public/backend/assets/demo/chart-bar-demo.js') }}"></script> --}}
    <script src="{{ asset('public/backend/js/simple-datatables.min.js') }}"></script>
    <script src="{{ asset('public/backend/js/datatables-simple-demo.js') }}"></script>
    <script src="{{ asset('public/backend/js/sweetalert2.js') }}"></script>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
    <script>
        $(document).ready(function() {

            let csrfToken = $('meta[name="csrf-token"]').attr('content'); // เก็บค่า CSRF token
            var url ='';
            if($('#user_type_not').val()=='admin'){
                url = '{{ route('seenNoti') }}';
            }else{
                url = '{{ route('seenNotiUser') }}';
            }
            
            $.ajax({
                url: url,
                method: "GET",
                // data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                },
                contentType: false,
                processData: false,
                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(data) {
                    // console.log(data);
                    if (data.success == true) {
                        // data.res['unread_count'];

                        if (data.data.unread_count > 0) {
                            $('#noti_span').html(' (' + data.data.unread_count + ') ')
                            $("#iconnoti").css("color", "yellow");
                        }



                    } else if (data.success == false) {
                        printErrorMsg(data.msg);
                    } else {
                        printValidationErrorMsg(data.msg);
                    }

                }

            });
        });
    </script>
</body>

</html>
