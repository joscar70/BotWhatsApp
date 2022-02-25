<div wire:ignore.self class="modal fade" id="ModalRol" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ $idSelect > 0 ?'Modificar ':'Crea '}}{{ $nombreComponente }}</h5>
				<h6 class="text-center text-warning" wire:loading><i class="fas fa-spin fa-spinner"></i> Procesando...</h6>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="md-form">
							<i class="far fa-edit prefix"></i>
							<input type="text" wire:model.lazy="roleName" class="form-control" id="roleName">
							<label for="roleName " class="active" >Rol</label>
							@error('roleName') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
						</div>
						
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" wire:click.prevent="resetUI()" class="btn btn-outline-dark" data-dismiiss="modal">CERRAR</button>
				@if ($idSelect < 1)
					<button type="button" wire:click.prevent="CreateRol()" class="btn btn-outline-success" id="btnGrabar">GUARDAR</button>
				@else
					<button type="button" wire:click.prevent="UpdateRol()" class="btn btn-outline-success" id="btnGrabar">ACTUALIZAR</button>
				@endif

			</div>
		</div>
	</div>
</div>
<script>
	 $("#roleName").keypress(function(e) {
        //no recuerdo la fuente pero lo recomiendan para
        //mayor compatibilidad entre navegadores.
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
        	$("#btnGrabar").trigger('click');
        }
    });
</script>