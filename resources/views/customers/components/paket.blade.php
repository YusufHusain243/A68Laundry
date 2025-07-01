<!-- Start Paket Laundry Section -->
<div class="product-section" id="paket">
    <div class="container">
        <div class="row justify-content-end">
            @foreach ($paket as $p)
                <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                    <a class="product-item" href="/paket/payment/{{$p->id}}">
                        <img src="{{ asset('images/' . $p->jenisLaundry->foto) }}"
                            class="img-fluid product-thumbnail">
                        <h3 class="product-title">{{ $p->jenisLaundry->nama }}</h3>
                        <strong class="product-price">{{ $p->harga }}/{{ $p->berat }}KG</strong>
                        <span class="icon-cross">
                            <img src="{{ asset('assets_customers/images/cross.svg') }}" class="img-fluid">
                        </span>
                    </a>
                </div>
            @endforeach

            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Paket Laundry Terbaik untuk Anda</h2>
                <p class="mb-4">Pilih berbagai paket laundry yang kami sediakan sesuai kebutuhan Anda. Nikmati layanan profesional dengan harga terjangkau dan hasil maksimal.</p>
                <p><a href="/paket" class="btn">Lihat Semua Paket</a></p>
            </div>
        </div>
    </div>
</div>
<!-- End Paket Laundry Section -->
