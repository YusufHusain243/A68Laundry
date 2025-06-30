<!-- Start Product Section -->
<div class="product-section" id="laundry">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                <h2 class="mb-4 section-title">Layanan Laundry yang Dapat Anda Percaya</h2>
                <p class="mb-4">Kami menyediakan layanan laundry berkualitas tinggi dengan fokus pada kepuasan
                    pelanggan. Rasakan kenyamanan solusi laundry profesional kami.</p>
                <p><a href="/laundry" class="btn">Explore</a></p>
            </div>

            @foreach ($laundry as $l)
                <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                    <a class="product-item" href="/keranjang/store/{{$l->id}}">
                        <img src="{{ asset('images/' . $l->foto) }}"
                            class="img-fluid product-thumbnail">
                        <h3 class="product-title">{{ $l->nama }}</h3>
                        <strong class="product-price">{{ $l->harga }}/KG</strong>

                        <span class="icon-cross">
                            <img src="{{ asset('assets_customers/images/cross.svg') }}" class="img-fluid">
                        </span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- End Product Section -->
