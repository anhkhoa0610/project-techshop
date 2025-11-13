/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
    "./vendor/livewire/**/*.blade.php",
    "./vendor/power-components/livewire-power-grid/resources/views/**/*.php",
    "./vendor/power-components/livewire-power-grid/src/Themes/Tailwind.php"
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}