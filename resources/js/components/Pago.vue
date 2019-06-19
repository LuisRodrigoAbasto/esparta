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
                <i class="fa fa-align-justify"></i> Pagos
                <button type="button" @click="abrirModal('pago','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="monto">Monto</option>
                              <option value="fecha">Fecha</option>
                            <!--  <option value="idcliente">Cliente</option> -->                                                    
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarPago(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarPago(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>   
                                                    
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Opciones</th>  
                                                     
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="pago in arrayPago" :key="pago.id">
                            
                            <td v-text="pago.monto"></td>
                            <td v-text="pago.fecha"></td>                       
                            <td v-text="pago.nombre_cliente"></td>                            
                            <td>
                                <div v-if="pago.estado">
                                   <span class="badge badge-success">Activo</span>
                                </div>
                                 <div v-else>
                                   <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                            <td>
                                <button type="button" @click="abrirModal('pago','actualizar',pago)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;

                                <template v-if="pago.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarPago(pago.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarPago(pago.id)">
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
                            <label class="col-md-3 form-control-label" for="text-input">Cliente</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="idcliente">
                                  <option value="0" disabled>Seleccione</option>
                                  <option v-for="cliente in arrayCliente" :key="cliente.id" :value="cliente.id
                                     " v-text="cliente.nombre"></option>

                                </select>                               
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                            <div class="col-md-9">
                                <input type="text" v-model="monto" class="form-control" placeholder="Ingrese el monto"> 
                                                 
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Fecha</label>
                            <div class="col-md-9">
                                <input type="dateTime-local" v-model="fecha" class="form-control">
                            </div>
                        </div>
                           
                     <div v-show="errorPago" class="form-group row div-error">
                           <div class="text-center text-error">
                                 <div v-for="error in errorMostrarMsjPago" :key="error" v-text="error">
                                 </div>
                           </div>
                    </div>
                   

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPago()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPago()">Actualizar</button>
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
import VueBarcode from 'vue-barcode';
export default {
  data() {
    return {
      pago_id: 0,
      idcliente: 0,
      nombre_cliente: '',
      monto: '0',
      fecha: '',
      arrayPago: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorPago: 0,
      errorMostrarMsjPago: [],
      pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'monto',
                buscar : '',
                arrayCliente :[]
    }
  },
  components: {
    'barcode': VueBarcode
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
    listarPago(page,buscar,criterio) {
      let me = this;
      var url= '/pago?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
      axios.get(url).then(function(response) {
          // handle success
         var respuesta= response.data;
         me.arrayPago = respuesta.pagos.data;
         me.pagination= respuesta.pagination;
          //console.log(response);
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
   selectCliente(){
    let me = this;
      var url= '/cliente/selectCliente';
      axios.get(url).then(function(response) {
       
        var respuesta= response.data;
         me.arrayCliente = respuesta.clientes;
       me.listarPago(1,'','monto');
         
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
                me.listarPago(page,buscar,criterio);
            },
    registrarPago() {
      if (this.validarPago()) {
        return;
      }
      let me = this;

      axios
        .post("/pago/registrar", {
          'idcliente': this.idcliente,
          'monto': this.monto,
          'fecha': this.fecha
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarPago(1,'','monto');
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
    actualizarPago() {
      if (this.validarPago()) {
        return;
      }
      let me = this;

      axios
        .put("/pago/actualizar", {
           'idcliente': this.idcliente,
          'monto': this.monto,
          'fecha': this.fecha,
          'id': this.pago_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarPago(1,'','monto');
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
    desactivarPago(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de desactivar este pago?",
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
            .put("/pago/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listarPago(1,'','monto');
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

    activarPago(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de activar este pago?",
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
            .put("/pago/activar", {
              id: id
            })
            .then(function(response) {
              me.listarPago(1,'','monto');
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
    validarPago() {
      this.errorPago = 0;
      this.errorMostrarMsjPago = [];

       if(this.idcliente==0) this.errorMostrarMsjCliente.push("Seleccione el Cliente")
      if (!this.monto) this.errorMostrarMsjPago.push("Ingrese un monto");

      if (this.errorMostrarMsjPago.length) this.errorPago = 1;

      return this.errorPago;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
      this.idcliente='0';
      this.nombre_cliente='';
      this.monto='0';
      this.fecha = '';
      this.errorPago='0';
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "pago": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Pago";
               this.idcliente='0';
                this.nombre_cliente='';
                this.monto='0';
                this.fecha = '';
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              // console.log(data);
              this.modal = 1;
              this.tituloModal = "Reprogramar Pago";
              this.tipoAccion = 2;
              this.pago_id = data['id'];
               this.idcliente = data['idcliente'];
               this.monto = data['monto'];
                this.fecha = data['fecha'];      
              break;
            }
          }
        }
      }
      this.selectCliente();
    }
  },
  mounted() {
    this.listarPago(1,this.buscar,this.criterio);
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