<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>  
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Clientes
                <button type="button" @click="abrirModal('cliente','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Nombre</option>
                              <option value="apellido">Apellido</option>
                              <option value="ci">CI</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarCliente(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarCliente(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>   
                                                    
                            <th>Nombre</th>
                            <th>CI</th>
                            <th>Fecha Nacimiento</th>
                            <th>Direccion</th>
                            <th>telefono</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Opciones</th>  
                                                     
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cliente in arrayCliente" :key="cliente.id">
                            
                            <td v-text="cliente.nombre+' '+cliente.apellido"></td>
                            <td v-text="cliente.ci"></td>
                            <td v-text="cliente.fechaNacimiento"></td>
                            <td v-text="cliente.direccion"></td>
                            <td v-text="cliente.telefono"></td>
                            <td v-text="cliente.email"></td>
                            <td>
                                <div v-if="cliente.estado">
                                   <span class="badge badge-success">Activo</span>
                                </div>
                                 <div v-else>
                                   <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                            <td>
                                <button type="button" @click="abrirModal('cliente','actualizar',cliente)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;

                                <template v-if="cliente.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarCliente(cliente.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarCliente(cliente.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>

                            </td>
                        </tr>
                       
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
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
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del cliente">
                                
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Apellido</label>
                            <div class="col-md-9">
                                <input type="email" v-model="apellido" class="form-control" placeholder="Ingrese el Apellido">
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">CI</label>
                            <div class="col-md-9">
                                <input type="email" v-model="ci" class="form-control" placeholder="Ingrese la Cedula de Identidad">
                            </div>
                        </div>  

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Fecha Nacimiento</label>
                            <div class="col-md-9">
                            <input type="date" name="fechaNacimiento" step="1" min="1990-01-01" max="2019-12-31" value="1990-01-01">
                          </div>

                        </div>
                           <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Direccion</label>
                            <div class="col-md-9">
                                <input type="email" v-model="direccion" class="form-control" placeholder="Ingrese la Cedula de Identidad">
                            </div>
                        </div>  

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                            <div class="col-md-9">
                                <input type="email" v-model="telefono" class="form-control" placeholder="Ingrese la Cedula de Identidad">
                            </div>
                        </div>  

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Email</label>
                            <div class="col-md-9">
                                <input type="email" v-model="email" class="form-control" placeholder="Ingrese la Cedula de Identidad">
                            </div>
                        </div>  
                     <div v-show="errorCliente" class="form-group row div-error">
                           <div class="text-center text-error">
                                 <div v-for="error in errorMostrarMsjCliente" :key="error" v-text="error">
                                 </div>
                           </div>
                    </div>
                   

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCliente()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCliente()">Actualizar</button>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
    <!-- Inicio del modal Eliminar -->

    <!-- Fin del modal Eliminar -->
</main>
</template>

<script>
export default {
  data() {
    return {
      cliente_id: 0,
      nombre: '',
      apellido: '',
      ci: '',
      fechaNacimiento: '',
      direccion:'',
      telefono:'',
      email:'',
      clave:'',
      arrayCliente: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorCliente: 0,
      errorMostrarMsjCliente: [],
      pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
    }
  },
  computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
  },

  methods: {
    listarCliente(page,buscar,criterio) {
      let me = this;
      var url= '/cliente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
      axios.get(url).then(function(response) {
          // handle success
         var respuesta= response.data;
         me.arrayCliente = respuesta.clientes.data;
         me.pagination= respuesta.pagination;
          //console.log(response);
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
     cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCliente(page,buscar,criterio);
            },
    registrarCliente() {
      if (this.validarCliente()) {
        return;
      }
      let me = this;

      axios
        .post("/cliente/registrar", {
          'nombre': this.nombre,
          'apellido': this.apellido,
          'ci': this.ci,
          'fechaNacimiento': this.fechaNacimiento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'email': this.email,
          'clave': this.clave
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCliente(1,'','nombre');
          swal({
            position: 'center',
            type: 'success',
            title: 'Se Registro Correctamente',
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    actualizarCliente() {
      if (this.validarCliente()) {
        return;
      }
      let me = this;
      axios
        .put("/cliente/actualizar", {
          'nombre': this.nombre,
          'apellido': this.apellido,
          'ci': this.ci,
          'fechaNacimiento': this.fechaNacimiento,
          'direccion': this.direccion,
          'telefono': this.telefono,
          'email': this.email,
          'clave': this.clave,
          'id': this.cliente_id,          
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCliente(1,'','nombre');
          swal({
            position: 'center',
            type: 'success',
            title: 'Se Actualizo Correctamente',
            showConfirmButton: false,
            timer: 1500
          });
        })
        .catch(function(error) {
          console.log(error);
        });       
    },
    desactivarCliente(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de desactivar esta cliente?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          axios
            .put("/cliente/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listarCliente(1,'','nombre');
              swalWithBootstrapButtons(
                "Desactivado!",
                "El registro ha sido desactivado con exito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
          /* swalWithBootstrapButtons(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )*/
        }
      });
    },

    activarCliente(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de activar este cliente?",
       // text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          axios
            .put("/cliente/activar", {
              id: id
            })
            .then(function(response) {
              me.listarCliente(1,'','nombre');
              swalWithBootstrapButtons(
                "Activado!",
                "El registro ha sido activado con exito.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        } else if (
          // Read more about handling dismissals
          result.dismiss === swal.DismissReason.cancel
        ) {
          /* swalWithBootstrapButtons(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )*/
        }
      });
    },
    validarCliente() {
      this.errorCliente = 0;
      this.errorMostrarMsjCliente = [];

      if (!this.nombre)
        this.errorMostrarMsjCliente.push(
          "El nombre de la cliente no puede estar vacio."
        );

      if (this.errorMostrarMsjCliente.length) this.errorCliente = 1;

      return this.errorCliente;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
      this.nombre = '';
      this.apellido = '';
      this.ci = '';
      this.fechaNacimiento='';
      this.direccion='';
      this.telefono='';
      this.email='';
      this.clave='';
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "cliente": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Cliente";
              this.nombre = '';
               this.apellido = '';
                this.ci = '';
                this.fechaNacimiento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.clave='';
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              // console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar cliente";
              this.tipoAccion = 2;
              this.cliente_id = data["id"];
              this.nombre = data["nombre"];
               this.apellido = data["apellido"];
                this.ci = data["ci"];
                this.fechaNacimiento= data["fechaNacimiento"];
                this.direccion= data["direccion"];
                this.telefono= data["telefono"];
                this.email= data["email"];
                this.clave= data["clave"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.listarCliente(1,this.buscar,this.criterio);
    //console.log('Component mounted.')
  }
};
</script>
<style>
.modal-content {
  width: 100% !important;
  position: absolute !important;
}
.mostrar {
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}
.div-error {
  display: flex;
  justify-content: center;
}
.text-error {
  color: red !important;
  font-weight: bold;
}
</style>