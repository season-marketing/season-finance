module.exports = {
  purge: {
    mode: 'all',
    content: [
      './app/Resources/js/**/*.js',
      './app/Resources/css/**/*.css',
      './app/Resources/views/**/*.blade.php'
    ]
  },
  darkMode: false,
  theme: {
    fontFamily: {
      hero: ['"Merriweather"', 'serif'],
      body: ['"Poppins"', 'sans-serif'],
    },
    container: {
      center: true,
      padding: '1rem',
      screens: {
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1300px',
        '2xl': '1336px'
     }
    },
    maxWidth: {
      logo: '15rem',
      'container-sm': '1100px',
      'container-xs': '850px'
    }
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
