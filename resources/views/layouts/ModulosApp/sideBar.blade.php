<aside class="main-sidebar elevation-4 sidebar-light-info">
    
    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host observed"><div class="os-resize-observer" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 253px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                Clientes
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" pagina="configuracion" class="nav-link opMenu">
                  <i class="fas fa-cogs nav-icon"></i>
                  <p>Configuración</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="fas fa-phone-alt nav-icon"></i>
                  <p>Teléfonos por Clientes</p>
                </a>
              </li>
            </ul>
          </li>
          @can('Menu Usuarios')
            <li class="nav-item has-treeview">
              <a href="javascript:void(0)" class="nav-link active">
                <i class="fas fa-user-cog nav-icon"></i>
                <p>
                  Usuarios
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
               
                <li class="nav-item">
                  <a href="{{ url('roles')}}"  class="nav-link">
                    <i class="fas fa-user-tag nav-icon"></i>
                    <p>Roles</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('permisos')}}"  class="nav-link">
                    <i class="fas fa-user-shield nav-icon"></i>
                    <p>Permisos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('usuarios') }}" class="nav-link">
                    <i class="fas fa-user-friends nav-icon"></i>
                    <p>Usuarios</p>
                  </a>
                </li>
              
              </ul>
            </li>
          @endcan
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 22.1254%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
    <!-- /.sidebar -->
  </aside>
    
