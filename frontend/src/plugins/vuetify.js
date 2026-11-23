import '@fortawesome/fontawesome-free/css/all.css'
import '@mdi/font/css/materialdesignicons.css'
import Vue from 'vue'
import Vuetify from 'vuetify/lib/framework'
import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify)

const storedTheme = localStorage.getItem('appTheme')

export default new Vuetify({
  icons: {
    iconfont: 'mdi',
  },
  theme: {
    dark: storedTheme === 'dark',
    themes: {
      light: {
        primary: colors.teal.lighten1,
        secondary: colors.teal.lighten1,
        navbar: colors.teal.lighten2,
        font1: colors.white,
        accent: colors.shades.black,
        error: colors.red.accent3,
      },
      dark: {
        primary: colors.grey.darken3,
        secondary: colors.blue.darken2,
        font2: colors.white,
        biru: colors.blue,
      },
    },
  },
})
