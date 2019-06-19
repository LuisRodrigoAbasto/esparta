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
                <i class="fa fa-align-justify"></i> Equipos
                <button type="button" @click="abrirModal('equipo','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
                <button type="button" @click="cargarPdf()" class="btn btn-info">
                  <i class="icon-doc"></i>&nbsp;Reporte
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="codigo">Codigo</option>
                              <option value="marca">Marca</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarEquipo(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarEquipo(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>   
                                                    
                            <th>Codigo</th>
                            <th>Marca</th>
                            <th>Categoria</th>
                            <th>Modelo</th>
                            <th>Estado</th>
                            <th>Opciones</th>  
                                                     
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="equipo in arrayEquipo" :key="equipo.id">
                            
                            <td v-text="equipo.codigo"></td>
                            <td v-text="equipo.marca"></td>                       
                            <td v-text="equipo.nombre_categoria"></td>
                            <td v-text="equipo.modelo"></td>
                            <td>
                                <div v-if="equipo.estado">
                                   <span class="badge badge-success">Activo</span>
                                </div>
                                 <div v-else>
                                   <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                            <td>
                                <button type="button" @click="abrirModal('equipo','actualizar',equipo)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;

                                <template v-if="equipo.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarEquipo(equipo.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarEquipo(equipo.id)">
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
                            <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                            <div class="col-md-9">
                                <select class="form-control" v-model="idcategoria">
                                  <option value="0" disabled>Seleccione</option>
                                  <option v-for="categoria in arrayCategoria" :key="categoria.id" :value="categoria.id" v-text="categoria.nombre"></option>

                                </select>                               
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                            <div class="col-md-9">
                                <input type="text" v-model="codigo" class="form-control" placeholder="Codigo de barras"> 
                                   <barcode :value="codigo" :options="{format: 'MSI'}">
                                     Generando codigo de barras.
                                     </barcode>                           
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Marca</label>
                            <div class="col-md-9">
                                <input type="email" v-model="marca" class="form-control" placeholder="Ingrese la marca del equipo">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Modelo</label>
                            <div class="col-md-9">
                                <input type="email" v-model="modelo" class="form-control" placeholder="Ingrese el modelo">
                            </div>
                        </div>     
                           
                     <div v-show="errorEquipo" class="form-group row div-error">
                           <div class="text-center text-error">
                                 <div v-for="error in errorMostrarMsjEquipo" :key="error" v-text="error">
                                 </div>
                           </div>
                    </div>
                   

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEquipo()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEquipo()">Actualizar</button>
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
      equipo_id: 0,
      idcategoria: 0,
      nombre_categoria: '',
      codigo: '',
      marca: '',
      modelo: '',
      arrayEquipo: [],
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorEquipo: 0,
      errorMostrarMsjEquipo: [],
      pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'codigo',
                buscar : '',
                arrayCategoria :[]
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
    listarEquipo(page,buscar,criterio) {
      let me = this;
      var url= '/equipo?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
      axios.get(url).then(function(response) {
          // handle success
         var respuesta= response.data;
         me.arrayEquipo = respuesta.equipos.data;
         me.pagination= respuesta.pagination;
          //console.log(response);
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
   selectCategoria(){
    let me = this;
      var url= '/categoria/selectCategoria';
      axios.get(url).then(function(response) {
       
        var respuesta= response.data;
         me.arrayCategoria = respuesta.categorias;
      
         
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
   },
 cargarPdf(){
    window.open('http://192.168.1.15:8000/equipo/listarPdf','blank');
   },
    cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEquipo(page,buscar,criterio);
            },
    registrarEquipo() {
      if (this.validarEquipo()) {
        return;
      }
      let me = this;

      axios
        .post("/equipo/registrar", {
          'idcategoria': this.idcategoria,
          'codigo': this.codigo,
          'marca': this.marca,
          'modelo': this.modelo

        })
        .then(function(response) {
          me.cerrarModal();
          me.listarEquipo(1,'','codigo');
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
    actualizarEquipo() {
      if (this.validarEquipo()) {
        return;
      }
      let me = this;

      axios
        .put("/equipo/actualizar", {
           'idcategoria': this.idcategoria,
          'codigo': this.codigo,
          'marca': this.marca,
          'modelo': this.modelo,
          'id': this.equipo_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarEquipo(1,'','codigo');
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
    desactivarEquipo(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de desactivar este equipo?",
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
            .put("/equipo/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listarEquipo(1,'','codigo');
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

    activarEquipo(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Esta seguro de activar esta equipo?",
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
            .put("/equipo/activar", {
              id: id
            })
            .then(function(response) {
              me.listarEquipo(1,'','codigo');
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
    validarEquipo() {
      this.errorEquipo = 0;
      this.errorMostrarMsjEquipo = [];

       if(this.idcategoria==0) this.errorMostrarMsjEquipo.push("Seleccione una categoria")
      if (!this.codigo) this.errorMostrarMsjEquipo.push("El codigo de la categoria no puede estar vacio.");

      if (this.errorMostrarMsjEquipo.length) this.errorEquipo = 1;

      return this.errorEquipo;
    },

    cerrarModal() {
      this.modal = 0;
      this.tituloModal = '';
      this.idcategoria='0';
      this.nombre_categoria='';
      this.codigo='';
      this.marca = '';
      this.modelo = '';
      this.errorEquipo='0';
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "equipo": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Equipo";
               this.idcategoria='0';
                this.nombre_categoria='';
                this.codigo='';
                this.marca = '';
                this.modelo = '';
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              // console.log(data);
              this.modal = 1;
              this.tituloModal = "Actualizar Equipo";
              this.tipoAccion = 2;
              this.equipo_id = data['id'];
               this.idcategoria = data['idcategoria'];
               this.codigo = data['codigo'];
                this.marca = data['marca'];
                this.modelo = data['modelo'];      
              break;
            }
          }
        }
      }
      this.selectCategoria();
    }
  },
  mounted() {
    this.listarEquipo(1,this.buscar,this.criterio);
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