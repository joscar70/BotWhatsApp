</div>
      <div class="modal-footer">
        <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn textx-info" data-dismiiss="modal">CERRAR</button>
        @if ($idSelect < 1)
          <button type="button" wire:click.prevent="Store()" class="btn btn-dark close-modal">GUARDAR</button>
        @else
          <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal">ACTUALIZAR</button>
        @endif
        
      </div>
    </div>
  </div>
</div>