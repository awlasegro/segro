<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Segro - @yield('title')</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- Custom Extensions -->
  <link rel="stylesheet" href="{{ asset('extensions/fixed-columns/bootstrap-table-fixed-columns.css') }}">
  <link rel="stylesheet" href="{{ asset('extensions/sticky-header/bootstrap-table-sticky-header.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          @if($navChatUnreadTotal > 0)
            <span class="badge badge-danger navbar-badge">{{ $navChatUnreadTotal }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          @forelse($navChatConversations as $conversation)
            <a href="{{ route('admin.chats.show', $conversation->id) }}" class="dropdown-item">
              <!-- Message Start -->
              <div class="media">
                <img src="{{ $conversation->profile_photo_url }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    {{ $conversation->name }}
                    @if($conversation->unread_count > 0)
                      <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                    @endif
                  </h3>
                  <p class="text-sm">
                    @if(optional($conversation->latest_message)->message)
                      {{ \Illuminate\Support\Str::limit($conversation->latest_message->message, 40) }}
                    @elseif(optional($conversation->latest_message)->image)
                      Photo attachment
                    @else
                      No messages yet
                    @endif
                  </p>
                  <p class="text-sm text-muted">
                    <i class="far fa-clock mr-1"></i>
                    {{ optional(optional($conversation->latest_message)->created_at)->diffForHumans() }}
                  </p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            <div class="dropdown-divider"></div>
          @empty
            <span class="dropdown-item text-muted">No conversations yet</span>
            <div class="dropdown-divider"></div>
          @endforelse
          <a href="{{ route('admin.chats.index') }}" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          @if($navNotificationsTotal > 0)
            <span class="badge badge-warning navbar-badge">{{ $navNotificationsTotal }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">{{ $navNotificationsTotal }} Notification{{ $navNotificationsTotal == 1 ? '' : 's' }}</span>
          @forelse($navNotifications as $request)
            <div class="dropdown-divider"></div>
            <a href="{{ $request->type == 'deposit' ? route('admin.deposit.requests') : route('admin.redemption.requests') }}" class="dropdown-item">
              @if($request->type == 'deposit')
                <i class="fas fa-coins mr-2"></i> Deposit request &mdash; ${{ number_format($request->amount, 2) }} from {{ optional($request->user)->name ?? 'Unknown' }}
              @else
                <i class="fas fa-hand-holding-usd mr-2"></i> Withdrawal request &mdash; ${{ number_format($request->amount, 2) }} from {{ optional($request->user)->name ?? 'Unknown' }}
              @endif
              <span class="float-right text-muted text-sm">{{ $request->created_at->diffForHumans(null, true) }}</span>
            </a>
          @empty
            <div class="dropdown-divider"></div>
            <span class="dropdown-item text-muted">No pending requests</span>
          @endforelse
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.deposit.requests') }}" class="dropdown-item dropdown-footer">See All Requests</a>
        </div>
      </li>

      <!-- Logout -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('images/logo.png') }}" alt="SEGRO" style="height: 22px; margin-left: 12px;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/avatar4.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>

      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/administration" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                Members
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/add-member" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>
                Add Members
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('admin.chats.index') }}" class="nav-link">
              <i class="nav-icon fas fa-comments"></i>
              <p>
                Support Chats
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Funds Requests
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 15px;">
              <li class="nav-item">
                <a href="/admin/deposit-requests" class="nav-link">
                  <i class="fas fa-coins nav-icon"></i>
                  <p>Deposit Requests</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/redemption-requests" class="nav-link">
                  <i class="fas fa-hand-holding-usd nav-icon"></i>
                  <p>Redemption Requests</p>
                </a>
              </li>
            </ul>
          </li>
          @if(Auth::user()->user_type == 1)
          <li class="nav-item">
            <a href="/admin/platform-wallet" class="nav-link">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Platform Wallet
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ URL('order-list') }}" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>
                Order Lists
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/memberships" class="nav-link">
              <i class="nav-icon fas fa-crown"></i>
              <p>
                Memberships
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{ route('admin.logout') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.2.0
    </div>
    <strong>Copyright &copy; 2022-2026 <a href="#">Segro</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- BS Stepper -->
<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- Custom Extensions -->
<script src="{{ asset('extensions/fixed-columns/bootstrap-table-fixed-columns.js') }}"></script>
<script src="{{ asset('extensions/sticky-header/bootstrap-table-sticky-header.js') }}"></script>
<!-- Custom Search Code -->
<script src="{{ asset('dist/js/search.js') }}"></script>

<!-- Page-specific scripts -->
<script>
  $(function () {
    // DataTables Initialization
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<script>
    $(function () {
      // Initialize Select2 Elements
      $('.select2').select2()
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })

      // Input Masks
      $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
      $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
      $('[data-mask]').inputmask()

      // Date Pickers
      $('#reservationdate').datetimepicker({
          format: 'L'
      });
      $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });
      $('#reservation').daterangepicker()
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
          format: 'MM/DD/YYYY hh:mm A'
        }
      })
      $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate  : moment()
        },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

      // Timepicker
      $('#timepicker').datetimepicker({
        format: 'LT'
      })

      // Bootstrap Duallistbox
      $('.duallistbox').bootstrapDualListbox()

      // Colorpicker
      $('.my-colorpicker1').colorpicker()
      $('.my-colorpicker2').colorpicker()
      $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
      })

      // Bootstrap Switch
      $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      })
    });

    // BS-Stepper Init
    document.addEventListener('DOMContentLoaded', function () {
      window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    });

    // DropzoneJS Demo Code
    Dropzone.autoDiscover = false
    var previewNode = document.querySelector("#template")
    previewNode.id = ""
    var previewTemplate = previewNode.parentNode.innerHTML
    previewNode.parentNode.removeChild(previewNode)

    var myDropzone = new Dropzone(document.body, {
      url: "/target-url",
      thumbnailWidth: 80,
      thumbnailHeight: 80,
      parallelUploads: 20,
      previewTemplate: previewTemplate,
      autoQueue: false,
      previewsContainer: "#previews",
      clickable: ".fileinput-button"
    })

    myDropzone.on("addedfile", function(file) {
      file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
    })

    myDropzone.on("totaluploadprogress", function(progress) {
      document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
    })

    myDropzone.on("sending", function(file) {
      document.querySelector("#total-progress").style.opacity = "1"
      file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
    })

    myDropzone.on("queuecomplete", function(progress) {
      document.querySelector("#total-progress").style.opacity = "0"
    })

    document.querySelector("#actions .start").onclick = function() {
      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
    }
    document.querySelector("#actions .cancel").onclick = function() {
      myDropzone.removeAllFiles(true)
    }
</script>

@yield('scripts')

</body>
</html>

