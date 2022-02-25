<div wire:ignore.self class="content-wrapper" style="min-height: 1203.31px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <nav class="main-header navbar botonBar navbar-light" id="navBar">
        
        <div>
            <h4  class="" style="margin-left: 10px;margin-top: 10px;color: white"><i class="far fa-calendar-alt icoTitle" style="margin-right: 10px"></i>{{ $nombreComponente }}.- {{ $tituloPagina }}<span id="titulomodalWl" style="color: white"></span></h4>
        </div>
        <div id="divHeader1" style="display: {{ ($agregarModificar?'none':'inline')  }};">
          <div class="row " >
            {{-- Btn Incluir --}}
            <div class="col">
              <button type="button" class="btn btn-outline-info btn-sm waves-effect" tabla="" onclick="Incluir('Crear Nuevo')" id="btnIncluir"data-toggle="tooltip" title="Crear Usuario"><i class="fas fa-user-plus text-white"></i></button> 
            </div>
            {{-- Btn Exportar Excel--}}
            <div class="col" style="margin-left: -20px;">
              <button type="button" class="btn btn-outline-info btn-sm waves-effect"id="ExportarExcel"data-toggle="tooltip" title="Exportar Excel"><i class="fas fa-file-export text-white"></i></button> 
            </div>
            {{-- <div class="col">
               <button type="button" id="borrarSelec" class="btn btn-outline-danger"><i class="far fa-trash-alt" style="margin-right: 10px"></i>Borrar Selección</button>
            </div> --}}
          </div>
        </div>

        <div id="divBtnAgregarModificar" style="display: {{ ($agregarModificar?'inline':'none')  }};">
          <div class="row " >
            {{-- Btn Regresar--}}
            <div class="col" id="divBtnCerrar">
              <button type="button" class="btn btn-outline-info cerrar btn-sm" data-toggle="tooltip" title="Regresar"><i class="fas fa-reply text-white"></i></button>
            </div>
            {{-- Btn Grabar --}}
            <div class="col" style="margin-left: -20px;">
              <button type="button" onclick="Grabar({{ ($idSelect > 0?"'Update'":"'Store'")}})" class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="Grabar usuario"><i class="far fa-save text-white"></i></button>
            </div>
          </div>
        </div>
        {{-- <div class="md-progress progressBarIndeterminate">
          <div class="indeterminate"></div>
        </div> --}}
      </nav>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" >
                        
                        <!-- /.card-header -->
                        <div  class="card-body" id="divCardBody" style="display: {{ ($agregarModificar?'none':'inline')  }};">
                            <h6 class="text-center text-warning" wire:loading><i class="fas fa-spin fa-spinner"></i> Procesando...</h6>
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    
                                        @include('comunes.buscarBox')
                                    
                                    <div class="col-sm-12 col-md-8" >
                                         {{ $usuarios->links() }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="tablaUsuarios" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                            <thead>
                                                <tr style="height:2rem">
                                                    <th wire:click.prevent="Sort('name')" class="text-center {{ $classSortC_1 }}">Nombre</th>
                                                    <th wire:click.prevent="Sort('username')" class="text-center {{ $classSortC_2}}">Usuario</th>
                                                    <th wire:click.prevent="Sort('email')" class="text-center {{ $classSortC_3}}">Email</th>
                                                    <th wire:click.prevent="Sort('rol')" class="text-center {{ $classSortC_4}}">Rol</th>
                                                    <th wire:click.prevent="Sort('estado')" class="text-center {{ $classSortC_5}}">Estado</th>
                                                    <th class="text-center">ACCIONES</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($usuarios as $user)
                                                    <tr>
                                                        <td valign="center"><h6>{{ $user->name }}</h6></td>
                                                        <td valign="center"><h6>{{ $user->username }}</h6></td>
                                                        <td valign="center"><h6>{{ $user->email }}</h6></td>
                                                        <td valign="center" class="text-center"><h6>
                                                            <span class="badge {{ $user->estado == 'ACTIVO'?'badge-success':'badge-danger'}} text-uppercase">{{ $user->rol }}</span></h6>
                                                        </td>
                                                        <td valign="center" class="text-center"><h6><span class="badge {{ $user->estado == 'ACTIVO'?'badge-success':'badge-danger'}} text-uppercase">{{ $user->estado }}</span></h6></td>

                                                        <td class="text-center" valign="center">
                                                            <buttom wire:click.prevent="Edit({{ $user->id }})" class="mtmobile" title="Editar" style="border: none !important;background-color: transparent;font-size:16px;margin-right: 10px ;" data-toggle="tooltip" title="Modificar">
                                                                <i class="fas fa-user-edit colorIcoEdit"></i>
                                                            </buttom>
                                                            <buttom onClick="Confirmar({{ $user->id }},'{{ $user->nombreCategoria }}')" class="mtmobile" title="Borrar" style="border: none !important;font-size:16px;" data-toggle="tooltip" title="Eliminar Registro">
                                                                <i class="fas fa-trash colorIcoDelete"></i>
                                                            </buttom>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="card card-cascade cascading-admin-card user-card" id="divAgregarModificar" style="display: {{ ($agregarModificar?'bloc':'none')  }};">
                        <div class="admin-up d-flex justify-content-start">
                            <i class="fas fa-user-edit info-color py-4 mr-3 z-depth-2"></i>
                            <div class="data">
                                <h5 class="font-weight-bold dark-grey-text">Datos - <span class="text-muted">Usuarios</span></h5>
                            </div>
                            <h6 class="text-center text-secondary" wire:loading><i class="fas fa-spin fa-spinner"></i> Procesando...</h6>
                        </div>
                        <div class="row" >
                            <div class="col-sm-12">
                                <div class="card-body" id="Cbody" >
                                    <form id="fUsuario">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="md-form">
                                                    <i class="fas fa-user prefix"></i>
                                                    <label for="username" class="active" >Nombre de usuario</label>
                                                    <input type="text" name="username" id="username" class="form-control">
                                                    @error('username') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="md-form">
                                                    <i class="fas fa-id-card prefix"></i>
                                                    <label for="name" class="active" >Nombre y Apellido</label>
                                                    <input type="text" name="name" id="name" class="form-control">
                                                    @error('name') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="md-form">
                                                    <i class="fas fa-envelope prefix"></i>
                                                    <label for="email" data-error="wrong" data-success="right" class="active">Ingrese su Correo</label>
                                                    <input type="text" name="email" id="email" class="form-control">
                                                    @error('email') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6">
                                                <div class="md-form">
                                                    <i class="fas fa-lock prefix"></i>
                                                    <label for="password" class="active" >Ingrese Clave</label>
                                                    <input type="password" name="password" id="password" class="form-control">
                                                    @error('password') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                    <label for="estado" class="active">Estado</label>
                                                    <select name="estado" id="estado" class="mdb-select md-form colorful-select dropdown-info" id="estado">
                                                        @foreach ($usuarios->opciones as $option)
                                                            @if ($option != 'DELETE')
                                                                <option Value="{{ $option }}">{{ $option }}</option>
                                                            @endif
                                                            
                                                        @endforeach
                                                    </select>
                                                    @error('estado') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
                                            </div>
                                            
                                            <div class="col-sm-12 col-md-6">
                                                    <label for="role" class="active">Roles</label>
                                                    <select name="role" id="role" class="mdb-select md-form colorful-select dropdown-info" id="roles">
                                                        <option selected value="-">-</option>
                                                        @foreach ($roles as $rol)
                                                            <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('role') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
                                                
                                            </div>
                                        </div>
                                    </form>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
            <!-- /.container-fluid -->
    </section>
        <!-- /.content -->

    
    <script>

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
    
            
        })

       var estado = '';
       var role = '';

        document.addEventListener('DOMContentLoaded', function(e){

            window.livewire.on('user-added', msg =>{
                estado = '';
                role = '';
                $("#fUsuario")[0].reset()
                $(".cerrar").trigger('click')
                Noty(msg,1);
            } )
            window.livewire.on('user-updated', msg =>{
               role = '';
               estado = '';
               $("#fUsuario")[0].reset()
                Noty(msg,1);
               $(".cerrar").trigger('click')
                
            } )
            window.livewire.on('user-deleted', msg =>{
                Noty(msg,1);
            } )
            window.livewire.on('hide-modal', msg =>{
                $(".cerrar").trigger('click')
            } )
            window.livewire.on('show-modal', msg =>{
                $('label').addClass('active')
                $("#fUsuario").view(msg);
                estado = msg.estado;
                role = msg.rol;
                Incluir('Modificar', msg)
            } )

            window.livewire.on('user-error', msg =>{
                swal({
                    title:'ERROR',
                    text:msg,
                    type:'error',
                    showCancelButton: false,
                     confirmButtonClass: 'btn btn-info',
                     confirmButtonText: 'ACEPTAR'
                }).then(function(result){
                    
                })
            } )

            
            window.livewire.on('reload', msg =>{
                $('.mdb-select').materialSelect();
                $("#estado").val(estado)
                $("#role").val(role)
            } )            
        
        });

    function Confirmar(id, nombre){
        swal({
            title:'CONFIRMAR',
            text:'¿Esta seguro de eliminar el usuario '+nombre+'?',
            type:'warning',
            showCancelButton: true,
             confirmButtonClass: 'btn btn-danger',
             cancelButtonText: 'Cancelar',
             cancelButtonClass: 'btn btn-info',
             confirmButtonText: 'Eliminar Registro!'
        }).then(function(result){
            if (result.value) {
                console.log("result", result);
                window.livewire.emit('deleteRow',id);
                //swal.close()
            }
        })
    }

    function Grabar(medotod){
        
        data = serializeFormJSON($("#fUsuario"))
        console.log("data", data);
        
        window.livewire.emit(medotod,data);
        
    }
    
    function serializeFormJSON(elemento){
      var o = {};
      var a = elemento.serializeArray();
      $.each(a, function () {
          const regex = /[\W]/;
          const subst = '';
          this.name = this.name.replace(regex, subst);
          if (o[this.name]) {
              if (!o[this.name].push) {
                  o[this.name] = [o[this.name]];
              }
              o[this.name].push(this.value || '');
          } else {
              o[this.name] = this.value || '';
          }
      });
      return o
    }
    function Incluir(TipoVentana, datos = ''){
        
        $( "#divHeader1").hide('blind');
        $( "#divCardBody").hide('blind');
        $("#divAgregarModificar").show('blind');
        $("#divBtnAgregarModificar").show('blind');
        $('.mdb-select').materialSelect();
        window.livewire.emit('AgreMod');
        
    }
    

    $(".cerrar").on('click',function(){
        $("#divAgregarModificar").hide('blind');
        $("#divBtnAgregarModificar").hide('blind');
        $( "#divHeader1").show('blind');
        $( "#divCardBody").show('blind');
        window.livewire.emit('resetUI');
    })

    $(function () {
    
    $('#tablaUsuarios').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</div>
