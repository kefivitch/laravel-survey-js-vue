require('./bootstrap');

// Import modules...
import Vue from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue'

import { InertiaProgress } from '@inertiajs/progress'

const el = document.getElementById('app');

new Vue({
    el: '#app',
  })


createInertiaApp({
    id: "app",
    resolve: name => import(`./Pages/${name}`),
    setup({ el, App, props }) {
      new Vue({
        render: h => h(App, props),
      }).$mount(el)
    },
  })

Vue.prototype.route = route

InertiaProgress.init({ color: '#4B5563' });
