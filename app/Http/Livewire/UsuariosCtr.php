<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;
use Exception;

class UsuariosCtr extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $name, $username, $email, $estado, $password, $buscar, $fileUpload, $role, $idSelect, $tituloPagina, $nombreComponente, $passwordOld = '';
    private $paginacion = 2;
    public $columnSort = 'name', $sortDirection = 'asc', $classSortC_1 = 'sorting_asc',$classSortC_2= 'sorting',$classSortC_3= 'sorting',$classSortC_4= 'sorting',$classSortC_5= 'sorting', $agregarModificar = false;
    protected $listeners = [
        'deleteRow' => 'Destroy',
        'Store' => 'Store',
        'Update' => 'Update',
        'AgreMod' => 'AgreMod',
        'resetUI' => 'resetUI'

    ];


    public function boot()
    {

        $this->emit('reload');
    }

    public function mount(){
        $this->tituloPagina = ($this->agregarModificar?($this->idSelect == 0?'Crear':'Modificar'):'');
        $this->nombreComponente = 'Usuarios';
        $this->estado = '-';
        
    }

    public function render()
    {
        if (strlen($this->buscar) > 0) {
            $query = User::where('name','like', '%'.$this->buscar.'%')->whereraw("estado != 'DELETE'")->orderBy($this->columnSort,$this->sortDirection)->paginate($this->paginacion);
        }else{
            $query = User::orderBy($this->columnSort,$this->sortDirection)->whereraw("estado != 'DELETE'")->paginate($this->paginacion);
        }

        $opciones = DB::select("CALL opcionesEnum(?,?,?)",[env("DB_DATABASE"),'users','estado']);
        $opciones = preg_replace('/\[|\]|"/m','', $opciones[0]->OPCIONES);
        $opciones = explode(',',$opciones);
        $query->opciones = (object)$opciones;
        return view('livewire.Usuarios.usuarios',['usuarios' => $query, "roles" => Role::orderBy('name','asc')->get()])->extends('home')
        ->section('content');
    }

    public function AgreMod($value='')
    {
        $this->agregarModificar = true;
        $this->tituloPagina = ($this->agregarModificar?($this->idSelect == 0?'Crear':'Modificar'):'');
        
    }

     public function Sort($columna)
    {
        $this->columnSort = $columna;
        $this->classSortC_1  = 'sorting';
        $this->classSortC_2  = 'sorting';
        $this->classSortC_3  = 'sorting';
        $this->classSortC_4  = 'sorting';
        $this->classSortC_5  = 'sorting';
        
        switch ($columna) {
            case 'name':

                $this->classSortC_1  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
            case 'username':
                $this->classSortC_2  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
            case 'email':
                $this->classSortC_3  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
            case 'rol':
                $this->classSortC_4  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
            case 'estado':
                $this->classSortC_5  = ($this->sortDirection == 'asc'?'sorting_desc':'sorting_asc');
                break;
            
        }

        $this->sortDirection = ($this->sortDirection == 'asc'?'desc':'asc');
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function Store($datos)
    {
        // try {
            $datos = (object)$datos;
            $this->name = $datos->name;
            $this->username = $datos->username;
            $this->email = $datos->email;
            $this->estado = $datos->estado;
            $this->role = $datos->role;
            $this->password = $datos->password;
            $reglas = [
                "name" => "required|min:3",
                "username" => "required|unique:users",
                "estado" => "required|not_in:-",
                "role" => "required|not_in:-",
                "password" => "required|min:3"

            ];
            $mensajes = [
                "name.required" => "¡Debe ingresar el Nombre completo  del Usuario!",
                "name.min" => "El Usuaurio debe tener al manos 3 caracteres",
                "username.required" => "Debe Ingresar un nombre de Usuario",
                "username.unique" => "Ya existe un Usuario",
                "estado.not_in" => "Debe seleccionar un estado",
                "role.not_in" => "Debe seleccionar un Rol para el Usuario",
                "password.required" => "Debe ingresar la CLAVE del Usuario",
                "password.min" => "La Clave debe tener al manos 3 caracteres"
            ];

               

            if ($this->validate($reglas, $mensajes)  != '') {
                $this->emit('user-valid','Error de Validación de datos');
            }

            $User = User::Create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'estado' => $this->estado,
                'rol' => $this->role,
                'password' =>  Hash::make($this->password)
            ]);


            if ($User->save()) {
                $User->syncRoles($this->role);
                $this->agregarModificar = false;
                $this->emit('user-added','Rol registrdo correctamente');
                $this->resetUI();
            }
        // } catch (Exception $e) {
        //     $this->resetValidation();
        //     $msg = $e->getMessage();
        //     if (str_contains($msg, '1062')) {
        //         $this->emit('user-error',"Ya existe un usuario con ese nombre");
        //     }else{
        //         $this->emit('user-error',$msg);
        //     }
            
        // }
        
        

    }

    public function resetUI()
    {
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->estado = '-';
        $this->role = '-';
        $this->password = '';
        $this->idSelect = 0;
        $this->buscar = '';
        $this->resetValidation();
        $this->agregarModificar = false;
    }

    public function Edit(User $usuario)
    {
        $this->name = $usuario->name;
        $this->username = $usuario->username;
        $this->email = $usuario->email;
        $this->estado = $usuario->estado;
        $this->role = $usuario->rol;
        $this->password = '';
        $this->idSelect = $usuario->id;
        $this->agregarModificar = true;
        $this->passwordOld = $usuario->password;
        $this->emit("show-modal", $usuario);

    }

    public function Update($datos)
    {
        //echo '<pre>'; print_r($datos); echo '</pre>';
        $datos = (object)$datos;
        $this->name = $datos->name;
        $this->username = $datos->username;
        $this->email = $datos->email;
        $this->estado = $datos->estado;
        $this->role = $datos->role;
        $this->password = $datos->password;
        $reglas = [
            "name" => "required|min:3",
            "username" => "required|unique:users,username,{$this->idSelect}",
            "estado" => "required|not_in:-",
            "role" => "required|not_in:-",

        ];
        $mensajes = [
            "name.required" => "¡Debe ingresar el Nombre completo  del Usuario!",
            "name.min" => "El Usuaurio debe tener al manos 3 caracteres",
            "username.required" => "Debe Ingresar un nombre de Usuario",
            "username.unique" => "Ya existe un Usuario",
            "estado.not_in" => "Debe seleccionar un estado",
            "role.not_in" => "Debe seleccionar un Rol para el Usuario",
        ];

        $this->validate($reglas, $mensajes);

        if ($this->password!="") {
           $reglas = [
                "password" => "min:6|regex:/^[\\_\\$\\#\\&\\%\\0-9a-zA-ZáéíóúÁÉÍÓÚÑñ]+$/i"

            ];
            $mensajes = [
                "password.min" => "La Clave debe tener al manos 6 caracteres",
                "password.regex" => "La CLAVE tiene carácteres no válidos"
            ];

            $this->validate($reglas, $mensajes);
            $nuevoPassword = Hash::make($this->password);
        }else{

            $nuevoPassword = $this->passwordOld;
            
        }

        $user = User::find($this->idSelect);
        $user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'estado' => $this->estado,
            'rol' => $this->role,
            'password' => $nuevoPassword
        ]);

        $user->save();
        $user->syncRoles($this->role);
        $this->agregarModificar = false;
        $this->emit('user-updated','Usuario actualizado correctamente');
        $this->resetUI();
    }

   

    public function Destroy(User $user){
        
        $user->update([
            
            'estado' =>'DELETE',
            
        ]);
        $user->save();
        $this->emit('user-deleted','Usuario eliminado correctamente');
    }
}
