/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './admin/**/*.{html,js,php}', // تحديد ملفات لوحة التحكم
  ],
  theme: {
    extend: {
      // تخصيص خصائص Tailwind مثل الألوان والخطوط
      colors: {
        dashboardBg: '#f5f5f5', // لون خلفية مخصص للوحة التحكم
        pgprimary: '#240b36', // لون أزرق مخصص
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      cardCustom: {
        'position': 'relative',
        'margin-bottom': '1.5rem',
        'border-radius': '0.75rem',
        'border-width': '1px',
        '--tw-border-opacity': '1',
        'border-color': 'rgb(231 234 238 / var(--tw-border-opacity))',
        '--tw-bg-opacity': '1',
        'background-color': 'rgb(255 255 255 / var(--tw-bg-opacity))',
      },
      
    },
  },
  plugins: [
   
  ],
};


