import { createWebHistory, createRouter } from 'vue-router'
import Home from '/@/views/Home.vue'
import MenuView from '/@/views/MenuView.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
]



const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default {
    install(app: any) {
        router.install(app)

       
    },
}
