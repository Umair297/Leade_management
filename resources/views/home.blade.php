<!doctype html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-http://localhost/lead//assets/"
  data-template="vertical-menu-template"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Lead</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="http://localhost/leade/public/assets/img/branding/lead.jpg" />
  
    <!-- Other head elements -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/css/pages/app-calendar.css" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=http://localhost/leade/Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="http://localhost/leade/public/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="http://localhost/leade/public/assets/vendor/css/pages/cards-advance.css" />
    
      <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css"/>
      
    <script src="http://localhost/leade/public/assets/vendor/js/helpers.js"></script>
    <script src="http://localhost/leade/public/assets/js/config.js"></script>
    
    <style>
    .app-brand{
    height:100px !important;
    }
    .app-brand-logo.demo{
    height:100% !important;
    }
    .bg-menu-theme.menu-vertical .menu-item.active > .menu-link:not(.menu-toggle) {
    background: #676b6b;
    box-shadow:none !important;
    }
    </style>
    
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{url('/home')}}" class="app-brand-link">
              <span class="app-brand-logo demo">
                @if(!request()->is('cases/') && !request()->is('calendar/'))
           <img src="{{ asset('public/assets/img/branding/leade.png') }}" alt="Logo"
     style="width: auto; height: 100%; max-height: 95px; display: block; margin: 0 auto; padding: 10px;">

        @endif
              </span>
            
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards - Always visible for all roles -->
            <li class="menu-item {{ request()->routeIs('home') ? 'active open' : '' }}">
                <a href="{{ route('home') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-smart-home"></i>
                    <div data-i18n="Dashboards">Dashboards</div>
                </a>
            </li>

            <!-- Conditional Menu Items for Employees -->
            @if(auth()->check() && auth()->user()->role === 'Agent')
                <li class="menu-item {{ request()->routeIs('cases.index') ? 'active open' : '' }}">
                    <a href="{{ route('cases.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-briefcase"></i>
                        <div data-i18n="Cases">Cases</div>
                    </a>
                </li>
                
                <li class="menu-item {{ request()->routeIs('assign.calendar') ? 'active open' : '' }}">
                    <a href="{{ route('assign.calendar') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-calendar"></i>
                        <div data-i18n="Calendar">Calendar</div>
                    </a>
                </li>
            @else
                <!-- Admin or other roles can access other menus -->
                <li class="menu-item {{ request()->routeIs('users.index') ? 'active open' : '' }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                        <div data-i18n="Users">Users</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('cases.index') ? 'active open' : '' }}">
                    <a href="{{ route('cases.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-briefcase"></i>
                        <div data-i18n="Cases">Cases</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('assign.index') ? 'active open' : '' }}">
                    <a href="{{ route('assign.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                        <div data-i18n="Assign">Assign</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('assign.calendar') ? 'active open' : '' }}">
                    <a href="{{ route('assign.calendar') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-calendar"></i>
                        <div data-i18n="Calendar">Calendar</div>
                    </a>
                </li>
            @endif

        </ul>
        </aside>
 <!-- / Menu -->
     <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-md"></i>
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                     <!-- Notification -->
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                 <a
    class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
    href="javascript:void(0);"
    data-bs-toggle="dropdown"
    data-bs-auto-close="outside"
    aria-expanded="false"
  >
    <span class="position-relative">
      <i class="ti ti-bell ti-md"></i>
      @if(Session::has('notifications') && count(Session::get('notifications')) > 0)
        <!-- Replace the dot with the count -->
         <span
          class="badge rounded-pill bg-success text-white"
          style="
            position: absolute;
            top: -6px; 
            right: -6px; 
            font-size: 0.65rem; 
            padding: 0.15rem 0.4rem;
            min-width: 1rem;
            height: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            line-height: 1;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
          "
          id="notificationCount"
        >
          {{ count(Session::get('notifications')) }}
        </span>
      @endif
    </span>
  </a>

                    <ul class="dropdown-menu dropdown-menu-end p-0">
    <li class="dropdown-menu-header border-bottom">
      <div class="dropdown-header d-flex align-items-center py-3">
        <h6 class="mb-0 me-auto">Notifications</h6>
      </div>
    </li>
    <li class="dropdown-notifications-list scrollable-container">
      <ul class="list-group list-group-flush" id="notificationList">
        @if(Session::has('notifications') && count(Session::get('notifications')) > 0)
          @foreach(Session::get('notifications') as $notification)
            <li class="list-group-item list-group-item-action dropdown-notifications-item">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <small class="mb-1 d-block text-body">{{ $notification }}</small>
                </div>
              </div>
            </li>
          @endforeach
        @else
          <li class="list-group-item text-center">
            <small class="text-muted">No notifications</small>
          </li>
        @endif
      </ul>
    </li>
  </ul>
                        </li>

                <!--/ Notification -->
    
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                          @if(Auth::check() && Auth::user()->image)
                              <img src="{{ asset('public/uploads/users/' . Auth::user()->image) }}" alt="User Image" class="rounded-circle" />
                          @else
                              <img src="{{ asset('public/assets/img/default-avatar.png') }}" alt="Default Avatar" class="rounded-circle" />
                          @endif
                      </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                          <div class="d-flex align-items-center">
                              <div class="flex-shrink-0 me-2">
                                  <div class="avatar avatar-online">
                                      @if(Auth::check() && Auth::user()->image)
                                          <img src="{{ asset('public/uploads/users/' . Auth::user()->image) }}" alt="User Image" class="rounded-circle" />
                                      @else
                                          <img src="{{ asset('public/assets/img/default-avatar.png') }}" alt="Default Avatar" class="rounded-circle" />
                                      @endif
                                  </div>
                              </div>
                              <div class="flex-grow-1">
                                  <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                  <small class="text-muted">{{ auth()->user()->role }}</small>
                              </div>
                          </div>
                      </li>
                      <li>
                          <div class="dropdown-divider my-1 mx-n2"></div>
                      </li>
                      <li>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                          <a class="dropdown-item" href="{{ route('logout') }}"
                              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                              <i class="ti ti-logout me-3 ti-md"></i>
                              <span class="align-middle">Logout</span>
                          </a>
                      </li>
                  </ul>
              </li>

                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              @yield('content')
            </div>
            <!-- / Content -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetchNotifications();

    function fetchNotifications() {
        fetch('/notifications')
            .then(response => response.json())
            .then(data => {
                const notificationsList = document.getElementById('notifications-list');
                notificationsList.innerHTML = '';

                if (data.length === 0) {
                    notificationsList.innerHTML = `
                        <li class="list-group-item text-center">
                            No notifications found.
                        </li>`;
                    return;
                }

                data.forEach(notification => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('list-group-item', 'list-group-item-action', 'dropdown-notifications-item');
                    listItem.innerHTML = `
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="small mb-1">Follow-Up Notification</h6>
                                <small class="mb-1 d-block text-body">
                                    Code Cliente: ${notification.cod_cliente}
                                </small>
                                <small class="text-muted">Follow-Up Date: ${notification.follow_up_date}</small>
                            </div>
                        </div>
                    `;
                    notificationsList.appendChild(listItem);
                });
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    fetch('/notifications')
        .then(response => response.json())
        .then(data => {
            const notificationList = document.getElementById('notifications');
            notificationList.innerHTML = ''; // Clear existing content

            if (data.length === 0) {
                notificationList.innerHTML = '<li>No follow-up cases found</li>';
                return;
            }

            data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `Cod Cliente: ${item.cod_cliente}`;
                notificationList.appendChild(li);
            });
        });
});
</script>



    <script src="http://localhost/leade/public/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/popper/popper.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/js/bootstrap.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->
     <!-- Vendors JS -->
    <script src="http://localhost/leade/public/assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/@form-validation/auto-focus.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/select2/select2.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/moment/moment.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/flatpickr/flatpickr.js"></script>

    <script src="http://localhost/leade/public/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/swiper/swiper.js"></script>
    <script src="http://localhost/leade/public/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->
    <script src="http://localhost/leade/public/assets/js/main.js"></script>
      <script src="https://cdn.datatables.net/2.1.8/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>

    <!-- Page JS -->
     @yield('script')
     <script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
// <script>
//     $(document).ready(function() {
//         $('#example1').DataTable();
//     });
// </script>
  </body>
</html>