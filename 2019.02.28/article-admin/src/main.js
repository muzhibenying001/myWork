// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'

/** 引入自定义的axios */
import Myaxios from './assets/axios';
Vue.use(Myaxios);

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
Vue.use(ElementUI);
import './assets/css/app.css';
import './assets/font/iconfont.css'
import qs from 'qs';

/** 引入富文本编辑器 */
import vueQuillEditor from "vue-quill-editor"
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
Vue.use(vueQuillEditor);

import router from './router'



Vue.prototype.$qs = qs;

Vue.config.productionTip = false


/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
