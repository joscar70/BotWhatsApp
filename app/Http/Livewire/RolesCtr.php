<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User;
use DB;

class RolesCtr extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $roleName, $buscar, $idSelect, $tituloPagina, $nombreComponente, $role = 0, $checkAll = false;
    public $columnSort = 'name', $sortDirection = 'asc', $classSortC_1 = 'sorting_asc',$classSortC_2= 'sorting';
    private $paginacion = 50;
    protected $listeners = [
        'deleteRow' => 'Destroy',
        'RevocarTodo' => 'RevocarTodo'
    ];

    public function mount(){
        $this->tituloPagina = 'Listado';
        $this->nombreComponente = 'Role';
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $query = Role::where('name','like', '%'.$this->buscar.'%')->orderBy($this->columnSort,$this->sortDirection)->paginate($this->paginacion);
        }else{
            $query = Role::orderBy($this->columnSort,$this->sortDirection)->paginate($this->paginacion);
        }
        $queryP = (object)array();
        if ($this->role > 0) {
            $queryP = Permission::join('modulos as a','a.id','permissions.idModulo')->selectRaw("permissions.id, a.nombreModulo, GROUP_CONCAT(CONCAT(permissions.name,'-',permissions.id,'_checked=0')) AS permisos")->groupBy("a.nombreModulo")->orderBy($this->columnSort,$this->sortDirection)->get();
            $role = Role::find($this->role);
            foreach ($queryP as $key => $value) {
                $permisos = collect(explode(',',$value->permisos));
                
                foreach ($permisos as $key2 => $permiso) {
                    
                    $tienePermiso = $role->hasPermissionTo(substr($permiso, 0, strpos($permiso, '-')));
                    if ($tienePermiso) {
                        $permisos[$key2] = preg_replace('/checked=0/m', 'checked=1', $permiso);
                        $this->checkAll = true;
                    }else{
                        $this->checkAll = false;
                    }
                }

                 $queryP[$key]->permisos =$permisos->sort();
            }


            //$this->permisos = Permission::select('id','name', DB::raw('0 as checked'))->orderBy('name', 'asc')->paginate($this->paginacion);

            $lista = Permission::join('role_has_permissions as rp','rp.permission_id','permissions.id')
                ->where('role_id',$this->role)->pluck('permissions.id')->toArray();

            $this->oldPermisos = $lista;
                
        }

        return view('livewire.Roles.roles',['roles' => $query, 'permisos' => $queryP])->extends('home')
        ->section('content');
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function Role($idRole)
    {
        $this->role = $idRole;
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

    public function CreateRol()
    {
        $reglas = ["roleName" => "required|min:2|unique:roles,name"];
        $mensajes = [
            "roleName.required" => "¡Debe ingresar un nombre para el rol!",
            "roleName.unique" => "El rol ya existe",
            "roleName.min" => "El rol debe tener al manos 2 caracteres"
        ];

        $this->validate($reglas, $mensajes);

        $rol = Role::Create([
            "name" => strtoupper($this->roleName)
        ]);
        $rol->save();
        $this->emit('rol-added','Rol registrdo correctamente');
        $this->resetUI();

    }

    public function Edit(Role $rol)
    {
        $this->roleName = $rol->name;
        $this->idSelect = $rol->id;;

        $this->emit('show-modal', 'show modal');
    }

    public function UpdateRol()
    {
         $reglas = ["roleName" => "required|min:2|unique:roles,name,{$this->idSelect}"];
        $mensajes = [
            "roleName.required" => "¡Debe ingresar un nombre para el rol!",
            "roleName.unique" => "El rol ya existe",
            "roleName.min" => "El rol debe tener al manos 2 caracteres"
        ];

        $this->validate($reglas, $mensajes);
        $rol = Role::find($this->idSelect);
        $rol->update([
            "name" => strtoupper($this->roleName)
        ]);
        $rol->save();
        $this->emit('rol-updated','Rol actualizado correctamente');
        $this->resetUI();
    }

    public function resetUI()
    {
        $this->roleName = '';
        $this->idSelect = 0;
        $this->buscar = '';
        $this->resetValidation();
        $this->emit('hide-modal','');

    }

    public function Destroy(Role $rol){
        $permisosCount = Role::find($rol->id)->Permissions->count();
        
        if ($permisosCount > 0) {
            $this->emit('role-error','No se piuede eliminar el rol, porque tiene permisos asociados');
            return;
        }
        $rol->delete();
        $this->emit('rol-delete','Rol eliminado correctamente');
    }

    public function asignarRevocarPermiso($estado, $nombrePermiso)
    {
        if ($this->role > 0) {
            $role = Role::find($this->role);

            if ($estado) {
                $role->givePermissionTo($nombrePermiso);
                $this->emit('permi',"Permiso ".strtoupper($nombrePermiso)." asignado para el rol $role->name");
                return;
            }else{
                $role->revokePermissionTo($nombrePermiso);
                $this->emit('permi',"Permiso ".strtoupper($nombrePermiso)."? revocado para el rol $role->name");
                return;
            }
        }
        $this->emit('asig-error','Debe seleccionar un Rol');


    }


    public function AsignarRevocar($estado)
    {
        $this->checkAll = $estado;
        if ($estado) {
             $role = Role::find($this->role);
            $permisos = Permission::pluck('id')->toArray();
            $role->syncPermissions($permisos);
            $this->emit('permi',"Se han asignado todos los permisos para el rol $role->name");
            return;
        }else{
             $role = Role::find($this->role);
            $role->syncPermissions([0]);
            $this->emit('permi',"Se han revocado todos los permisos para el rol $role->name");
            return;
        }

    }
}
