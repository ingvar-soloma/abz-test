import './bootstrap';

import { createApp } from 'vue'
import vuetifyConfig from './plugins/vuetify'
import router from './plugins/router'
import { createPinia } from 'pinia';

// Components
import App from './App.vue'


let app = createApp(App);

const pinia = createPinia();

app.use(vuetifyConfig)
    .use(router)
    .use(pinia);

app.mount('#app')
