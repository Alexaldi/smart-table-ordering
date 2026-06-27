// Quantity
let qty = 1;
document.getElementById('btnMinus').addEventListener('click', () => {
  if (qty > 1) {
    qty--;
    document.getElementById('qtyValue').textContent = qty;
  }
});

document.getElementById('btnPlus').addEventListener('click', () => {
  qty++;
  document.getElementById('qtyValue').textContent = qty;
});

const sentinel = document.getElementById('tabelSentinel');
const cardTabel = document.getElementById('cardTabel');

const observer = new IntersectionObserver(
  ([entry]) => {
    // kalau sentinel udah nggak kelihatan (scroll lewat), berarti card-tabel sedang stuck
    cardTabel.classList.toggle('is-stuck', !entry.isIntersecting);
  },
  { threshold: 0 }
);

observer.observe(sentinel);