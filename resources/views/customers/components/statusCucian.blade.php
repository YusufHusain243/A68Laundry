<div class="why-choose-section py-5" id="statusCucian">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <h2 class="section-title mb-4">Pantau Status Cucian</h2>
                <p class="section-subtitle mb-4">Cek status cucian Anda dengan mudah dan cepat</p>

                <!-- Form -->
                <form id="statusCucianForm" class="search-form mb-4">
                    <div class="input-group">
                        <input type="text" name="order_code" id="orderCodeInput" class="form-control"
                            placeholder="Masukkan Kode Order" required>
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </form>

                <!-- Loader -->
                <div id="loadingSpinner" class="my-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Hasil -->
                <div id="statusCucianResult" class="search-results mt-4"></div>
            </div>
        </div>
    </div>
</div>