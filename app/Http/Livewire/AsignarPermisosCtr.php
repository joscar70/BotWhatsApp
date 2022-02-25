<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use DB;

class AsignarPermisosCtr extends Component
{
    use WithPagination;

    public $role, $componenteName, $permisoSelect = [], $oldPermiso = [], $tituloPagina, $nombreComponente;
    public $columnSort = 'name', $sortDirection = 'asc', $classSortC_1 = 'sorting_asc',$classSortC_2= 'sorting';
    private $paginacion;
    public function mount()
    {
        $this->role = '-';
        $this->componenteName = 'Asignar Permiso';

    }
    public function render()
    {
        $queryP = Permission::join('modulos as a','a.id','permissions.idModulo')->selectRaw("permissions.id, a.nombreModulo, GROUP_CONCAT(CONCAT(permissions.name,'-',permissions.id,'_checked=0')) AS permisos")->groupBy("a.nombreModulo")->orderBy($this->columnSort,$this->sortDirection)->get();
        if ($this->role > 0) {
            
            $role = Role::find($this->role);
            foreach ($queryP as $key => $value) {
                $permisos = collect(explode(',',$value->permisos));
                foreach ($permisos as $key2 => $permiso) {
                    $tienePermiso = $role->hasPermissionTo(substr($permiso, 0, strpos($permiso, '-')));
                    if ($tienePermiso) {
                        $permisos[$key] = preg_replace('/checked=0/m', 'checked=1', $permiso);
                    }
                }

                 $queryP[$key]->permisos = $permisos;
            }


            //$this->permisos = Permission::select('id','name', DB::raw('0 as checked'))->orderBy('name', 'asc')->paginate($this->paginacion);

            $lista = Permission::join('role_has_permissions as rp','rp.permission_id','permissions.id')
                ->where('role_id',$this->role)->pluck('permissions.id')->toArray();

            $this->oldPermisos = $lista;
                
        }
        $permisos = Permission::select('id','name', DB::raw('0 as checked'))->orderBy('name', 'asc')->paginate($this->paginacion);

        if ($this->role != '-') {
            $lista = Permission::join('role_has_permissions as rp','rp.permission_id','permissions.id')
            ->where('role_id',$this->role)->pluck('permissions.id')->toArray();

            $this->oldPermisos = $lista;
        }
        

        if ($this->role != '-') {

            foreach ($permisos as $permiso) {
                $role = Role::find($this->role);
                $tienePermiso = $role->hasPermissionTo($permiso->name);
                if ($tienePermiso) {
                    $permiso->checked = 1;
                }
                $cant = DB::select("SELECT COUNT(*)  AS 'cant' FROM (SELECT COUNT(*) FROM roles a LEFT JOIN role_has_permissions b ON a.id = b.role_id JOIN permissions c ON b.permission_id = c.id WHERE c.id = ? GROUP BY a.id) AS z", [$permiso->id]);
                $permiso->totalRoles = $cant[0]->cant;
            }
        }



        return view('livewire.AsignarPermisos.asignar-permisos',[
            "roles" => Role::orderBy('name','asc')->get(), 
            'permisos' => $permisos
        ])->extends('layouts.temas.app')
        ->section('content');
    }

    

    public function asignarPermiso($estado, $nombrePermiso)
    {
        if ($this->role != '-') {
            $role = Role::find($this->role);

            if ($estado) {
                $role->givePermissionTo($nombrePermiso);
                $this->emit('permi',"Permiso asignado para el rol $role->name");
                return;
            }else{
                $role->revokePermissionTo($nombrePermiso);
                $this->emit('permi',"Permiso revocado para el rol $role->name");
                return;
            }
        }
        $this->emit('asig-error','Debe seleccionar un Rol');


    }
}
