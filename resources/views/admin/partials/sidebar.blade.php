<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        {{-- <li class="nav-item">
            <a class="nav-link " href="{{url('/')}}">
                <i class="bi bi-house-door"></i>
                <span>Home</span>
            </a>
        </li> --}}


        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.departments.index') }}">
                <i class="bi bi-building"></i>
                <span>Department Management</span>
            </a>
        </li><!-- End Department Management -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.racks.index') }}">
                <i class="bi bi-hdd-rack"></i>
                <span>Racks Management</span>
            </a>
        </li><!-- End Racks Management -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.files.index') }}">
                 <i class="bi bi-folder2-open"></i>
                <span>Files Management</span>
            </a>
        </li><!-- End Files Management -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#master-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-gear-wide-connected"></i><span>Master Settings</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="master-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <i class="bi bi-circle"></i><span>User Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.permissions.index') }}">
                        <i class="bi bi-circle"></i><span>Permissions Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-circle"></i><span>Roles Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.create.generalSetting') }}">
                        <i class="bi bi-circle"></i><span>General Setting</span>
                    </a>
                </li>
            </ul>
        </li><!-- Master Settings -->
    </ul>

</aside><!-- End Sidebar-->
