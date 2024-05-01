import './bootstrap';
// Import custom CSS
import '../sass/app.scss'


//import Vue from 'vue'
//import VueRouter from 'vue-router'
import { createApp } from 'vue';
import { createMemoryHistory, createRouter } from 'vue-router'

import App from './components/App.vue';
import Home from './components/Home.vue';
import Desks from './components/desks/Desks.vue';
import ShowDesk from './components/desks/ShowDesk.vue';

//VueRouter.use(VueRouter)

const routes = [
    { path: '/spa', component: Home, name: 'home' },
    { path: '/spa/desks', component: Desks, name: 'desks' },
    { path: '/spa/desks/:deskId', component: ShowDesk, name: 'showDesk', props: true },
]

const router = createRouter({
    history: createMemoryHistory(),
    routes,
})

 // const app = new Vue({
 //     el: '#app'
 // })

createApp(App).use(router).mount('#app');
