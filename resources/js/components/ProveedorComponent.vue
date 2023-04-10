<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="float-start">
                                   Listado de categorias de productos
                                </h5>
                            </div>
                            <div class="col-6">
                                <button @click="showDialog"
                                    
                                    class="btn btn-success btn-sm float-end"
                                   
                                >
                                    Nuevo registro
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table bordered">
                            <thead>
                                <tr>
                                    <th scope="col-2">Categoria</th>
                             
                                    <th scope="col-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in categorias" :key="item.id">
                                    <td>{{ item.nombre }}</td>
                              
                                    <td>
                                        <button
                                        @click="showDialogEditar(item)"
                                            type="button"
                                            class="btn btn-primary btn-sm"
                                            
                                        >
                                            <i class="fas fa-edit"></i>
                                            Editar
                                        </button>
                                        &nbsp;
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            @click="eliminarAutor(item)"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="autorModal" tabindex="-1" aria-labelledby="autorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="autorModalLabel">{{ formTitle }}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     <!-- definiendo el cuerpo del modal -->
     <div class="row">
        <div class="form-group col-12">
            <label for="autor">Autor</label>
            <input type="text" class="form-control" v-model="categoria.nombre">
            <span class="text-danger" v-show="categoriasErrors.nombre">Nombre es requerido</span>
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
           categorias: [],
                categoria:{
                    id:null,
                    nombre:""
                },
            editeCategorias: -1,
            categoriasErrors:{
                nombre:false
            }
        }
    },
   computed:{
        formTitle(){
            return this.categoria.id==null ? "Nuevo registro":"Editar registro";
        },
        btnTitle(){
            return this.categoria.id==null?"Guardar":"Actualizar ";
        }
    },
    methods: {
        async fetchAutores() {
            let me = this;
            await this.axios.get("/pro").then((response) => {
                console.log(response.data);
                me.categorias = response.data;
            });
        },
        showDialog(){
            this.categoria={
                id: null,
                nombre:"",
             
            },
            this.categoriasErrors ={
                nombre:false
            }
            $('#autorModal').modal('show');
        },
         showDialogEditar(categoria){
            let me = this;
            $('#autorModal').modal('show');
            me.editeCategorias= me.categorias.indexOf(categoria);
            me.categoria = Object.assign({},categoria);
        },
        hideDialog(){
            let me = this;
            setTimeout(()=>{
                me.categoria={
                    id: null,
                    nombre:"",
                  
                };
            },300)
             $('#autorModal').modal('hide');
        },
        async saveOrUpdate(){
            let me = this;
            me.categoria.nombre== '' ? me.categoriasErrors.nombre = true : me.categoriasErrors.nombre = false;
            if(me.categoria.nombre){ 
                let accion = me.categoria.id == null ? "add" : "upd"
                if(accion == "add"){
                    await this.axios.post('/pro', me.categoria)
                    .then(response =>{
                        console.log(response.data);
                        me.verificarAccion(response.data.data,response.status,accion);
                        me.hideDialog();
                       // me.fetchAutores();
                    }).catch(error => {
                        console.log(error);
                    } )
                } else {
                    await this.axios.put(`/pro/${me.categoria.id}`, me.categoria)
                    .then(response =>{

                        if (response.status == 202) {
                            me.verificarAccion(response.data.data,response.status,accion);
                            me.hideDialog();
                            
                        }
                    }).catch(error =>{
                        console.log(error);
                    })
                }
            }
        },  async eliminarAutor(categoria){
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
                me.editeCategorias =me.categorias.indexOf(categoria);
                this.axios.delete(`/pro/${categoria.id}`)
                .then(response => {
                    me.verificarAccion(null, response.status,"del");
                }).catch(error => {
                    console.log(error);
                })
            }
        })
    },
        verificarAccion(categoria,statusCode,accion){

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
                        me.categorias.unshift(categoria);
                        Toast.fire({
                            icon:'success',
                            title: 'Autor registrado con exito'
                        });
                        break;
                    case "upd":
                        Object.assign(me.categorias[me.editeCategorias],categoria);
                        Toast.fire({
                            icon:'success',
                            title: 'Autor actualizado con exito'
                        });
                        break;
                    case "del":
                       if(statusCode == 200){
                            me.categorias.splice(me.editeCategorias,1);
                        Toast.fire({
                            icon:'success',
                            title: 'Autor eliminado con exito'
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
        this.fetchAutores();
    },
    
    
    };
</script>
