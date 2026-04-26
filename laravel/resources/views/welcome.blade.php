@extends('layouts.master')

@section('fullwidth', true)

@section('content')

    <style>
        /* CSS của bạn đang thiếu rất nhiều class:
    hero-wrap, hero-grid, hero-title, hero-images, stat...
    nên trang bị vỡ layout */

        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        /* FULL SCREEN */
        .hero-section {
            min-height: 100vh;
            width: 100%;
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .14), transparent 25%),
                radial-gradient(circle at 80% 30%, rgba(255, 255, 255, .10), transparent 22%),
                linear-gradient(-45deg, #1d4ed8, #0ea5e9, #10b981, #16a34a);
            background-size: 400% 400%;
            animation: bgMove 12s ease infinite;
        }

        /* content */
        .hero-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 60px 7%;
            position: relative;
            z-index: 5;
        }

        .hero-grid {
            width: 100%;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        /* text */
        .badge-top {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 40px;
            background: rgba(255, 255, 255, .15);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .hero-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 68px;
            line-height: 1.18;
            font-weight: 800;
            color: #fff;
            margin-bottom: 24px;
        }

        .hero-title span {
            color: #fde047;
        }

        .hero-desc {
            font-size: 20px;
            line-height: 1.9;
            color: rgba(255, 255, 255, .92);
            margin-bottom: 34px;
        }

        .hero-btn {
            display: inline-block;
            padding: 16px 36px;
            border-radius: 50px;
            background: #fff;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: 700;
            transition: .3s;
        }

        .hero-btn:hover {
            background: #f59e0b;
            color: #fff;
            transform: translateY(-4px);
        }

        /* stats */
        .stats {
            margin-top: 35px;
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .stat {
            padding: 18px 24px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .12);
            min-width: 170px;
        }

        .stat h4 {
            color: #fff;
            font-size: 30px;
            font-weight: 800;
        }

        .stat span {
            color: #fff;
            font-size: 14px;
        }

        /* image */
        .hero-images {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .image-card {
            position: relative;
            min-height: 290px;
            overflow: hidden;
            border-radius: 26px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
        }

        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .7s;
        }

        .image-card:hover img {
            transform: scale(1.08);
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .6), transparent);
        }

        .label {
            position: absolute;
            left: 18px;
            bottom: 16px;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
        }

        /* effects */
        .wave {
            position: absolute;
            bottom: -120px;
            left: -10%;
            width: 130%;
            height: 260px;
            background: rgba(255, 255, 255, .08);
            border-radius: 45%;
            animation: wave 18s linear infinite;
        }

        .wave2 {
            bottom: -150px;
            opacity: .5;
            animation-duration: 26s;
        }

        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .35);
            animation: fly linear infinite;
        }

        .p1 {
            left: 10%;
            bottom: -20px;
            animation-duration: 10s;
        }

        .p2 {
            left: 30%;
            bottom: -20px;
            animation-duration: 14s;
        }

        .p3 {
            left: 50%;
            bottom: -20px;
            animation-duration: 12s;
        }

        .p4 {
            left: 70%;
            bottom: -20px;
            animation-duration: 16s;
        }

        .p5 {
            left: 90%;
            bottom: -20px;
            animation-duration: 13s;
        }

        @keyframes bgMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes wave {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fly {
            from {
                transform: translateY(0);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            to {
                transform: translateY(-110vh);
                opacity: 0;
            }
        }

        @media(max-width:992px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .hero-title {
                font-size: 48px;
            }
        }

        @media(max-width:768px) {
            .hero-title {
                font-size: 34px;
            }

            .hero-desc {
                font-size: 16px;
            }

            .hero-images {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="hero-section">

        <div class="wave"></div>
        <div class="wave wave2"></div>

        <div class="particle p1"></div>
        <div class="particle p2"></div>
        <div class="particle p3"></div>
        <div class="particle p4"></div>
        <div class="particle p5"></div>

        <div class="hero-wrap">
            <div class="hero-grid">

                <!-- LEFT -->
                <div>

                    <div class="badge-top">ONLINE TOUR BOOKING PLATFORM</div>

                    <h1 class="hero-title">
                        Khám Phá Thế Giới<br>
                        Theo Cách <span>Chuyên Nghiệp</span>
                    </h1>

                    <p class="hero-desc">
                        Hệ thống booking du lịch hiện đại giúp người dùng tìm kiếm tour,
                        đặt chỗ nhanh chóng và thanh toán an toàn.
                    </p>

                    <a href="/tours" class="hero-btn">🚀 Khám Phá Tour Ngay</a>

                    <div class="stats">
                        <div class="stat">
                            <h4>24/7</h4>
                            <span>Hỗ trợ</span>
                        </div>

                        <div class="stat">
                            <h4>100%</h4>
                            <span>An toàn</span>
                        </div>

                        <div class="stat">
                            <h4>Real-time</h4>
                            <span>Cập nhật</span>
                        </div>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="hero-images">

                    <div class="image-card">
                        <img src="https://statics.vinpearl.com/y-nghia-ngay-quoc-khanh-2-9-13_1689837466.jpg">
                        <div class="overlay"></div>
                        <div class="label">🏖️ Tour Biển</div>
                    </div>

                    <div class="image-card">
                        <img src="https://gonatour.vn/vnt_upload/gallery/09_2019/ba_li.jpg">
                        <div class="overlay"></div>
                        <div class="label">⛰️ Tour Núi</div>
                    </div>

                    <div class="image-card">
                        <img src="https://i.pinimg.com/736x/0c/4d/e3/0c4de3927a9ea37f04c2fbd5bc6eff1f.jpg">
                        <div class="overlay"></div>
                        <div class="label">🏙️ City Tour</div>
                    </div>

                    <div class="image-card">
                        <img
                            src="https://th.bing.com/th/id/OIP.GZ3hr1J_lsktsxNgRTf7jwHaE0?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3">
                        <div class="overlay"></div>
                        <div class="label">🌏 Quốc Tế</div>
                    </div>

                </div>

            </div>
        </div>

    </section>

@endsection