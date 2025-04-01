import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import { createVuetify } from "vuetify";
// Vuetify
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

export default createVuetify({
    ssr: true,
    components,
    directives,
})
