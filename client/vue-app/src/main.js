import './assets/main.css'
import "@mdi/font/css/materialdesignicons.css";
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from '../src/App.vue'
import route from './router'
import VeeValidatePlugin from "./includes/validation";
import { rules } from './includes/customValidationRules.js';

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'


const vuetify = createVuetify({
  components,
  directives,
})

const pinia = createPinia()
const app = createApp(App)

app.config.globalProperties.$rules = rules
app.use(vuetify)
app.use(pinia)
app.use(route)
app.use(VeeValidatePlugin)



//global snackbar component
import Snackbar from "./components/base/SnackbarProvider.vue"
app.component('Snackbar', Snackbar)


app.mount('#app')