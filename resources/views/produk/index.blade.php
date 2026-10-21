<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Daftar Produk</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Daftar Produk</h2>
            <p class="text-muted mb-0">
                Data produk ditampilkan melalui RESTful API menggunakan JSON.
            </p>
        </div>

        <a
            href="{{ route('produk.create') }}"
            class="btn btn-primary"
        >
            + Tambah Produk
        </a>
    </div>

    <!-- Pesan status -->
    <div id="pesan" role="alert"></div>

    <!-- Tabel produk -->
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="tabelProduk">
                    <tr>
                        <td colspan="6" class="text-center">
                            Memuat data produk...
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

</div>

<script>
    // Endpoint API tanpa prefix v1
    const API = '/api/produk';

    const tabel = document.getElementById('tabelProduk');
    const pesan = document.getElementById('pesan');

    // Request API dan penanganan respons JSON
    async function requestJSON(url, options = {}) {
        const response = await fetch(url, {
            ...options,
            headers: {
                Accept: 'application/json',
                ...(options.body ? {
                    'Content-Type': 'application/json'
                } : {}),
                ...options.headers
            }
        });

        const hasil = response.status === 204
            ? null
            : await response.json();

        if (!response.ok) {
            throw new Error(
                hasil?.message || 'Permintaan API gagal.'
            );
        }

        return hasil;
    }

    // Menampilkan pesan
    function tampilkanPesan(teks, tipe = 'success') {
        pesan.replaceChildren();

        const alert = document.createElement('div');
        alert.className = `alert alert-${tipe}`;
        alert.textContent = teks;

        pesan.appendChild(alert);
    }

    // Format harga menjadi Rupiah
    function rupiah(nilai) {
        return 'Rp ' + Number(nilai ?? 0).toLocaleString('id-ID');
    }

    // Membuat sel tabel dengan teks aman
    function buatSel(teks) {
        const td = document.createElement('td');
        td.textContent = teks;
        return td;
    }

    // GET /api/produk
    async function tampilkanProduk() {
        tabel.replaceChildren();

        const barisMemuat = document.createElement('tr');
        const selMemuat = buatSel('Memuat data produk...');
        selMemuat.colSpan = 6;
        selMemuat.className = 'text-center';

        barisMemuat.appendChild(selMemuat);
        tabel.appendChild(barisMemuat);

        try {
            const hasil = await requestJSON(API);

            // Mendukung respons Laravel Resource Collection
            const daftar = Array.isArray(hasil)
                ? hasil
                : (hasil?.data ?? []);

            tabel.replaceChildren();

            if (!Array.isArray(daftar) || daftar.length === 0) {
                const tr = document.createElement('tr');
                const td = buatSel('Belum ada produk.');
                td.colSpan = 6;
                td.className = 'text-center text-muted';

                tr.appendChild(td);
                tabel.appendChild(tr);
                return;
            }

            daftar.forEach((produk, index) => {
                const tr = document.createElement('tr');

                tr.append(
                    buatSel(String(index + 1)),
                    buatSel(produk.nama ?? '-'),
                    buatSel(produk.deskripsi || '-'),
                    buatSel(rupiah(produk.harga)),
                    buatSel(String(produk.stok ?? 0))
                );

                const aksi = document.createElement('td');

                // Tombol detail
                const detail = document.createElement('a');
                detail.href = `/produk/${produk.id}`;
                detail.className = 'btn btn-info btn-sm me-1 mb-1';
                detail.textContent = 'Detail';

                // Tombol edit
                const edit = document.createElement('a');
                edit.href = `/produk/${produk.id}/edit`;
                edit.className = 'btn btn-warning btn-sm me-1 mb-1';
                edit.textContent = 'Edit';

                // Tombol hapus
                const hapus = document.createElement('button');
                hapus.type = 'button';
                hapus.className = 'btn btn-danger btn-sm mb-1';
                hapus.textContent = 'Hapus';

                hapus.addEventListener('click', async () => {
                    await hapusProduk(produk.id, hapus);
                });

                aksi.append(detail, edit, hapus);
                tr.appendChild(aksi);
                tabel.appendChild(tr);
            });

        } catch (error) {
            tabel.replaceChildren();

            const tr = document.createElement('tr');
            const td = buatSel('Gagal memuat data produk.');
            td.colSpan = 6;
            td.className = 'text-center text-danger';

            tr.appendChild(td);
            tabel.appendChild(tr);

            tampilkanPesan(error.message, 'danger');
        }
    }

    // DELETE /api/produk/{id}
    async function hapusProduk(id, tombol) {
        const yakin = confirm(
            'Yakin ingin menghapus produk ini?'
        );

        if (!yakin) return;

        tombol.disabled = true;
        tombol.textContent = 'Menghapus...';

        try {
            const hasil = await requestJSON(`${API}/${id}`, {
                method: 'DELETE'
            });

            tampilkanPesan(
                hasil?.message || 'Produk berhasil dihapus.',
                'success'
            );

            // Muat ulang tabel tanpa membuka halaman API
            await tampilkanProduk();

        } catch (error) {
            tampilkanPesan(error.message, 'danger');

            tombol.disabled = false;
            tombol.textContent = 'Hapus';
        }
    }

    // Jalankan saat halaman dibuka
    tampilkanProduk();
</script>

</body>
</html>