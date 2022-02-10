<?php
$path = Request::path();
$uRole = Auth::user()->role;
?>
<ul class="nav-main">
  <li class="{{ $path == 'admin/clientes' ? 'active' : '' }}">
    <a href="{{ url('/admin/clientes') }}" >
      <i class="fa fa-users"></i><span class="sidebar-mini-hide font-w600">Clientes</span>
    </a>
  </li>
  <li class="{{ (str_contains($path,'citas/comercial')) ? 'active' : '' }}">
    <a href="{{ url('/admin/citas/comercial') }}" >
      <i class="fa fa-plus-circle"></i><span class="sidebar-mini-hide font-w600">Citas COMERCIAL</span>
    </a>
  </li>
  <li class="{{ (str_contains($path,'citas/instalador')) ? 'active' : '' }}">
    <a href="{{ url('/admin/citas/instalador') }}" >
      <i class="fa fa-plus-circle"></i><span class="sidebar-mini-hide font-w600">Citas INSTALADOR</span>
    </a>
  </li>
  <li class="{{ $path == 'admin/tarifas' ? 'active' : '' }}">
    <a href="{{url('/admin/tarifas/listado')}}" class="font-w600"><i class="fa fa-thumb-tack"></i> <span class="sidebar-mini-hide font-w600">Servicios</span></a>
  </li>
  @if($uRole == "admin")
  <li class="{{ str_contains($path,'admin/entrenadores') ? 'active' : '' }}">
    <a href="{{ url('/admin/entrenadores/activos') }}" >
      <i class="fa fa-hand-rock-o"></i><span class="sidebar-mini-hide font-w600">COMISIONES E INSTALACIONES</span>
    </a>
  </li>
  <li class="{{ $path == 'admin/usuarios' ? 'active' : '' }}">
    <a href="{{ url('/admin/usuarios') }}" >
      <i class="fa fa-users"></i><span class="sidebar-mini-hide font-w600">Usuarios</span>
    </a>
  </li>
  <li class="{{ $path == 'admin/ingresos' ? 'active' : '' }}">
    <a href="{{url('/admin/ingresos/')}}" class="font-w600"><i class="fa fa-line-chart"></i> <span class="sidebar-mini-hide font-w600">Contabilidad</span></a>
  </li>
  <li class="{{ $path == 'admin/facturas' ? 'active' : '' }}">
    <a href="{{url('/admin/facturas/')}}" class="font-w600"><i class="fa fa-files-o"></i> <span class="sidebar-mini-hide font-w600">Facturas</span></a>
  </li>
  <li class="{{ $path == 'control-contabilidad' ? 'active' : '' }}">
    <a href="{{url('/control-contabilidad')}}" class="font-w600"><i class="fa fa-line-chart"></i> <span class="sidebar-mini-hide font-w600">Control Contabilidad</span></a>
  </li>
  <li class="{{ (str_contains($path,'settings_msgs')) ? 'active' : '' }}">
    <a href="{{url('/admin/settings_msgs')}}" class="font-w600"><i class="fa fa-building"></i> <span class="sidebar-mini-hide font-w600">Txt Mails</span></a>
  </li>

  @endif
  <li style="margin-left: 17px;">
    <form action="{{ url('/logout') }}" method="POST">
      @csrf
      <button alt="Salir" class="text-danger" style="color: #d26a5c;" type="submit">
        <i class="fa fa-btn fa-sign-out text-danger"></i>  <span class="sidebar-mini-hide font-w600">Salir ({{ Auth::user()->name }})</span><!-- -->
      </button>
    </form>
  </li>
</ul>
<style>
  .subMenu{
    display: block;
    padding: 10px 20px;
    color: rgba(255, 255, 255, 1);
    text-transform: uppercase;
    cursor: pointer;
  }
  .subMenu ul{
    overflow: auto;
    height: auto;
    background-color: transparent;
    padding-left: 24px;
  }
  .subMenu.opened ul{
    display: block !important;
  }
  .nav-main .subMenu ul > li {
    opacity: 1;
  }
  .nav-main .subMenu ul > li a{
    padding: 7px;
    margin: 8px 0;
  }
</style>
