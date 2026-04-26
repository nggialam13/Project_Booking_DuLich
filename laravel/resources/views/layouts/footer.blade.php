<footer class="footer-main">

    <div class="container">

        <div class="row gy-4">

            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <h3>🌍 Travel Booking</h3>
                    <p>
                        Nền tảng đặt tour du lịch trực tuyến hiện đại,
                        giúp bạn khám phá những điểm đến tuyệt vời với
                        trải nghiệm nhanh chóng, an toàn và tiện lợi.
                    </p>

                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title">Điều Hướng</h5>
                <ul class="footer-links">
                    <li><a href="/">Trang chủ</a></li>
                    <li><a href="/tours">Tours</a></li>
                    <li><a href="/bookings">Booking</a></li>
                    <li><a href="/login">Đăng nhập</a></li>
                </ul>
            </div>

            <!-- Tour -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Danh Mục Tour</h5>
                <ul class="footer-links">
                    <li><a href="#">🏖️ Tour Biển</a></li>
                    <li><a href="#">⛰️ Tour Núi</a></li>
                    <li><a href="#">🏙️ City Tour</a></li>
                    <li><a href="#">🌏 Tour Quốc Tế</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Liên Hệ</h5>

                <ul class="footer-contact">
                    <li><i class="fas fa-phone"></i> 0123 456 789</li>
                    <li><i class="fas fa-envelope"></i> support@travelbooking.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> TP.HCM, Việt Nam</li>
                    <li><i class="fas fa-clock"></i> Hỗ trợ 24/7</li>
                </ul>
            </div>

        </div>

        <hr class="footer-line">

        <div class="footer-bottom">
            <p class="mb-0">© 2026 Travel Booking. All Rights Reserved.</p>
            <span>Thiết kế dành cho hệ thống Booking Du Lịch</span>
        </div>

    </div>

</footer>

<style>
.footer-main{
    background:linear-gradient(135deg,#0f172a,#1e293b,#0ea5e9);
    color:#fff;
    padding:70px 0 25px;
    margin-top:80px;
    position:relative;
    overflow:hidden;
}

.footer-main::before{
    content:'';
    position:absolute;
    top:-100px;
    right:-100px;
    width:280px;
    height:280px;
    border-radius:50%;
    background:rgba(255,255,255,.05);
}

.footer-main::after{
    content:'';
    position:absolute;
    bottom:-120px;
    left:-80px;
    width:260px;
    height:260px;
    border-radius:50%;
    background:rgba(34,197,94,.08);
}

.footer-brand h3{
    font-size:30px;
    font-weight:700;
    margin-bottom:15px;
}

.footer-brand p{
    color:rgba(255,255,255,.85);
    line-height:1.9;
    font-size:15px;
}

.footer-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:18px;
    color:#fde047;
}

.footer-links,
.footer-contact{
    list-style:none;
    padding:0;
    margin:0;
}

.footer-links li,
.footer-contact li{
    margin-bottom:12px;
}

.footer-links a{
    color:rgba(255,255,255,.88);
    text-decoration:none;
    transition:.3s;
}

.footer-links a:hover{
    color:#fff;
    padding-left:6px;
}

.footer-contact li{
    color:rgba(255,255,255,.88);
    font-size:15px;
}

.footer-contact i{
    color:#22c55e;
    width:22px;
}

.social-links{
    margin-top:18px;
    display:flex;
    gap:12px;
}

.social-links a{
    width:40px;
    height:40px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(255,255,255,.12);
    color:#fff;
    text-decoration:none;
    transition:.3s;
}

.social-links a:hover{
    background:#f97316;
    transform:translateY(-4px);
}

.footer-line{
    border-color:rgba(255,255,255,.12);
    margin:35px 0 20px;
}

.footer-bottom{
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
    color:rgba(255,255,255,.75);
    font-size:14px;
}

@media(max-width:768px){
    .footer-bottom{
        flex-direction:column;
        text-align:center;
    }

    .footer-main{
        padding:55px 0 20px;
    }
}
</style>