<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <button class="btn btn-outline-primary" @click="showDiv = !showDiv">{{btnReservTitle}}</button>
            </div>
         </div>
         <br>
         <div class="show">
            <div v-show="showDiv">
                <!--DIV OCULTO INICIO-->
                <div class="row">
            <div class="col">
                <label for="nombre">Cliente:</label>&nbsp; {{ user.name }}
               </div>
               <div class="row">
            <div class="col">
                <label for="nombre">Fecha Alquiler:</label>&nbsp; {{ reservaForm.fechaAlquiler }}
               </div>
               </div>
        </div>
        <h4>Detalle de la Reserva</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoria</th>
                    <th>Proveedor</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item,index) in reservaForm.Pedido" :key="index">
                <td>{{ item.producto.nombre }}</td>
                <td>{{item.producto.categoria.nombre}}</td>
                <td>{{item.producto.proveedor.nombre}}</td>
                <td>{{item.producto.precio}}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" @click="removeItem(index)">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
                </tr>
                <tr>
                    <td colspan="6" class="text-center"><b>Total a pagar</b></td>
                    <td>{{ total }}</td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center">
                        <button class="btn btn-primary" :disabled="reservaForm.Pedido.length < 1" @click="saveReserva()">Confirmar Compra</button>
                    </td>
                </tr>
            </tbody>
        </table>
    
      <!--DIV OCULTO FIN-->
             </div>
             </div>
            <div class="row">
            <div class="col-sm-4" v-for="(item,index) in productos" key="index">
                <div class="card" style="width: 18rem;">
                <img src="https://picsum.photos/300/300" class="card-img-top" alt="">
                <div class="card-body">
                <h5 class="card-title text-bold">{{item.nombre}}&nbsp;</h5>
                <p class="card-text">proveedor:<b class="text-warning">${{ item.proveedor.nombre }}</b></p>
                <p class="card-text">categoria:<b class="text-warning">${{ item.categoria.nombre }}</b></p>
                <p class="card-text">Precio: <b class="text-warning">${{ item.precio }} &nbsp; Existencias: {{ item.existencias }}</b></p>
                <button class="btn btn-primary"  @click="addToReserva(item)" :disabled="reservaForm.Pedido.length >= 1" >Comprar</button>
                    </div>
                </div>
            </div>          
        </div>
    </div>

  </template>

 <script>
    export default {
        props:['user'],
        data(){
            return{
                productos: [],
                reservaForm:{
                    id:null,
                    fechaAlquiler:new Date(Date.now() - new Date().getTimezoneOffset() * 60000),
                    fechaDevolucion:new Date(Date.now() - new Date().getTimezoneOffset() * 60000),
                    total: new Number("0").toFixed(2),
                    user: null,
                    producto: null,
                    Pedido:[],    
                },
                indexAuto: -1,
                disableButton: false,
                showDiv: false,
                fecha_pedido: new Date().getFullYear() + "-" + 0 + (new Date().getMonth()+1) + "-" + new Date().getDate(),

            }
        },
        computed:{
            btnReservTitle(){
                return this.showDiv == false ? "Ver carrito" : "Ocultar carrito";
            },
        },
       
        methods:{
            async fetchProductos(){
                let me = this;
              await this.axios.get('/productos')
              .then(response =>{
               // console.log(response.data);
               me.productos = response.data;
              })  
            
            },

            removeItem(i){
                this.reservaForm.Pedido.splice(i,1);
            },
            addToReserva(item,index){
                let me = this;
                me.reservaForm.Pedido.push({
                    id:null,
                    producto:item,
                    fecha_pedido: this.fecha_pedido
                })
                me.reservaForm.producto = item;
                me.reservaForm.user = this.user;
                console.log(me.reservaForm);
                console.log(me.reservaForm.Pedido.length);
            },
            async saveReserva(){
                let me = this;
                if(me.reservaForm.Pedido.length > 0)
                {
                    //seteando datos daltantes para la reserva
                    var f = new Date();
                    this.reservaForm.fecha = f.getFullYear() + "-" + 0 + (f.getMonth()+1) + "-" + f.getDate();
                    this.reservaForm.monto = me.reservaForm.producto.precio;
                    console.log(me.reservaForm);
                    await this.axios.post('/pedidos', me.reservaForm)
                    .then(response =>{
                        
                            this.$swal.fire("success", "Su reserva se ha registrado con exito, pronto le llamaremos");
                           this.reservaForm.Pedido = [];
                            me.showDiv = false;
                         
                    }).catch(errors =>{
                        console.log(errors);
                    })
                } else{
                    this.$swal.fire("warning", "Complete los datos de la reserva");
                }
            },

    },


        mounted() {
            var f = new Date();
            this.fetchProductos();
            console.log(this.reservaForm);
            //this.fetchMarcas();
            //this.fetchModelos();
            console.log(this.user);
        }
    }
</script>

