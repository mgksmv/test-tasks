import { createApp } from 'vue/dist/vue.esm-bundler';
import TasksIndex from '../components/tasks/Index.vue';

document.addEventListener('DOMContentLoaded', () => {
    const vueApp = createApp({});
    vueApp.component('Tasks', TasksIndex);
    vueApp.mount('#app');
});
