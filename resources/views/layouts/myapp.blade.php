<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - University Track</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #f4f6f9;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #111827;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #1f2937;
            color: white;
        }

        .main {
            margin-left: 250px;
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h4 class="text-white mb-4">
        🎓 University Track
    </h4>

    <a href="{{ route('admin.index') }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ route('students.index') }}">
        <i class="bi bi-people"></i> Students
    </a>

    <a href="{{ route('departments.index') }}">
        <i class="bi bi-journal"></i> Departments
    </a>

    <a href="#">
        <i class="bi bi-calendar"></i> Timetable
    </a>

    <hr class="text-secondary">

    <a href="#">
        <i class="bi bi-gear"></i> Settings
    </a>

</div>

<!-- MAIN -->
<div class="main">

    <!-- TOP BAR -->
    <div class="topbar">

        <h5 class="mb-0">@yield('title')</h5>

        <div class="d-flex align-items-center gap-3">

            <!-- Search -->
            <input type="text" class="form-control form-control-sm" placeholder="Search...">

            <!-- Role Badge -->
            <span class="badge bg-primary">
                Admin
            </span>

            <!-- Profile -->
            <div class="dropdown">

                <a class="dropdown-toggle text-dark text-decoration-none"
                   data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle fs-5"></i>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            Profile
                        </a>
                    </li>

                    <li><a class="dropdown-item" href="#">Settings</a></li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="content">

        @yield('content')

    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // DELETE CONFIRM
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            let form = this.closest('form');

            Swal.fire({
                title: "Delete Confirmation",
                text: "This record will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SUCCESS MESSAGE
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

});
</script>

</body>
</html>