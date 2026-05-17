document.addEventListener("DOMContentLoaded", function () {
  const btnBar = document.getElementById("btn-bar");
  const btnTimes = document.getElementById("btn-times");
  const popMenu = document.getElementById("pop-menu");

  // Fungsi saat tombol Bar diklik (cek eksistensi elemen terlebih dahulu)
  if (btnBar && btnTimes && popMenu) {
    btnBar.addEventListener("click", function () {
      btnBar.classList.add("d-none"); // Sembunyikan Bar
      btnTimes.classList.remove("d-none"); // Munculkan Times
      popMenu.classList.remove("d-none"); // Munculkan Menu
    });

    // Fungsi saat tombol Times diklik
    btnTimes.addEventListener("click", function () {
      btnTimes.classList.add("d-none"); // Sembunyikan Times
      btnBar.classList.remove("d-none"); // Munculkan Bar
      popMenu.classList.add("d-none"); // Sembunyikan Menu
    });
  }
});

function addData() {
  const secAdd = document.querySelector(".sec-add");
  secAdd.classList.add("show");
  secAdd.scrollIntoView({ behavior: "smooth", block: "start" });
}

function closeForm() {
  const secAdd = document.querySelector(".sec-add");
  secAdd.classList.remove("show");
  // Redirect to remove URL parameters
  window.location.href = window.location.pathname;
}
