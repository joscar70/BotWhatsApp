<div wire:ignore.self class="modal fade" id="ModalPermisos" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h5 class="modal-title text-white">{{ $idSelect > 0 ?'Editar':'Crear'}} {{ $nombreComponente }}</h5>
				<h6 class="text-center text-warning" wire:loading><i class="fas fa-spin fa-spinner"></i> Procesando...</h6>
			</div>
			<div class="modal-body">
				<div class="row">

					
					<div class="col-sm-12">
						@if ($idSelect == 0)
							<div class="row">
								<div class="col-sm-11">
									<div class="md-form">
										<input type="text" wire:model.lazy="nombreModulo" class="form-control" id="nombrePermiso">
										<label for="nombrePermiso " class="active" >Agregar Modulo</label>
										@error('nombreModulo')
											<sapn class="text-danger">{{ $message }}</sapn>
										@enderror
									</div>
								</div>
								<div class="col-sm-1">
									<button wire:click.prevent="AgergarModulo()" type="button" class="btn btn-outline-info botonModal"><i class="fas fa-plus"></i> </button>
								</div>
							</div>
						@endif
						<div class="md-form">
							<label for="modulo" class="active">modulos</label>
	                        <select wire:model="idModulo" class="mdb-select md-form colorful-select dropdown-info" id="modulo">
	                            <option selected value="-">-</option>
	                            @foreach ($modulos as $m)
	                                <option value="{{ $m->id }}">{{ $m->nombreModulo }}</option>
	                            @endforeach
	                        </select>
	                        @error('idModelo') <sapn class="text-danger er">{{ $message }}</sapn>@enderror	
						</div>
						
						<div class="md-form">
							<input type="text" wire:model.lazy="nombrePermiso" class="form-control" id="nombrePermiso">
							<label for="nombrePermiso " class="active" >Permiso</label>
							@error('nombrePermiso') <sapn class="text-danger">{{ $message }}</sapn>@enderror
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" wire:click.prevent="resetUI()" class="btn btn-outline-dark close-btn textx-info botonModal" data-dismiiss="modal">CERRAR</button>
				@if ($idSelect < 1)
					<button type="button" wire:click.prevent="CreatePermiso()" class="btn btn-outline-success close-modal botonModal" id="btnGrabar">GUARDAR</button>
				@else
					<button type="button" wire:click.prevent="UpdatePermiso()" class="btn btn-outline-success close-modal botonModal" id="btnGrabar">ACTUALIZAR</button>
				@endif

			</div>
		</div>
	</div>
</div>
<script>
	 $("#nombrePermiso").keypress(function(e) {
        //no recuerdo la fuente pero lo recomiendan para
        //mayor compatibilidad entre navegadores.
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	$("#btnGrabar").trigger('click');
        }
    });
</script>