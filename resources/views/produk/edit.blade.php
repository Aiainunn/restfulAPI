<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Produk</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px">
        <div class="card-body p-4">

            <h2 class="mb-4">Edit Produk</h2>

            <!-- Pesan status dan error -->
            <div id="pesan" class="mb-3" role="alert">
                Memuat data produk...
            </div>

            <form id="formEditProduk" class="d-none">

                <div class="mb-3">
                    <label for="nama" class="form-label">
                        Nama Produk
                    </label>
                    <input
                        type="text"
                        id="nama"
                        class="form-control"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">
                        Deskripsi
                    </label>
                    <textarea
                        id="deskripsi"
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">
                        Harga
                    </label>
                    <input
                        type="number"
                        id="harga"
                        class="form-control"
                        min="0"
                        step="1"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="stok" class="form-label">
                        Stok
                    </label>
                    <input
                        type="number"
                        id="stok"
                        class="form-control"
                        min="0"
                        step="1"
                        required
                    >
                </div>

                <button
                    type="submit"
                    id="tombolSimpan"
                    class="btn btn-primary"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('produk.index') }}"
                    class="btn btn-secondary"
                >
                    Batal
                </a>
            </form>

            <a
                id="tombolKembali"
                href="{{ route('produk.index') }}"
                class="btn btn-secondary d-none"
            >
                Kembali ke Daftar Produk
            </a>

        </div>
    </div>
</div>

<script>
    const ID_PRODUK = @json($id);
    const API = '/api/produk';

    const form = document.getElementById('formEditProduk');
    const pesan = document.getElementById('pesan');
    const tombolSimpan = document.getElementById('tombolSimpan');
    const tombolKembali = document.getElementById('tombolKembali');

    // Menampilkan pesan pada halaman
    function tampilkanPesan(teks, jenis = 'info') {
        pesan.textContent = teks;
        pesan.className = `alert alert-${jenis} mb-3`;
    }

    // Mengambil data produk berdasarkan ID
    async function ambilProduk() {
        try {
            const response = await fetch(`${API}/${ID_PRODUK}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const hasil = await response.json();

            if (!response.ok) {
                throw new Error(
                    hasil.message || 'Gagal mengambil data produk.'
                );
            }

            // Resource Laravel dapat mengembalikan data di dalam properti data
            const produk = hasil.data ?? hasil;

            if (!produk || produk.id == null) {
                throw new Error('Data produk tidak ditemukan.');
            }

            document.getElementById('nama').value = produk.nama ?? '';
            document.getElementById('deskripsi').value =
                produk.deskripsi ?? '';
            document.getElementById('harga').value = produk.harga ?? 0;
            document.getElementById('stok').value = produk.stok ?? 0;

            form.classList.remove('d-none');
            pesan.classList.add('d-none');

        } catch (error) {
            tampilkanPesan(error.message, 'danger');
            tombolKembali.classList.remove('d-none');
        }
    }

    // Menyimpan perubahan tanpa mengirim formulir secara langsung ke API
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        tombolSimpan.disabled = true;
        tombolSimpan.textContent = 'Menyimpan...';

        pesan.classList.remove('d-none');
        tampilkanPesan('Sedang menyimpan perubahan...', 'info');

        const data = {
            nama: document.getElementById('nama').value.trim(),
            deskripsi: document.getElementById('deskripsi').value.trim(),
            harga: Number(document.getElementById('harga').value),
            stok: Number(document.getElementById('stok').value)
        };

        try {
            const response = await fetch(`${API}/${ID_PRODUK}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const hasil = await response.json();

            if (!response.ok) {
                if (hasil.errors) {
                    const daftarError = Object.values(hasil.errors)
                        .flat()
                        .join(' ');

                    throw new Error(daftarError);
                }

                throw new Error(
                    hasil.message || 'Gagal menyimpan perubahan produk.'
                );
            }

            tampilkanPesan(
                'Produk berhasil diperbarui. Mengalihkan halaman...',
                'success'
            );

            // Kembali ke halaman web, bukan halaman JSON API
            window.location.href = @json(route('produk.index'));

        } catch (error) {
            tampilkanPesan(error.message, 'danger');

            tombolSimpan.disabled = false;
            tombolSimpan.textContent = 'Simpan Perubahan';
        }
    });

    // Jalankan saat halaman dibuka
    ambilProduk();
</script>

</body>
</html>