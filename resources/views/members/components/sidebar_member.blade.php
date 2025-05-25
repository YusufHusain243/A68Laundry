<!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="/" class="b-brand text-primary">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('assets_customer/logo/logo.png') }}" alt="logo_upr" class="logo logo-lg"
                            width="50" />
                    </div>
                    <div class="col text-start">
                        <span style="font-weight: bold; font-size: 1.5rem; color: black;">A68</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item pc-caption">
                    <label>Menu</label>
                    <i class="ti ti-dashboard"></i>
                </li>

                <li class="pc-item">
                    <a href="/paketLaundryMember" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-folder"></i></span>
                        <span class="pc-mtext">Paket Laundry</span>
                    </a>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-credit-card"></i></span>
                        <span class="pc-mtext">Order Laundry</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a class="pc-link" href="/orderLangsung">
                                Langsung/Tanpa Paket
                            </a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="/orderPaket">
                                Order Paket
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="w-100 text-center">
                <div class="badge theme-version badge rounded-pill bg-light text-dark f-12"></div>
            </div>
        </div>
    </div>
</nav>
<!-- [ Sidebar Menu ] end -->
