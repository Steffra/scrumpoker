const plugin = require("tailwindcss/plugin");

const Myclass = plugin(function ({ addUtilities }) {
    addUtilities({
      ".my-rotate-y-180": {
        transform: "rotateY(180deg)",
      },
      ".preserve-3d": {
        transformStyle: "preserve-3d",
      },
      ".perspective": {
        perspective: "1000px",
      },
      ".backface-hidden": {
        backfaceVisibility: "hidden",
      },
    });
  });

module.exports = {
    mode: 'jit',
    content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
    theme: {
        fontFamily: {
            poppins: ['"Poppins"', 'poppins'],
        },
        extend: {
            colors: {
                'light-green': '#c5d5cb',
                'darker-green': '#82a28e',
                'even-darker-green': '#64806e',
                'dark-grey': '#9fa8a3',
                'light-grey': '#e3e0cf',
            },

            width: {
                224: '30rem',
            },
        },
    },
    plugins: [require('@tailwindcss/custom-forms'), Myclass],
}
