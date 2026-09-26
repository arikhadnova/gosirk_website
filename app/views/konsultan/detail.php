<style>
    .detail-hero {
        padding: 80px 0;
        background: linear-gradient(rgba(13, 74, 124, 0.05), rgba(13, 74, 124, 0.02));
    }
    .service-category-tag {
        color: var(--gosirk-orange);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
        display: block;
    }
    .detail-title {
        font-weight: 800;
        font-size: 3rem;
        line-height: 1.2;
        color: var(--gi-dark);
        margin-bottom: 25px;
    }
    .detail-desc {
        color: #64748b;
        font-size: 1.15rem;
        line-height: 1.8;
        margin-bottom: 35px;
        max-width: 650px;
    }
    .hero-img-wrapper {
        position: relative;
        padding: 10px;
    }
    .hero-img-wrapper img {
        border-radius: 30px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .btn-orange-gi {
        background-color: var(--gosirk-orange);
        color: white;
        border: none;
        padding: 14px 35px;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-orange-gi:hover {
        background-color: var(--gosirk-orange-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255,143,86,0.3);
    }
    
    .section-title {
        font-weight: 700;
        font-size: 1.75rem;
        color: var(--gi-dark);
        margin-bottom: 25px;
        position: relative;
        padding-left: 20px;
    }
    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: var(--gosirk-orange);
        border-radius: 5px;
    }
    
    .content-card {
        background: white;
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    
    .feature-item {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }
    .feature-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 143, 86, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gosirk-orange);
        flex-shrink: 0;
    }
    .feature-content h6 {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 8px;
    }
    .feature-content p {
        color: #64748b;
        margin-bottom: 0;
        font-size: 0.95rem;
    }
    
    .sidebar-card {
        background: #0d4a7c;
        color: white;
        padding: 40px;
        border-radius: 30px;
        position: sticky;
        top: 100px;
    }
    .sidebar-card .btn-light {
        color: #0d4a7c !important;
        font-weight: 700;
        border-radius: 50px;
        padding: 12px;
        margin-top: 20px;
    }
    
    /* Table Styling */
    .table-custom {
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid #eee;
    }
    .table-custom thead th {
        background: #f8fafc;
        padding: 20px;
        font-weight: 700;
        color: var(--gi-dark);
        border: none;
    }
    .table-custom tbody td {
        padding: 20px;
        border-top: 1px solid #f1f5f9;
        color: #475569;
    }
</style>

<div class="konsultan-detail-page">
    <!-- HERO SECTION -->
    <section class="detail-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-5">
                <ol class="breadcrumb" style="font-size: 0.9rem;">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none text-muted">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>konsultan" class="text-decoration-none text-muted">Konsultan</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Detail Layanan</li>
                </ol>
            </nav>
            
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="service-category-tag">Strategic Advisory</span>
                    <h1 class="detail-title">Optimasi Kebijakan & Roadmap Strategis</h1>
                    <p class="detail-desc">
                        Pendampingan komprehensif dalam penyusunan regulasi, Masterplan, dan Roadmap Pengelolaan Sampah yang adaptif terhadap target nasional (JAKSTRADA) dan potensi regional.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?= $this->waLink('Halo GoSirk, saya ingin konsultasi gratis terkait layanan Optimasi Kebijakan & Roadmap Strategis.') ?>" target="_blank" rel="noopener" class="btn btn-orange-gi">Konsultasi Gratis</a>
                        <a href="#" class="btn btn-outline-dark rounded-pill px-4 py-3 fw-bold">Unduh Company Profile</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-img-wrapper">
                        <img loading="lazy" decoding="async" src="<?= ASSETS_URL ?>img/IMG_8084.jpg" alt="Service Detail">
                        <div class="position-absolute translate-middle-y end-0 me-n3 top-50 bg-white p-4 rounded-4 shadow-lg d-none d-xl-block" style="width: 200px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-success">verified</span>
                                <span class="fw-bold small">Data Driven</span>
                            </div>
                            <p class="small text-muted mb-0">Rekomendasi berbasis analisis primer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTENT SECTION -->
    <section class="py-5 my-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <!-- Overview -->
                    <div class="mb-5">
                        <h2 class="section-title">Mengapa Memilih Layanan Kami?</h2>
                        <div class="content-card">
                            <p class="mb-4" style="text-align: justify;">
                                Kami memahami bahwa setiap daerah dan organisasi memiliki karakteristik yang unik. Layanan advisory kami tidak menggunakan pendekatan "satu solusi untuk semua", melainkan membedah tantangan spesifik di lapangan untuk menghasilkan rekomendasi yang pragmatis dan dapat dieksekusi.
                            </p>
                            <div class="row g-4 mt-2">
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <span class="material-symbols-outlined">analytics</span>
                                        </div>
                                        <div class="feature-content">
                                            <h6>Kajian Komprehensif</h6>
                                            <p>Analisis mendalam dari aspek teknis, hukum, hingga finansial.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <span class="material-symbols-outlined">groups</span>
                                        </div>
                                        <div class="feature-content">
                                            <h6>Fasilitasi Stakeholder</h6>
                                            <p>Mengelola dialog antar pihak untuk konsensus kebijakan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Scope of Work -->
                    <div class="mb-5 pt-4">
                        <h2 class="section-title">Lingkup Pekerjaan</h2>
                        <div class="table-responsive table-custom">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Fase Proyek</th>
                                        <th>Output Utama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Assessment & Baseline</td>
                                        <td>Dokumen Audit Timbulan & Karakteristik Sampah.</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Strategi & Modeling</td>
                                        <td>Model Bisnis & Proyeksi Keuangan (Fiskal).</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Finalisasi & Roadmap</td>
                                        <td>Dokumen Masterplan & Rencana Aksi Jangka Panjang.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">
                    <div class="sidebar-card">
                        <h3 class="fw-bold mb-4">Mulai Kolaborasi</h3>
                        <p class="opacity-75 mb-4">Jadwalkan pertemuan awal untuk membedah kebutuhan organisasi Anda secara detail.</p>
                        
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <span class="material-symbols-outlined text-orange">event_available</span>
                                <span>Estimasi: 3-6 Bulan Kerja</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="material-symbols-outlined text-orange">description</span>
                                <span>Output: Dokumen Strategis</span>
                            </div>
                        </div>
                        
                        <hr class="opacity-25 mb-4">
                        
                        <h6 class="fw-bold mb-3">Siap berdiskusi?</h6>
                        <a href="<?= BASE_URL ?>contact" class="btn btn-light w-100 py-3">Hubungi Tim Sekarang</a>
                        <p class="text-center small mt-3 opacity-50 mb-0">Respon dalam 1x24 jam</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
