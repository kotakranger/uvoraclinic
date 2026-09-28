// Hanya dibutuhkan kalau Anda menambah class Tailwind baru dan ingin
// meng-generate ulang assets/css/style.css. Lihat README.md.
module.exports = {
  content: ["./index.php", "./partials/**/*.php", "./includes/**/*.php", "./assets/js/main.js"],
  theme: {
    extend: {
      colors: {
        border: "hsl(var(--border))",
      },
    },
  },
  plugins: [],
};
