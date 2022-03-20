@php
  $user       = auth()->user();
  $name       = $user->name ?? 'Name';
  $initial    = $name[0];
@endphp

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm px-4">
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
      <i class="fa fa-bars"></i>
  </button>

  <ul class="navbar-nav ms-auto">
    <div class="topbar-divider d-none d-sm-block"></div>

    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ $name }}</span>
          <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ $initial }}"></figure>
      </a>

      <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in border-0" aria-labelledby="userDropdown">

          <a class="dropdown-item" href="{{ url('/profile') }}">
              <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
              Profile
          </a>

          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ url('/logout') }}">
              <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
              Logout
          </a>
      </div>
    </li>
  </ul>
</nav>