@include('comunes.modalHead')

<div class="row">
	<div class="col-sm-12 col-md-8">
		<div class="form-group">
			<label>Nombre Usuario</label>
			<input type="text" wire:model.lazy="name" class="form-control">
			@error('name') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Teléfono</label>
			<input type="text" wire:model.lazy="telefono" class="form-control">
			@error('telefono') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Email</label>
			<input type="text" data-type="currency" wire:model.lazy="email" class="form-control">
			@error('email') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
		</div>
	</div>

	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Clave de usuario</label>
			<input type="password" wire:model.lazy="password" class="form-control">
			@error('password') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Estado</label>
			<select wire:model.lazy="estado" class="form-control" style="font-size: 12px;">
				<option Value="-" selected>-</option>
				@foreach ($usuarios->opciones as $option)
					@if ($option != 'DELETE')
						<option Value="{{ $option }}">{{ $option }}</option>
					@endif
					
				@endforeach
			</select>
			@error('alerta') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
		</div>
	</div>
	
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Roles</label>
			<select wire:model.lazy="role" class="form-control" style="font-size: 12px;">
				<option Value="-" selected>-</option>
				@foreach ($roles as $rol)
					<option Value="{{ $rol->id }}">{{ $rol->name }}</option>
				@endforeach
			</select>
			@error('role') <sapn class="text-danger er">{{ $message }}</sapn>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-12">
		<div class="form-group custom-file">
			<input type="file" class="custom-file-input form-control" wire:model="imagen" accept="image/x-png, image/gif, image/jpeg">
			<label for="" class="custom-file-label">Imágen {{ $imagen }}</label>
			@error('imagen') <sapn class="text-danger er">{{ $message }}</sapn> @enderror
		</div>
	</div>
</div>

@include('comunes.modalFooter')