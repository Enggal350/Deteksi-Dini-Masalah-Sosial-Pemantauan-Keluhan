const categorySelect = document.getElementById("categorySelect");
const searchInput = document.getElementById("searchInput");
const aduanTable = document.getElementById("aduanTable");
const addForm = document.getElementById("addForm");
const editStatusModal = new bootstrap.Modal(document.getElementById("editStatusModal"));
const editStatusSelect = document.getElementById("editStatusSelect");
const saveStatusBtn = document.getElementById("saveStatusBtn");

let editingRow = null;

// 📦 Data Awal
let aduanData = JSON.parse(localStorage.getItem("aduanData")) || [
  { nama: "Andi", kategori: "Infrastruktur", isi: "Jalan rusak parah.", tanggal: "2025-10-02", status: "Belum Diproses" },
  { nama: "Sinta", kategori: "Kebersihan", isi: "Sampah menumpuk di taman kota.", tanggal: "2025-10-04", status: "Sedang Ditindak" },
  { nama: "Budi", kategori: "Pelayanan Publik", isi: "Pelayanan kelurahan lambat.", tanggal: "2025-10-07", status: "Selesai" }
];

// 🔁 Render Tabel
function renderTable() {
  aduanTable.innerHTML = "";
  let filtered = aduanData.filter((aduan) => {
    const matchCategory =
      categorySelect.value === "all" || aduan.kategori === categorySelect.value;
    const matchSearch = aduan.isi.toLowerCase().includes(searchInput.value.toLowerCase());
    return matchCategory && matchSearch;
  });

  filtered.forEach((aduan, index) => {
    const row = `
      <tr data-index="${index}">
        <td>${index + 1}</td>
        <td>${aduan.nama}</td>
        <td>${aduan.kategori}</td>
        <td>${aduan.isi}</td>
        <td>${aduan.tanggal}</td>
        <td><span class="badge bg-${getStatusColor(aduan.status)}">${aduan.status}</span></td>
        <td><button class="btn btn-sm btn-warning edit-btn"><i class="fa-solid fa-pen"></i></button></td>
      </tr>`;
    aduanTable.insertAdjacentHTML("beforeend", row);
  });
}

function getStatusColor(status) {
  if (status === "Belum Diproses") return "secondary";
  if (status === "Sedang Ditindak") return "info";
  if (status === "Selesai") return "success";
  return "light";
}

// 🔍 Filter
categorySelect.addEventListener("change", renderTable);
searchInput.addEventListener("keyup", renderTable);

// ➕ Tambah Aduan
addForm.addEventListener("submit", (e) => {
  e.preventDefault();
  const newAduan = {
    nama: nama.value,
    kategori: kategori.value,
    isi: isi.value,
    tanggal: new Date().toISOString().split("T")[0],
    status: "Belum Diproses",
  };
  aduanData.push(newAduan);
  localStorage.setItem("aduanData", JSON.stringify(aduanData));
  renderTable();
  addForm.reset();
  bootstrap.Modal.getInstance(document.getElementById("addModal")).hide();
});

// ✏️ Edit Status
aduanTable.addEventListener("click", (e) => {
  if (e.target.closest(".edit-btn")) {
    const row = e.target.closest("tr");
    editingRow = row.dataset.index;
    editStatusSelect.value = aduanData[editingRow].status;
    editStatusModal.show();
  }
});

saveStatusBtn.addEventListener("click", () => {
  const newStatus = editStatusSelect.value;
  aduanData[editingRow].status = newStatus;
  localStorage.setItem("aduanData", JSON.stringify(aduanData));
  renderTable();
  editStatusModal.hide();
});

// 🔁 Initial Render
renderTable();
