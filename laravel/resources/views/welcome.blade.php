@extends('layouts.master')

@section('fullwidth', true)

@section('content')

   
<section class="hero-section fade-in">

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