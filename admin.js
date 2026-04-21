// INISIALISASI & STATE
const STORAGE_KEY = 'alia_inventory_data';

// Data default
const initialData = [
    { id: "1", kode: "CK-01", nama: "Nastar Klasik", kategori: "Cookies", stok: 15, harga: 75000, tanggal: "2026-04-10" },
    { id: "2", kode: "CK-02", nama: "Sea Salt Choco", kategori: "Cookies", stok: 4, harga: 85000, tanggal: "2026-04-12" },
    { id: "3", kode: "HM-01", nama: "Twin Delight", kategori: "Hampers", stok: 10, harga: 150000, tanggal: "2026-04-15" }
];

// Cek apakah Local Storage kosong. Jika panjang datanya 0, paksa pakai initialData
let savedData = JSON.parse(localStorage.getItem(STORAGE_KEY));
let inventoryData = (savedData && savedData.length > 0) ? savedData : initialData;

let myChart = null; 

// DOM Elements
const form = document.getElementById('productForm');
const tableBody = document.getElementById('productTableBody');
const searchInput = document.getElementById('searchInput');
const filterCategory = document.getElementById('filterCategory');
const errorBox = document.getElementById('errorBox');

// TOAST DOM
const toastNotification = document.getElementById('toastNotification');
const toastMessage = document.getElementById('toastMessage');
const toastIcon = document.getElementById('toastIcon');

// FUNGSI UTAMA 

const saveData = () => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(inventoryData));
};

// Fungsi Menampilkan Notifikasi
const showToast = (message, type = 'success') => {
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toastNotification.className = "fixed top-28 right-8 transform transition-transform duration-500 z-[100] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-xl font-bold text-sm bg-[#FDF5E6] text-[#5D4037] border-l-4 border-[#8B4513] translate-x-0";
        toastIcon.textContent = "✅";
    } else if (type === 'delete') {
        toastNotification.className = "fixed top-28 right-8 transform transition-transform duration-500 z-[100] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-xl font-bold text-sm bg-red-50 text-red-800 border-l-4 border-red-600 translate-x-0";
        toastIcon.textContent = "🗑️";
    }

    setTimeout(() => {
        toastNotification.classList.remove('translate-x-0');
        toastNotification.classList.add('translate-x-[150%]');
    }, 3000);
};

// Render Tabel
const renderTable = (dataArray = inventoryData) => {
    tableBody.innerHTML = ''; 

    dataArray.forEach((item) => {
        const tr = document.createElement('tr');
        tr.className = `zebra-row border-b border-amber-50 hover:bg-[#F3E5D8] transition-colors`;
        tr.innerHTML = `
            <td class="p-5">
                <div class="font-bold text-[#5D4037]">${item.nama}</div>
                <div class="text-[10px] text-gray-500">${item.kode} | Masuk: ${item.tanggal}</div>
            </td>
            <td class="p-5"><span class="px-3 py-1 bg-amber-50 rounded-full text-xs font-bold">${item.kategori}</span></td>
            <td class="p-5 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-bold ${item.stok < 5 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">
                    ${item.stok}
                </span>
            </td>
            <td class="p-5 font-bold">Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
            <td class="p-5 text-center">
                <button class="btn-edit px-3 py-1 bg-amber-100 text-amber-800 rounded hover:bg-amber-200 text-xs font-bold" data-id="${item.id}">Edit</button>
                <button class="btn-delete px-3 py-1 bg-red-100 text-red-800 rounded hover:bg-red-200 text-xs font-bold" data-id="${item.id}">Hapus</button>
            </td>
        `;
        tableBody.appendChild(tr);
    });

    updateStatsAndChart(dataArray);
};

// Update Statistik & Grafik
const updateStatsAndChart = (dataArray) => {
    document.getElementById('statTotalItem').textContent = dataArray.length;
    const totalValue = dataArray.reduce((acc, curr) => acc + (Number(curr.harga) * Number(curr.stok)), 0);
    document.getElementById('statTotalValue').textContent = `Rp ${totalValue.toLocaleString('id-ID')}`;
    document.getElementById('statLowStock').textContent = dataArray.filter(item => Number(item.stok) < 5).length;

    const cookiesStock = dataArray.filter(i => i.kategori === 'Cookies').reduce((acc, curr) => acc + Number(curr.stok), 0);
    const hampersStock = dataArray.filter(i => i.kategori === 'Hampers').reduce((acc, curr) => acc + Number(curr.stok), 0);

    renderChart(cookiesStock, hampersStock);
};

// Logika Chart.js
const renderChart = (cookiesCount, hampersCount) => {
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    if (myChart) myChart.destroy();

    myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Stok Cookies', 'Stok Hampers'],
            datasets: [{
                data: [cookiesCount, hampersCount],
                backgroundColor: ['#8B4513', '#D2B48C'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Komposisi Stok Gudang', font: { family: 'Playfair Display', size: 18 } }
            }
        }
    });
};

// Validasi Custom 
const validateInput = (kode, nama, kategori, stok, harga, tanggal) => {
    if (!kode || !nama || !kategori || !tanggal) return "Semua kolom teks dan tanggal wajib diisi.";
    if (stok === "" || isNaN(stok)) return "Jumlah stok harus diisi dengan angka.";
    if (Number(stok) < 0) return "Stok tidak boleh bernilai negatif.";
    if (harga === "" || isNaN(harga)) return "Harga harus diisi dengan angka.";
    if (Number(harga) < 100) return "Harga barang minimal Rp 100.";
    return null; 
};

// EVENT LISTENERS

// Submit Form (Tambah / Edit)
form.addEventListener('submit', (e) => {
    e.preventDefault();
    errorBox.classList.add('hidden'); 

    const editId = document.getElementById('editIndex').value;
    const kode = document.getElementById('kodeBarang').value.trim();
    const nama = document.getElementById('namaBarang').value.trim();
    const kategori = document.getElementById('kategoriBarang').value;
    const stok = document.getElementById('stokBarang').value;
    const harga = document.getElementById('hargaBarang').value;
    const tanggal = document.getElementById('tanggalMasuk').value;

    const errorMessage = validateInput(kode, nama, kategori, stok, harga, tanggal);
    if (errorMessage) {
        errorBox.textContent = "⚠ " + errorMessage;
        errorBox.classList.remove('hidden');
        return; 
    }

    const newData = { id: editId || Date.now().toString(), kode, nama, kategori, stok, harga, tanggal };

    if (editId) {
        const index = inventoryData.findIndex(item => item.id === editId);
        if (index !== -1) inventoryData[index] = newData;
        document.getElementById('formTitle').textContent = "Input Barang";
        document.getElementById('btnSubmit').textContent = "Simpan Data";
        showToast("Data barang berhasil diperbarui!", "success");
    } else {
        inventoryData.push(newData);
        showToast("Barang baru berhasil ditambahkan!", "success");
    }

    saveData();
    renderTable();
    form.reset();
    document.getElementById('editIndex').value = '';
});

// Edit & Delete
tableBody.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn-delete')) {
        if (confirm("Hapus barang ini secara permanen?")) {
            inventoryData = inventoryData.filter(item => item.id !== e.target.getAttribute('data-id'));
            saveData();
            searchInput.dispatchEvent(new Event('input')); 
            showToast("Data barang berhasil dihapus!", "delete");
        }
    }

    if (e.target.classList.contains('btn-edit')) {
        const item = inventoryData.find(item => item.id === e.target.getAttribute('data-id'));
        if (item) {
            document.getElementById('editIndex').value = item.id;
            document.getElementById('kodeBarang').value = item.kode;
            document.getElementById('namaBarang').value = item.nama;
            document.getElementById('kategoriBarang').value = item.kategori;
            document.getElementById('stokBarang').value = item.stok;
            document.getElementById('hargaBarang').value = item.harga;
            document.getElementById('tanggalMasuk').value = item.tanggal;

            document.getElementById('formTitle').textContent = "Edit Data Barang";
            document.getElementById('btnSubmit').textContent = "Update Data";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
});

// Search & Filter
searchInput.addEventListener('input', (e) => {
    const keyword = e.target.value.toLowerCase();
    const category = filterCategory.value;

    const filtered = inventoryData.filter(item => {
        const matchesName = item.nama.toLowerCase().includes(keyword) || item.kode.toLowerCase().includes(keyword);
        const matchesCat = category === "All" || item.kategori === category;
        return matchesName && matchesCat;
    });
    renderTable(filtered);
});

filterCategory.addEventListener('change', () => searchInput.dispatchEvent(new Event('input')));

// Inisialisasi awal
renderTable();