<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="float-start">Listado de Categorias</h5>
                            </div>
                            <div class="col-6">
                                <button @click="showDialog" class="btn btn-success btn-sm float-end">Nuevo</button>
                            </div>
                        </div> 
                                       
                    </div>
                    <div class="card-body">
                        <table class="table bordered">
                            <thead>
                            <tr>
                                <th scope="col">Categoria</th>
                                <th scope="col">accion</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in categorias" :key="item.id">
                                <td>{{ item.nombre }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" @click="showDialogEditar(item)">Editar</button>
                                    &nbsp;
                                    <button type="button" class="btn btn-danger btn-sm" @click="eliminar(item)">Eliminar</button>
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
<div class="modal fade" id="categoriaModal" tabindex="-1" aria-labelledby="categoriaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="categoriaModalLabel">{{ formTitle }}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!--definiendo el cuerpo del modal-->
        <div class="row">
           <div class="form-group col-12">
               <label for="nombre">Nombre</label>
               <input type="text" class="form-control" v-model="categoria.nombre">
               <span class="text-danger" v-show="categoriaErrors.nombre">Nombre es requerido</span>
           </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" @click="saveOrUpdate()">{{ btnTitle }}</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
    export default {
        data(){
            return{
                categorias: [],
                categoria:{
                    id:null,
                    nombre:""
                },
                editedCategoria: -1,
                categoriaErrors:{
                    nombre:false
                },
                
            }
        },  
        
        computed:{
            formTitle(){
                return this.categoria.id == null ? "Agregar Categoria" : "Actualizar Categoria";
            },
            btnTitle(){
                return this.categoria.id == null ? "Guardar" : "Actualizar";
            }
        },      
        methods:{
            async fetchCategorias(){
                let me = this;
                await this.axios.get('/categorias')
                .then(response =>{
                    me.categorias = response.data;
                })
            },
        
        showDialog(){
            this.categoria = {
                id:null,
                nombre:""
            };
            this.categoriaErrors = {
                nombre:false
            }
            $('#categoriaModal').modal('show');
            },

        showDialogEditar(categoria){
            let me = this;
            $('#categoriaModal').modal('show');
            me.editedCategoria = me.categorias.indexOf(categoria);
            me.categoria = Object.assign({},categoria);
            },
            hideDialog(){
                let me = this;
                setTimeout(()=> {
                    me.categoria = {
                        id:null,
                        nombre:""  
                    }
                },300);
                $('#categoriaModal').modal('hide');
            },
        async saveOrUpdate(){
            let me = this;
            me.categoria.nombre == '' ? me.categoriaErrors.nombre = true : me.categoriaErrors.nombre = false;
            if(me.categoria.nombre){
               let accion = me.categoria.id == null ? "add" : "upd";
               if(accion == "add"){
                //guardar una categoria
                await this.axios.post('/categorias', me.categoria)
                .then(response => {
                    if(response.status == 201){
                    //console.log(response.data);
                    me.verficarAccion(response.data.data, response.status,accion);
                    me.hideDialog();
                    }
                }).catch(errors =>{
                    console.log(errors);
                }) 
               }else{
                //para actualizar una categoria
                await this.axios.put(`/categorias/${me.categoria.id}`, me.categoria)
                .then(response => {
                    if(response.status == 202){
                        me.verficarAccion(response.data.data, response.status,accion);
                        me.hideDialog();
                    }
                }).catch(errors =>{
                    console.log(errors);
               })
            }
            }
        },
        async eliminar(categoria){
            let me = this;
            this.$swal.fire({
                title: 'Seguro/a de eliminar este registro?',
                text: "No podras revertir la accion",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
            }).then((result)=>{
                if(result.value){
                    me.editedCategoria = me.categorias.indexOf(categoria);
                    this.axios.delete(`/categorias/${categoria.id}`)
                    .then(response => {
                        me.verficarAccion(null,response.status,"del");
                    }).catch(errors =>{
                        console.log(errors);
                    })
                }

            })

        },

            verficarAccion(categoria,statusCode, accion){
                let me = this;
                const Toast = this.$swal.mixin({
                    toast: true,
                    position:'top-rigth',
                    showConfirmButton:false,
                    timer: 2000,
                    timerProgressBar: true,
                });
                switch (accion) {
                    case "add":
                        //agregamos al principio el arreglo categoria, la nueva categoria
                        me.categorias.unshift(categoria);
                        Toast.fire({
                            icon: 'success',
                            'title':'Categoria Registrada con Exito...!'
                        });
                        break;
                    case "upd":
                        Object.assign(me.categorias[me.editedCategoria],categoria);
                        Toast.fire({
                            icon: 'success',
                            'title':'Categoria actualizada con Exito...!'
                        });
                        break;
                    case "del":
                        if (statusCode == 200){
                                me.categorias.splice(me.editedCategoria,1);
                                Toast.fire({
                                    icon: 'success',
                                    'title':'Categoria Eliminada...!!!'
                                });
                            }else{
                            Toast.fire({
                                    icon: 'warning',
                                    'title':'Error al eliminar la categoria, Intente de nuevo'
                            });
                        }
                        break;
                    }
            },   
        
       
    }, 
        mounted() {
            this.fetchCategorias();
        },
    };

</script>