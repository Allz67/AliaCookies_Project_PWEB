const formatRupiah = (angka) => `Rp ${Number(angka).toLocaleString('id-ID')}`;

const formatDate = (dateString) => {
    const options = { day: 'numeric', month: 'short', year: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

// DATA SIMULASI RIWAYAT PESANAN
const orderHistoryData = [
    { id: "ORD-001", tanggal: "2026-03-01", produk: "Nastar Klasik", kategori: "Cookies", status: "SELESAI", total: 100000 },
    { id: "ORD-002", tanggal: "2026-03-03", produk: "Twin Delight Hampers", kategori: "Hampers", status: "SELESAI", total: 250000 },
    { id: "ORD-003", tanggal: "2026-04-10", produk: "Sea Salt Choco", kategori: "Cookies", status: "SELESAI", total: 85000 },
    { id: "ORD-004", tanggal: "2026-04-18", produk: "Sweet Single Hampers", kategori: "Hampers", status: "SELESAI", total: 120000 },
    { id: "ORD-005", tanggal: "2026-01-15", produk: "Lidah Kucing", kategori: "Cookies", status: "SELESAI", total: 65000 }
];

// SELEKSI ELEMEN DOM
const historyTableBody = document.getElementById('historyTableBody');
const noDataMessage = document.getElementById('noDataMessage');
const searchHistory = document.getElementById('searchHistory');
const filterCookies = document.getElementById('filterCookies');
const filterHampers = document.getElementById('filterHampers');
const radiosWaktu = document.querySelectorAll('.filter-radio');

// RENDER & FILTER
const renderHistoryTable = () => {
    // 1. Ambil nilai dari input user
    const keyword = searchHistory.value.toLowerCase();
    const showCookies = filterCookies.checked;
    const showHampers = filterHampers.checked;
    const showAllCategories = !showCookies && !showHampers;

    // Ambil nilai radio button waktu
    let timeFilter = "all";
    radiosWaktu.forEach(radio => { 
        if (radio.checked) timeFilter = radio.value; 
    });

    // 2. Proses Filter Array Data
    const filteredData = orderHistoryData.filter(item => {
        // Cek pencarian nama
        const matchName = item.produk.toLowerCase().includes(keyword);
        
        // Cek kategori
        const matchCategory = showAllCategories || 
                            (showCookies && item.kategori === "Cookies") || 
                            (showHampers && item.kategori === "Hampers");
        
        // Cek filter waktu
        let matchTime = true;
        if (timeFilter === "30") {
            const today = new Date();
            const orderDate = new Date(item.tanggal);
            const diffDays = Math.ceil(Math.abs(today - orderDate) / (1000 * 60 * 60 * 24));
            
            if (diffDays > 30) matchTime = false;
        }

        return matchName && matchCategory && matchTime;
    });

    // 3. Render ke dalam HTML
    historyTableBody.innerHTML = ''; 

    if (filteredData.length === 0) {
        noDataMessage.classList.remove('hidden');
        noDataMessage.classList.add('flex');
    } else {
        noDataMessage.classList.add('hidden');
        noDataMessage.classList.remove('flex');

        filteredData.forEach(item => {
            const statusBadge = "bg-green-100 text-green-700";

            const tr = document.createElement('tr');
            tr.className = "zebra-row border-b border-amber-50 cursor-pointer hover:bg-[#F3E5D8] transition-colors";
            
            tr.innerHTML = `
                <td class="p-6 text-gray-500">${formatDate(item.tanggal)}</td>
                <td class="p-6">
                    <div class="font-bold text-warm-brown">${item.produk}</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] uppercase opacity-50 font-bold">${item.id}</span>
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-md text-[8px] font-bold uppercase tracking-widest">${item.kategori}</span>
                    </div>
                </td>
                <td class="p-6 text-center">
                    <span class="${statusBadge} px-3 py-1 rounded-full text-[10px] font-bold tracking-widest">${item.status}</span>
                </td>
                <td class="p-6 text-right font-bold text-warm-brown">${formatRupiah(item.total)}</td>
            `;
            historyTableBody.appendChild(tr);
        });
    }
};

// EVENT LISTENERS (Trigger otomatis saat user mengetik/klik)
searchHistory.addEventListener('input', renderHistoryTable);
filterCookies.addEventListener('change', renderHistoryTable);
filterHampers.addEventListener('change', renderHistoryTable);
radiosWaktu.forEach(radio => radio.addEventListener('change', renderHistoryTable));

renderHistoryTable();