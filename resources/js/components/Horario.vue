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
                <i class="fa fa-align-justify"></i> Horarios
                <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <template v-if="listado">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre">Empleado</option>
                            
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listar(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listar(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>   
                                                    
                            <th>Fecha Inicio</th>
                            <th>Fecha Final</th>
                            <th>Empleado</th>                        
                            <th>Estado</th>
                            <th>Opciones</th>  
                                                     
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="horario in arrayHorario" :key="horario.id">
                            
                            <td v-text="horario.fechaInicio"></td>
                            <td v-text="horario.fechaFin"></td>                       
                            <td v-text="horario.empleado"></td>                            
                            <td>
                                <div v-if="horario.estado">
                                   <span class="badge badge-success">Activo</span>
                                </div>
                                 <div v-else>
                                   <span class="badge badge-danger">Desactivado</span>
                                </div>

                            </td>
                            <td>
                                <button type="button" @click="abrirModal('horario','actualizar',horario)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;

                                <template v-if="horario.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivar(horario.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activar(horario.id)">
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
            </template>
             <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Empleado<span  v-show="idEmpleado==0">(*Selecione)</span></label>
                                        <v-select                                             
                                            :on-search="selectEmpleado"                                                          
                                            label="empleado"   
                                            :options="arrayEmpleado"           
                                            placeholder="Buscar Empleado..."
                                            :onChange="getDatosEmpleado"
                                            >
                                        </v-select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fecha Entrada<span>(*Selecione)</span></label>
                                    <input type="datetime-local" v-model="fechaInicio" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Fecha Final</label>
                                     <input type="datetime-local" v-model="fechaFin" class="form-control">
                                </div>
                            </div>                             
                            
                          <div class="col-md-12">
                              <div v-show="errorMostrar" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsj" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                            </div> 

                        </div>

                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Equipo <span v-show="idEquipo==0">(*Selecione)</span></label>
                                        <v-select 
                                            :on-search="selectEquipo"
                                            label="codigo"
                                            :options="arrayEquipoV"
                                            placeholder="Buscar Equipo..."
                                            :onChange="getDatosEquipo"
                                            >
                                        </v-select>
                                </div>
                            </div>
                            <div class="col-md-0">
                                <div class="form-group">
                                    <button @click="abrirModal()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i> <i class="icon-plus"></i> <i class="icon-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"> Agregar</i></button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length"> 
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.codigo"></td>
                                           <td v-text="detalle.marca">
                                            <td v-text="detalle.modelo">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                      <tr>
                                        <td colspan="5">
                                          No hay Horarios Agregados
                                        </td>
                                      </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                        

                        <!---listado formula-->
                        
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrar()">Registrar Horario</button>
                              
                            </div>
                        </div>
                    </div>
                    </template>
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
                              <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioE">
                                          <option value="marca">Marca</option>
                                          <option value="modelo">Modelo</option>
                                        </select>
                                        <input type="text" v-model="buscarE" @keyup.enter="listarEquipo(buscarE,criterioE)" class="form-control" placeholder="Buscar Equipo" >
                                        <button type="submit" @click="listarEquipo(buscarE,criterioE)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                  <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Codigo</th>
                                            <th>Marca</th>
                                            <th>Modelo</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="equipo in arrayEquipo" :key="equipo.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(equipo)" class="btn btn-success btn-sm" >
                                                  <i class="icon-check"></i>
                                                </button>                                               
                                            </td>
                                            <td v-text="equipo.codigo"></td>
                                            <td v-text="equipo.marca"></td>
                                            <td v-text="equipo.modelo"></td>
                                            <td>
                                                <div v-if="equipo.estado">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                    <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
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
import VSelect from 'vue-select';
export default {
  data() {
    return {
      horario_id: 0,
      idEmpleado: 0,
      empleado: '',
      fechaInicio: '',
      fechaFin: '',
      arrayHorario: [],
      arrayDetalle: [],
      arrayEquipo: [],
      arrayEquipoV: [],
      arrayEmpleado :[],
      listado: 1,
      modal: 0,
      tituloModal: '',
      tipoAccion: 0,
      errorMostrar: 0,
      errorMostrarMsj: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset : 3,
      criterio: "nombre",
      buscar: "",
      idEquipo: 0,
      codigo:0,
      marca:"",
      modelo:"",
      buscarE: "",
      criterioE: "marca",
      equipo: ""
      
    };
  },
components:
  {
      VSelect
  },
 computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
 },
methods: {
    listar(page,buscar,criterio) {
      let me = this;
      var url= "/horario?page=" + page + "&buscar="+ buscar + "&criterio="+ criterio;
      axios.get(url).then(function(response) {
          // handle success
         var respuesta= response.data;
         me.arrayHorario = respuesta.horarios.data;
         me.pagination= respuesta.pagination;
          //console.log(response);
        })
        .catch(function(error) {
          // handle error
          console.log(error);
        });
    },
    selectEmpleado(search, loading) {
      let me = this;
      loading(true);
      var url = "/empleado/selectEmpleado?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayEmpleado = respuesta.empleados;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
   
    getDatosEmpleado(val1) {
      let me = this;
      me.loading = true;
      me.idEmpleado = val1.id;
    },
    cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listar(page,buscar,criterio);
    },
 

     selectEquipo(search, loading) {
      let me = this;
      loading(true);
      var url = "/equipo/selectEquipo?filtro=" + search;
      axios
        .get(url)
        .then(function(response) {
          let respuesta = response.data;
          q: search;
          me.arrayEquipoV = respuesta.equipos;
          loading(false);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosEquipo(val1) {
      let me = this;
      me.loading = true;
      me.idEquipo = val1.id;
      me.codigo = val1.codigo;
      me.marca = val1.marca;
      me.modelo = val1.modelo;
    },
    encuentra(id) {
      var sw = 0;
      for (var i = 0; i < this.arrayDetalle.length; i++) {
        if (this.arrayDetalle[i].idEquipo == id) {
          sw = true;
        }
      }
      return sw;
    },
      eliminarDetalle(index) {
      let me = this;
      me.arrayDetalle.splice(index, 1);
    },
    agregarDetalle() {
      let me = this;
      if (me.idEquipo == 0) {

      } else {
        if (me.encuentra(me.idEquipo)) {
          swal({
            type: "error",
            title: "Error...",
            text: "Este Equipo ya se Encuentra Agregado!"
          })
        } else {
          me.arrayDetalle.push({
            idEquipo: me.idEquipo,
            codigo: me.codigo,
            modelo: me.modelo,
            marca: me.marca
          });
        }
      }
    },
    agregarDetalleModal(data = []) {
      let me = this;
      if (me.encuentra(data['id'])) {
        swal({
          type: 'error',
          title: 'Error...',
          text: 'Este Equipo ya se Encuentra Agregado!'
        });
      } else {
        me.arrayDetalle.push({
          idEquipo: data['id'],
          codigo: data['codigo'],
          modelo: data['modelo'],
          marca: data['marca']
        });
      }
    },
    listarEquipo(buscar, criterio) {
      let me = this;
      var url = "/equipo/listarEquipo?buscar=" + buscar + "&criterio=" + criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayEquipo = respuesta.equipos.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    registrar() {
     if (this.validar()) {
        return;
      }
      let me = this;
      axios.post("/horario/registrar", {
          'idEmpleado' : this.idEmpleado,
          'data' : this.arrayDetalle
        })
       .then(function(response) {
          me.listado = 1;
          me.listar(1, "", "nombre");
          me.fechaInicio = '';
          me.fechaFin = "";
          me.arrayDetalle = [];
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizar() {
      if (this.validar()) {
        return;
      }
      let me = this;

      axios
        .put("/horario/actualizar", {
           'idEmpleado': this.idEmpleado,
          'id': this.horario_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listar(1,"","nombre");
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
   desactivar(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Estas Seguro de Desactivar al Equipo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar!",
        cancelButtonText: "No, Cancelar!",
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;

          axios
            .put("/horario/desactivar", {
              id: id
            })
            .then(function(response) {
              me.listar(1, "", "nombre");
              swalWithBootstrapButtons(
                "Desactivado!",
                "El Horario ha sido Desactivado.",
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
                    'Cancelado',
                    'Su Producto Esta a Salvo',
                    'error'
                    )*/
        }
      });
    },
 activar(id) {
      const swalWithBootstrapButtons = swal.mixin({
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
      });

      swalWithBootstrapButtons({
        title: "Estas Seguro de Activar al Equipo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Activar ",
        cancelButtonText: "No, Cancelar!",
        reverseButtons: true
      }).then(result => {
        if (result.value) {
          let me = this;
          axios
            .put("/horario/activar", {
              id: id
            })
            .then(function(response) {
              me.listar(1,"",'nombre');
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
     validar() {
      this.errorMostrar = 0;
      this.errorMostrarMsj = [];
      if(this.arrayDetalle.length<=0) this.errorMostrarMsj.push("Ingrese Equipo al Detalle");

      if(this.errorMostrarMsj.length) this.errorMostrar=1;
      return this.errorMostrar;
    },

    mostrarDetalle() {
      let me = this;
      this.listado = 0;
      me.arrayDetalle = [];
    },
     ocultarDetalle() {
      this.listado = 1;
    },
  
    cerrarModal() {
       this.modal = 0;
      this.tituloModal = "";
    },
    abrirModal() {
      this.arrayEquipo = [];
      this.modal = 1;
      this.tituloModal = "Selecione uno o varios Equipos";
    }
  },
  mounted() {
    this.listar(1,this.buscar,this.criterio);
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
@media (min-width: 600px) {
  .btnagregar {
    margin-top: 2rem;
  }
}
</style>
