<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-18">

                                <h5 class="float-start">
                                     <br>  Listado de reservaciones realizadas
                                </h5>
                            </div>
                           
                        </div> 
                    </div>

                    <div class="card-body">
                        <table class="table bordered">
                            <thead>
                                <tr>
                                    <th scope="col">cantidad</th>
                                     <th scope="col">fecha del pedido </th>
                                     <th scope="col">usuario</th>
                                     
                                  
                             
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in detalle_reser" :key="item.id">
                                    <td>{{ item.cantidad }}</td>
                                 <td>{{ item.fecha_pedido}}</td>
                                 <td>{{ item.pedido.user_id}}</td>
                                 
                                
                               
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="autorediModal" tabindex="-1" aria-labelledby="autorediModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="autorediModalLabel">{{ formTitle }}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     <!-- definiendo el cuerpo del modal -->
     <div class="row">
        <div class="form-group col-12">
            <label for="tipo">Autor</label>
            <input type="text" class="form-control" v-model="detalleres.fecha_dev">
            <span class="text-danger" v-show="detallesErrors.fecha_dev">Nombre es requerido</span>
            <label for="tipo">Editorial</label>
             <input type="text" class="form-control" v-model="detalleres.dias_alquiler">
           
            <span class="text-danger" v-show="detallesErrors.dias_alquiler">Nombre es requerido</span>
        </div>
     </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="saveOrUpdate()">{{btnTitle}}</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            detalle_reser: [],
            detalleres:{
                id: null,
                fecha_dev:null,
                dias_alquiler:null,
                observacion:null,
                habitacion_id: null,
                clave_habitacion:null,
                reservacion_id: null,
                
            },
            editeDetalles: -1,
            detallesErrors:{
                fecha_dev:false,
                dias_alquiler:false,
                observacion:false,
                habitacion_id: false,
                reservacion_id: false,
            },
            habitaciones:[
                
            ],
            reservaciones:[

            ]
            
        }
    },
   computed:{
        formTitle(){
            return this.detalleres.id==null ? "Nuevo registro":"Editar registro";
        },
        btnTitle(){
            return this.detalleres.id==null?"Guardar":"Actualizar ";
        }
    },
    methods: {
        async fetchAutoresEdi() {
            let me = this;
            await this.axios.get("/det")
            .then((response) => {
                console.log(response.data);
                me.detalle_reser = response.data;
            });
        },
        async fetchAutores() {
            let me = this;
            await this.axios.get("/habicacion")
            .then((response) => {
                console.log(response.data);
                me.habitaciones= response.data;
            });
        },
         async fetchEditoriales() {
            let me = this;
            await this.axios.get("/reser")
            .then((response) => {
                console.log(response.data);
                me.reservaciones = response.data;
            });
        },
        showDialog(){
            this.autoredi ={
                 id: null,
                  autor_id: null,
                editorial_id: null,
                autor:null,
                editorial:null
            },
            this.autorediErrors ={
                autor_id:false,
                editorial_id:false
            }
            $('#autorediModal').modal('show');
        },
         showDialogEditar(autoredi){
            let me = this;
            $('#autorediModal').modal('show');
            me.editeAutoredi = me.autores_edi.indexOf(autoredi);
            me.autoredi = Object.assign({},autoredi)
        },
        hideDialog(){
            let me = this;
            setTimeout(()=>{
                me.autoredi={
                   id: null,
                      autor_id: null,
                editorial_id: null,
                autor:null,
                editorial:null
                };
            },300)
             $('#autorediModal').modal('hide');
        },
        async saveOrUpdate(){
            let me = this;
            me.autoredi.autor_id == '' ? me.autorediErrors.autor_id = true : me.autorediErrors.autor_id = false;
            me.autoredi.editorial_id == '' ? me.autorediErrors.editorial_id = true : me.autorediErrors.editorial_id = false;
            if(me.autoredi.autor_id){ 

                let accion = me.autoredi.id == null ? "add" : "upd";
                me.autoredi.autor = {
                    "id": me.autoredi.autor_id,
                };
                me.autoredi.editorial = {
                    "id": me.autoredi.editorial_id,
                };
              

                if(accion == "add"){
                    await this.axios.post('/edi', me.autoredi)
                    .then(response =>{  
                        console.log(response.data);
                        me.verificarAccion(response.data.data,response.status,accion);
                        me.hideDialog();
                       // me.fetchAutores();
                    }).catch(error => {
                        console.log(error);
                    } )
                } else {
                    await this.axios.put(`/edi/${me.autoredi.id}`, me.autoredi)
                    .then(response =>{
                       console.log(response.data);
                        if (response.status == 202) {
                            me.verificarAccion(response.data.data,response.status,accion);
                            me.hideDialog();
                            
                        }
                    }).catch(error =>{
                        console.log(error);
                    })
                }
            }
        },  async eliminarAutor(autoredi){
        let me = this;
        this.$swal.fire({
            title: '¿Esta seguro de eliminar el autor?',
            text: "Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'Cancelar!'
        }).then((result) => {
            if(result.value){
                me.editeAutoredi =me.autores_edi.indexOf(autoredi);
                this.axios.delete(`/edi/${autoredi.id}`)
                .then(response => {
                    me.verificarAccion(null, response.status,"del");
                }).catch(error => {
                    console.log(error);
                })
            }
        })
    },
        verificarAccion(autoredi,statusCode,accion){

                let me = this;
                const Toast = this.$swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    
                });
                switch (accion) {
                    case "add":
                        me.autores_edi.unshift(autoredi);
                        Toast.fire({
                            icon:'success',
                            title: 'Editorial registrada con exito'
                        });
                        break;
                    case "upd":
                        Object.assign(me.autores_edi[me.editeAutoredi],autoredi);
                        Toast.fire({
                            icon:'success',
                            title: 'Editorial actualizada con exito'
                        });
                        break;
                    case "del":
                       if(statusCode == 200){
                            me.autores_edi.splice(me.editeAutoredi,1);
                        Toast.fire({
                            icon:'success',
                            title: 'Editorial eliminada con exito'
                        });
                       
                       
                       
                       }else{
                        Toast.fire({
                            icon: 'error',
                            title: 'No se pudo eliminar el autor'
                        });
                       }
                        break;
         
        }
    },
  
    },
    mounted() {
        console.log(this.msj);
        this.fetchAutoresEdi();
        this.fetchAutores();
        this.fetchEditoriales();
    },
    
    
    };
</script>
