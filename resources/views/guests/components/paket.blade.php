<div class="overflow-hidden py-7 py-sm-8 py-xl-9" id="paketMember">
    <div class="container">
        <div>
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="m-0 text-primary-emphasis text-base leading-7 fw-semibold">
                    Paket Member
                </h2>
                <p class="m-0 mt-2 text-body-emphasis text-4xl tracking-tight fw-bold">
                    Layanan Laundry Premium Untuk Pelanggan Setia
                </p>
                <p class="m-0 mt-4 text-body text-lg leading-8">
                    Nikmati Kemudahan Berlangganan Layanan Laundry Premium Kami
                </p>
            </div>
        </div>
        <div>
            <div
                class="row row-cols-1 row-cols-xl-3 gy-5 gx-xl-4 mt-1 justify-content-center justify-content-xl-between">
                @foreach ($paketLaundry as $pl)
                    <div class="col pt-5 pt-xl-4">
                        <div class="max-w-xl mx-auto mx-xl-0" data-aos-delay="0" data-aos="fade"
                            data-aos-duration="1000">
                            <div class="ratio" style="--bs-aspect-ratio: 66.66%;">
                                <img src="/images/{{ $pl->jenisLaundry->foto }}" class="object-fit-cover rounded-3"
                                    alt="Service image" loading="lazy">
                            </div>
                            <h3 class="m-0 mt-4 text-body-emphasis text-lg leading-6 fw-semibold">
                                {{ $pl->jenisLaundry->nama }}
                            </h3>
                            <h3 class="m-0 mt-1 text-primary-emphasis text-2xl leading-6 fw-bold">
                                Rp. {{ $pl->harga }} / {{ $pl->berat }}KG
                            </h3>
                            <a href="/paketLaundryMember" class="btn btn-lg btn-primary text-white text-sm fw-semibold mt-3"
                                data-aos-delay="200" data-aos="fade" data-aos-duration="3000">
                                Daftar Paket
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
