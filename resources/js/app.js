/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';


//importaciones de axios y vue-axios
import axios from 'axios'
import VueAxios from 'vue-axios';

//importaciones de sweeatalert
import VueSweetalert2 from 'vue-sweetalert2'; 
import 'sweetalert2/dist/sweetalert2.min.css';



/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
import CategoriaComponent from './components/CategoriaComponent.vue';
import ProductoComponent from './components/ProductoComponent.vue';
import PedidoComponent from './components/PedidoComponent.vue';
import ProveedorComponent from './components/ProveedorComponent.vue'; 
import DetallesComponent from './components/DetallesComponent.vue'; 
app.component('detalles-component',DetallesComponent);
app.component('pro-component', ProveedorComponent);
app.component('example-component', ExampleComponent);
app.component('categorias-component', CategoriaComponent);
app.component('producto-component', ProductoComponent);
app.component('pedido-component', PedidoComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */


//definiendo variables globales
app.config.globalProperties.axios = axios;
app.config.globalProperties.msj = "Hola";

app.use(VueSweetalert2);
app.use(VueAxios,axios)
app.mount('#app');

