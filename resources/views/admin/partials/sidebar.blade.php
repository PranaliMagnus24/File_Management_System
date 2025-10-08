<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="#">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->


      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-houses"></i><span>Master Settings</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{route('admin.permissions.index')}}">
                    <i class="bi bi-circle"></i><span>Permissions Management</span>
                </a>
            </li>
            <li>
                <a href="{{route('admin.roles.index')}}">
                    <i class="bi bi-circle"></i><span>Roles Management</span>
                </a>
            </li>
        </ul>
      </li><!-- Master Settings -->
    </ul>

  </aside><!-- End Sidebar-->
