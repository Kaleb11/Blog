require('./bootstrap');
import Vue from 'vue'
import router from './router'
import store from './store'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import Editor from 'vue-editor-js/src/index'
import locale from 'view-design/dist/locale/en-US'
import DisableAutocomplete from 'vue-disable-autocomplete';

Vue.use(DisableAutocomplete);
Vue.use(Editor)

Vue.use(ViewUI, { locale: locale });
Vue.use(Vuetify);
import common from './common'
import jsonToHtml from './jsonToHtml'
// new Vue({ vuetify }).$mount('#app')
Vue.mixin(common);
Vue.mixin(jsonToHtml)
Vue.component('mainapp', require('./components/mainapp.vue').default)
const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify: new Vuetify(),
    transpileDependencies: ['vuetify']
})