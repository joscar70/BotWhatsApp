{{--  wire:ignore.self esta directiva evita que se cierre el modal al renderisarse
      wire:loading estadirectiva hace que cuando el server este trabajamdo mueste el mensaje --}}
<div wire:ignore.self class="modal" id="ModalComun" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white"><b>{{ $nombreComponente }}</b> | {{ $idSelect > 0 ?'EDITAR':'CREAR'}}</h5>
        <h6 class="text-center text-warning" wire:loading><i class="fas fa-spin fa-spinner"></i> Procesando...</h6>
      </div>
      <div class="modal-body">