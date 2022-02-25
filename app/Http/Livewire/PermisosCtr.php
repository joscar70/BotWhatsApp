<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Modulo;
use DB;

class PermisosCtr extends Component
{
    use WithFileUploads;
    use WithPagination;

    /**
     * Variable Publica para el Modelo
     */
    public $nombrePermiso, $buscar, $idSelect, $tituloPagina, $nombreComponente, $idModulo, $nombreModulo;
    public $columnSort = 'nombreModulo', $sortDirection = 'asc', $classSortC_1 = 'sorting_asc',$classSortC_2= 'sorting';

    private $paginacion = 50;

    /**
     * Declaracion de Eventos
     */
    protected $listeners = ['deleteRow' => 'Destroy'];


    public function updated()
    {
            

        $this->emit('reload', $this->idModulo);
    }

    public function mount(){
        $this->tituloPagina = 'Listado';
        $this->nombreComponente = 'Permisos';
        $this->emit('reload');
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $query = Permission::join('modulos as a','a.id','permissions.idModulo')->where('name','like', '%'.$this->buscar.'%')->selectRaw("permissions.id, a.nombreModulo,GROUP_CONCAT(CONCAT(permissions.name,'-',permissions.id)) AS permisos, a.id as idModulo")->orderBy($this->columnSort,$this->sortDirection)->groupBy("a.nombreModulo")->paginate($this->paginacion);
        }else{
            $query = Permission::join('modulos as a','a.id','permissions.idModulo')->selectRaw("permissions.id, a.nombreModulo, GROUP_CONCAT(CONCAT(permissions.name,'-',permissions.id)) AS permisos, a.id as idModulo")->groupBy("a.nombreModulo")->orderBy($this->columnSort,$this->sortDirection)->paginate($this->paginacion);
        }
        foreach ($query as $key => $value) {
            $permisos = collect(explode(',',$value->permisos));
            $permisos = $permisos->sort();
            $query[$key]->permisos = $permisos;
            

        }
           
        return view('livewire.Permisos.permisos',['permisos' => $query, 'modulos' => Modulo::orderBy('nombreModulo')->get()])->extends('home')
        ->section('content');
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function Sort($columna)
    {
        $this->columnSort = $columna;
        $this->classSortC_1  = 'sorting';
        $this->classSortC_2  = 'sorting';
        
        switch ($columna) {
            case 'id':

                $this->classSortC_1  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
            case 'name':
                $this->classSortC_2  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
           
        }

        $this->sortDirection = ($this->sortDirection == 'asc'?'desc':'asc');
    }

    public function CreatePermiso()
    {

        $reglas = [
            "nombrePermiso" => "required|min:2|unique:permissions,name",
            "idModulo" => "required|not_in:-"
        ];
        $mensajes = [
            "nombrePermiso.required" => "¡Debe ingresar un nombre para el Permiso!",
            "nombrePermiso.unique" => "¡El Permiso ya existe!",
            "nombrePermiso.min" => "¡El Permiso debe tener al manos 2 caracteres!",
            "idModulo" => "¡Debe Seleccionar un Modulo paar el Permiso!"
        ];
        $this->validate($reglas, $mensajes);

        $permiso = Permission::Create([
            "name" => $this->nombrePermiso,
            "idModulo" => $this->idModulo
            
        ]);
        $permiso->save();
        $this->emit('permiso-added','Permiso registrdo correctamente');
        $this->resetUI();

    }

    public function Edit(Permission $permiso, $idModulo)
    {
        $this->nombrePermiso = $permiso->name;
        $this->idSelect = $permiso->id;
        $this->idModulo = $idModulo;
        $this->emit('show-modal', 'show modal');
    }

    public function UpdatePermiso()
    {
         $reglas = [
            "nombrePermiso" => "required|min:2|unique:permissions,name,{$this->idSelect}",
            "idModulo" => "required|not_in:-"
        ];
        $mensajes = [
            "nombrePermiso.required" => "¡Debe ingresar un nombre para el Permiso!",
            "nombrePermiso.unique" => "El Permiso ya existe",
            "nombrePermiso.min" => "El Permiso debe tener al manos 2 caracteres",
            "idModulo" => "¡Debe Seleccionar un Modulo paar el Permiso!"
        ];
        $this->emit('reload');
        $this->validate($reglas, $mensajes);
        $permiso = Permission::find($this->idSelect);
        $permiso->update([
            "name" => $this->nombrePermiso,
            "idModulo" => $this->idModulo
        ]);
        $permiso->save();
        $this->emit('permiso-updated','Permiso actualizado correctamente');
        $this->resetUI();
    }

    public function resetUI()
    {
        $this->nombrePermiso = '';
        $this->idSelect = 0;
        $this->buscar = '';
        $this->resetValidation();
        $this->emit('hide-modal','');
        
    }

    public function Destroy(Permission $permiso){
        $rolesCount = Permission::find($permiso->id)->getRoleNames()->count();
        
        if ($rolesCount > 0) {
            $this->emit('permiso-error','No se piuede eliminar el permiso, porque tiene roles asociados');
            return;
        }
        $permiso->delete();
        $this->emit('permiso-delete','Permiso eliminado correctamente');
    }

    public function AgergarModulo()
    {
        $reglas = [
            "nombreModulo" => "required|min:5|unique:modulos,nombreModulo"
        ];
        $mensajes = [
            "nombreModulo.required" => "¡Debe ingresar un nombre para el Modulo!",
            "nombreModulo.unique" => "¡El Modulo ya existe!",
            "nombreModulo.min" => "¡El nombre del Modulo debe tener al manos 5 caracteres!",
        ];
        $this->emit('reload');
        $this->validate($reglas, $mensajes);
        if ($this->nombreModulo != '') {
            Modulo::insert(['nombreModulo' => $this->nombreModulo]);
            $nombreModulo = $this->nombreModulo;
            $this->emit('add-modulo',$nombreModulo);
            $this->nombreModulo = '';
            
        }
    }

}
