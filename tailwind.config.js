/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    'index.html',
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    container:{
      center: true,
      padding: '16px',
    },
    extend: {
      colors: {
        primary: '#fffffe',
        second: '#f9bc60',
        paragraph: '#abd1c6',
        buttontext: '#001e1d',
        aboutcolor: '#0f3433',
        homecolor : '#004643',
        aboutbg : '#abd1c6',
        main: '#e8e4e6',
      },
      screens:{
        '2xl': '1320px',
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

