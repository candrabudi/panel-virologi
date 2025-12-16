 <header class="app-topbar">
     <div class="container-fluid topbar-menu">
         <div class="d-flex align-items-center gap-2">
             <div class="logo-topbar">
                 <a href="index.html" class="logo-light">
                     <span class="logo-lg">
                         <img src="assets/images/logo.png" alt="logo" />
                     </span>
                     <span class="logo-sm">
                         <img src="assets/images/logo-sm.png" alt="small logo" />
                     </span>
                 </a>
                 <a href="index.html" class="logo-dark">
                     <span class="logo-lg">
                         <img src="assets/images/logo-black.png" alt="dark logo" />
                     </span>
                     <span class="logo-sm">
                         <img src="assets/images/logo-sm.png" alt="small logo" />
                     </span>
                 </a>
             </div>
             <button class="sidenav-toggle-button btn btn-primary btn-icon">
                 <i class="ri ri-menu-line"></i>
             </button>
             <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu">
                 <i class="ri ri-menu-line"></i>
             </button>
         </div>

         <div class="d-flex align-items-center gap-2">

             <div id="theme-toggler" class="topbar-item d-none d-sm-flex">
                 <button class="topbar-link" id="light-dark-mode" type="button">
                     <i class="ri ri-moon-line topbar-link-icon mode-light-moon"></i>
                     <i class="ri ri-sun-line topbar-link-icon mode-light-sun"></i>
                 </button>
             </div>

             <div id="fullscreen-toggler" class="topbar-item d-none d-sm-flex">
                 <button class="topbar-link" type="button" data-toggle="fullscreen">
                     <i class="ri ri-fullscreen-line topbar-link-icon"></i>
                     <i class="ri ri-fullscreen-exit-line topbar-link-icon d-none"></i>
                 </button>
             </div>

             <div id="simple-user-dropdown" class="topbar-item nav-user">
                 <div class="dropdown">
                     <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                         href="#!" aria-haspopup="false" aria-expanded="false">
                         <img src="assets/images/users/user-1.jpg" width="32" class="rounded-circle me-lg-2 d-flex"
                             alt="user-image" />
                         <div class="d-lg-flex align-items-center gap-1 d-none">
                             <h5 class="my-0">David Dev</h5>
                             <i class="ri ri-arrow-down-s-line align-middle"></i>
                         </div>
                     </a>
                     <div class="dropdown-menu dropdown-menu-end">
                         <div class="dropdown-header noti-title">
                             <h6 class="text-overflow m-0">Welcome back!</h6>
                         </div>
                         <a href="#!" class="dropdown-item">
                             <i class="ri ri-user-line me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Profile</span>
                         </a>
                         <a href="javascript:void(0);" class="dropdown-item text-danger fw-semibold" onclick="logout()">
                             <i class="ri ri-logout-box-line me-1 fs-lg align-middle"></i>
                             <span class="align-middle">Log Out</span>
                         </a>

                         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                             @csrf
                         </form>

                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
