<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Travel Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;

            /* 🎨 NỀN MỚI: XANH PASTEL ĐẬM HƠN + CÓ CHIỀU SÂU */
            background: linear-gradient(135deg,
                    #a2bada 0%,
                    /* xanh pastel đậm hơn */
                    #6593b1 40%,
                    /* trắng xanh */
                    #9fdab4 100%
                    /* xanh lá nhạt */
                );

            min-height: 100vh;
        }

        /* gradient navbar đẹp hơn */
        .bg-main {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* card nâng cấp */
        .card-custom {
            border: none;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.95);
            /* glass effect nhẹ */
            backdrop-filter: blur(6px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        /* button đẹp hơn */
        .btn-main {
            background: linear-gradient(135deg, #0d6efd, #22c55e);
            color: white;
            border: none;
            border-radius: 10px;
            transition: 0.3s;
        }

        .btn-main:hover {
            transform: scale(1.03);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        /* animation mượt hơn */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* container đẹp hơn */
        .main-content {
            min-height: 80vh;
        }

        /* table đẹp hơn */
        .table {
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 3px;
        }

        .pagination .active .page-link {
            background: #0d6efd;
            border: none;
        }

        td {
            vertical-align: middle !important;
        }

        .table-hover tbody tr:hover {
            background: rgba(13, 110, 253, 0.05);
        }

        /* hover row bảng */
        .table tbody tr {
            transition: all 0.25s ease;
        }

        .table tbody tr:hover {
            background: rgba(13, 110, 253, 0.05);
            /* xanh nhẹ */
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* button hover */
        .btn-main {
            transition: all 0.25s ease;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.3);
        }

        /* nút cancel */
        .btn-danger {
            transition: all 0.25s ease;
        }

        .btn-danger:hover {
            transform: scale(1.1);
        }

        .badge {
            padding: 6px 10px;
            border-radius: 12px;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .fade-in {
            animation: fadeSlide 0.4s ease;
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-custom {
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .hover-glow:hover {
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.6);
        }

        .table tbody tr:hover td {
            color: #0d6efd;
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <div class="container main-content mt-4 fade-in">
        @include('layouts.alert')

        @yield('content')
    </div>

    @include('layouts.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>