<div class="content-wrapper" style="min-height: 1203.31px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <nav class="main-header navbar botonBar navbar-light" id="navBar">
        
        <div>
            <h4  class="" style="margin-left: 10px;margin-top: 10px;color: white"><i class="far fa-calendar-alt icoTitle" style="margin-right: 10px"></i>{{ $nombreComponente }}.- {{ $tituloPagina }}<span id="titulomodalWl" style="color: white"></span></h4>
        </div>
        <div id="divHeader1" style="display: inline;">
          <div class="row " >
            {{-- Btn Incluir --}}
            <div class="col">
              <button type="button" class="btn btn-outline-info btn-sm waves-effect" tabla="" data-toggle="modal" data-target="#ModalPermisos" data-toggle="tooltip" title="Crear Rol" id="btnIncluir"><i class="fas fa-user-tag text-white"></i></button>
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

        <div id="divBtnAgregarModificar" style="display: none">
          <div class="row " >
            {{-- Btn Regresar--}}
            <div class="col" id="divBtnCerrar">
              <button type="button" class="btn btn-outline-info cerrar btn-sm" data-toggle="tooltip" title="Regresar"><i class="fas fa-reply text-white"></i></button>
            </div>
            {{-- Btn Grabar --}}
            <div class="col" style="margin-left: -20px;">
              <button type="button" id="grabarWl" class="btn btn-outline-info btn-sm" data-toggle="tooltip" title="Grabar usuario"><i class="far fa-save text-white"></i></button>
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
                    <div class="card">
                        
                        <!-- /.card-header -->
                        <div class="card-body" id="divCardBody">
                            <div class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    
                                        @include('comunes.buscarBox')
                                    
                                    <div class="col-sm-12 col-md-8" >
                                         {{ $permisos->links() }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="tablaUsuarios" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                            <thead>
                                                <tr style="height:2rem">
                                                    <th wire:click.prevent="Sort('nombreModulo')" class="text-center {{ $classSortC_1 }}">Módulo</th>
                                                    <th wire:click.prevent="Sort('name')" class="text-center {{ $classSortC_2}}">Descripción </th>
                                                    <th class="text-center">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permisos as $p)
                                                    <tr>
                                                        <td valign="center"><h6>{{ $p->nombreModulo }}</h6></td>
                                                        <td valign="center">
                                                            <h6>
                                                                <ul class="nav nav-pills nav-sidebar  nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">
                                                                    <li class="nav-item has-treeview menu-open">
                                                                    
                                                                        <ul class="">
                                                                            @foreach ($p->permisos as $pp)
                                                                            <li class="">{{ substr($pp, 0, strpos($pp, '-')) }}</li>
                                                                            @endforeach    
                                                                        </ul>
                                                                    </li>
                                                                </ul>
                                                            </h6>
                                                        </td>
                                                        <td class="text-center" valign="center">
                                                            <ul class="">
                                                                @foreach ($p->permisos as $pp)
                                                                    <li class="" style="list-style-type: none;">
                                                                         <buttom wire:click="Edit({{ substr($pp, strpos($pp, '-')+1)}},{{ $p->idModulo }})" class="mtmobile" title="Editar" style="border: none !important;background-color: transparent;font-size:16px;margin-right: 10px ;" data-toggle="tooltip" title="Modificar">
                                                                            <i class="far fa-edit colorIcoEdit"></i>
                                                                        </buttom>
                                                                        <buttom onClick="Confirmar({{substr($pp, strpos($pp, '-')+1) }}')" class="mtmobile" title="Borrar" style="border: none !important;font-size:16px;" data-toggle="tooltip" title="Eliminar Registro">
                                                                            <i class="fas fa-trash colorIcoDelete"></i>
                                                                        </buttom>
                                                                    </li>
                                                                @endforeach  
                                                            </ul>
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
                    
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
           @include('livewire.Permisos.form')
    </section>
        <!-- /.content -->
    <script>
        

        document.addEventListener('DOMContentLoaded', function(e){
            

           window.livewire.on('permiso-added', msg =>{
                $("#ModalPermisos").modal('hide');
                Noty(msg);
            } )
            window.livewire.on('permiso-updated', msg =>{
                $("#ModalPermisos").modal('hide');
                Noty(msg);
            } )
            window.livewire.on('permiso-deleted', msg =>{
                Noty(msg);
            } )
            window.livewire.on('permiso-existe', msg =>{
                Noty(msg,2);
            } )
            window.livewire.on('permiso-error', msg =>{
                Noty(msg,4);
            } )
            window.livewire.on('hide-modal', msg =>{
                $("#ModalPermisos").modal('hide');
            } )
            window.livewire.on('show-modal', msg =>{
                $("#ModalPermisos").modal('show');
            } )

            window.livewire.on('reload', msg =>{
                console.log("msg", msg);
                $('.mdb-select').materialSelect();
            } )

            window.livewire.on('add-modulo', msg =>{
                $('#modulo option:contains("'+msg+'")').attr('selected', true);
            } )

            
            
        });

        $(document).on('show.bs.modal','#ModalPermisos', function () {
          $('.mdb-select').materialSelect();
        })

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
        $('[data-toggle="tooltip"]').tooltip();

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

        document.addEventListener('keydown', (event) => {
            const keyName = event.key;
            if (keyName == 'F4') {
                if ($("#btnIncluir")) {
                    $("#btnIncluir").trigger('click');
                    $("#nombrePermiso").focus();
                }
            }
            if (keyName == 'F6') {

            }
            if (keyName == 'F9') {

            }
        });

    </script>
</div>



