<template>
   
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Empleados
                <button type="button"  @click="abrirModal('empleado','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <option value="apellido">apellido</option>
                               <option value="telefono">telefono</option>
                            </select>
                           <input type="text" v-model="buscar" @keyup.enter="listarEmpleado(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEmpleado(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>telefono</th>
                            <th>fechaNacimiento</th>
                            <th>ci</th>
                            <th>sueldo</th>
                            <th>tipo</th>
                            <th>estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr  v-for="empleado in arrayEmpleado" :key="empleado.id"> 

                            <td>
                                <button type="button" @click="abrirModal('empleado','actualizar',empleado)" class="btn btn-warning btn-sm" >
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;
                            <template  v-if="empleado.estado==1">
                              <button type="button" class="btn  btn-danger btn-sm" @click="desactivarEmpleado(empleado.id)">
                                 <i class="icon-trash"> </i>
                              </button>   
                            </template>
                             <template  v-else>
                              <button type="button" class="btn  btn-info btn-sm" @click="activarEmpleado(empleado.id)">
                                 <i class="icon-check"> </i>
                              </button>   
                            </template>
                            </td>
                            <td v-text="empleado.nombre+' '+empleado.apellido"></td>
                              <td v-text="empleado.telefono"></td>
                               <td v-text="empleado.fechaNacimiento"></td>
                                <td v-text="empleado.ci"></td>
                                 <td v-text="empleado.sueldo"></td>
                                   <td v-text="empleado.tipo"></td>
                            <td>
                                <div v-if="empleado.estado==1">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivo</span>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page== isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                 
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade"  tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModa"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del nuevo Empleado">
                              
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">apellido</label>
                            <div class="col-md-9">
                                <input type="text" v-model="apellido" class="form-control" placeholder="Enter apellido">
                            </div>
                        </div>
                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                                    </div>
                        </div>
                         <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">fechaNacimiento</label>
                                    <div class="col-md-9">
                                        <input type="date" v-model="fechaNacimiento" class="form-control" placeholder="fechaNaciiento">
                                    </div>
                        </div>
                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">ci</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="ci" class="form-control" placeholder="Canet identidad">
                                     </div>
                        </div>
                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">sueldo</label>
                                    <div class="col-md-9">
                                        <input type="decimal" v-model="sueldo" class="form-control" placeholder="sueldo">
                                     </div>
                        </div>

                        <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo</label>
                                    <div class="col-md-9">
                                        <select v-model="tipo" class="form-control">
                                            <option value="INSTRUCTOR">INSTRUCTOR</option>
                                            <option value="SECRETARIA">SECRETARIA</option>
                                            <option value="GERENTE">GERENTE</option>
                                        </select>                                    
                                    </div>
                        </div>
                        <div v-show="errorEmpleado" class="form-group row div-error">
                            <div class="text-center text-error">  
                               <div v-for="error in errorMostrarMsjEmpleado" :key="error" v-text="error">
                               
                               </div>
                            </div>   
                        </div>
                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpleado()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpleado()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    
</main>


</template>

<script>
    export default {
        data(){
           return{
               empleado_id : 0,
               nombre:'',
               apellido:'',
               telefono:'',
               fechaNacimiento:'',
               ci:'',
               sueldo:'',
               tipo:'',
               arrayEmpleado : [],
               modal : 0,
               tituloModal :'',
               tipoAccion :0,
               errorEmpleado : 0,
               errorMostrarMsjEmpleado : [],
               pagination :
               { 'total': 0,
                 'current_page':0,
                 'per_page':0,
                 'last_page':0,
                 'from':0,
                 'to':0, 
               },
               offset : 3,
               criterio : 'nombre',
               buscar : ''

           }
        },
        computed :
        {
           isActived : function()
           {
             return this.pagination.current_page;
           },
           //calcula los elemento de la paginacion
           pagesNumber : function()
           { if(!this.pagination.to)
                 {
                  return [];
                 }
                 var from =this.pagination.current_page - this.offset;
                 if(from <1)
                 {
                     from = 1;
                 }
                 var to = from + (this.offset * 2);
                 if(to >= this.pagination.last_page)
                 {
                     to =this.pagination.last_page;
                 }
                 var pagesArray = [];
                 while( from <= to)
                 {
                     pagesArray.push(from);
                     from++;
                 }
                 return pagesArray;

           }
        },
        methods:{
             listarEmpleado(page,buscar,criterio)
             {
               let me=this;
               var url='/empleado?page=' + page +'&buscar='+ buscar + '&criterio=' +criterio;
               axios.get(url).then(function(response){
               var respuesta = response.data;
               me.arrayEmpleado = respuesta.empleados.data;
               me.pagination=respuesta.pagination;
               
             })
                    .then(function (response) {
                        // handle success
                       me.arrayEmpleado=response.data;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function () {
                        // always executed
                    });
             },
             cambiarPagina(page,buscar,criterio)
             {
                let me =this;
                me.pagination.current_page=page;
                me.listarEmpleado(page,buscar,criterio);             
            },
             registrarEmpleado(){
                if(this.validarEmpleado()==1)
                {return; }
                let me=this;
               axios.post('/empleado/registrar',{
                    'nombre': this.nombre,
                    'apellido': this.apellido,
                    'telefono' : this.telefono,
                    'fechaNacimiento' : this.fechaNacimiento,
                    'ci' : this.ci,
                    'sueldo' : this.sueldo,
                    'tipo': this.tipo,

                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpleado(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
             },
             actualizarEmpleado(){
                 if(this.validarEmpleado()==1)
                {return; }
                let me=this;
               axios.put('/empleado/actualizar',{
                  
                    'nombre': this.nombre,
                    'apellido': this.apellido,
                    'telefono' : this.telefono,
                    'fechaNacimiento' : this.fechaNacimiento,
                    'ci' : this.ci,
                    'sueldo' : this.sueldo,
                    'tipo': this.tipo, 
                    'id': this.empleado_id

                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpleado(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
             },
desactivarEmpleado(id) {
                  swal({
                title: 'Esta seguro de desactivar esta empleado?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/empleado/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEmpleado(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
},
activarEmpleado(id){
               swal({
                title: 'Esta seguro de activar esta Empleado?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/empleado/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEmpleado(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
validarEmpleado(){
              this.errorEmpleado=0;
              this.errorMostrarMsjEmpleado=[];
              if(!this.nombre) this.errorMostrarMsjEmpleado.push("El nombre no puede estar Vacio.") ;
              if(this.errorMostrarMsjEmpleado.length) this.errorEmpleado = 1;
              return this.errorEmpleado;
              },
cerrarModal(){
              this.modal=0;
              this.tituloModal='';
                               this.nombre='';
                               this.apellido='';
                               this.telefono='';
                               this.fechaNacimiento='';
                               this.ci='';
                               this.sueldo=0;  
                               this.tipo='';       
             },
 abrirModal(modelo,accion,data=[]){
                 switch(modelo){
                     case "empleado":
                     {  switch(accion){
                             case 'registrar':
                             { this.modal = 1;
                               this.tituloModal='Registrar empleado';
                               this.nombre='';
                               this.apellido='';
                               this.telefono='';
                               this.fechaNacimiento='';
                               this.ci='';
                               this.sueldo=0;
                               this.tipo='';
                               this.tipoAccion=1;
                               break;

                             }
                             case 'actualizar':
                             {
                               // console.log(data);
                               this.modal=1;
                               this.tituloModal='Actualizar empleado';
                               this.tipoAccion=2;
                               this.empleado_id=data['id'];
                               this.nombre=data['nombre'];
                               this.apellido=data['apellido'];
                               this.telefono=data['telefono'];
                               this.fechaNacimiento=data['fechaNacimiento'];
                               this.ci=data['ci'];
                               this.sueldo=data['sueldo'];
                               this.tipo=data['tipo'];
                               
                               break;
                             }
                         }

                     }
                 }
             }
        },
        mounted() {
           this.listarEmpleado(1,this.buscar,this.criterio);
        }
    }
</script>
<style >
   .modal-content{
      width: 100% !important;
      position: absolute !important;
   }
   .mostrar{
       display: list-item !important;
       opacity: 1 !important;
       position: absolute !important;
       background-color: #3c29297a !important;
   }
   .div-error
   {
       display: flex;
       justify-content: center;
   }
   .text-error{
       color: red !important;
       font-weight: bold;
   }
</style>

