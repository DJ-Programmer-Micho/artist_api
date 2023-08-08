<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link rel="shortcut icon" href="https://www.youtube.com/s/desktop/0e9d1cf9/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="https://www.youtube.com/s/desktop/0e9d1cf9/img/favicon_32x32.png" sizes="32x32">
    <link rel="icon" href="https://www.youtube.com/s/desktop/0e9d1cf9/img/favicon_48x48.png" sizes="48x48">
    <link rel="icon" href="https://www.youtube.com/s/desktop/0e9d1cf9/img/favicon_96x96.png" sizes="96x96">
    <link rel="icon" href="https://www.youtube.com/s/desktop/0e9d1cf9/img/favicon_144x144.png" sizes="144x144">
    <meta name="author" content="MET IRAQ" />
    <meta name="description" content="Content managers are prohibited from engaging in practices that attempt to go around or interfere with YouTube’s systems, processes, or policies." />
    <title>Youtube | MET IRAQ</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
    <!-- Custom fonts for this template-->
    <link
      href="vendor/fontawesome-free/css/all.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet"
    />

    <!-- Custom styles for this template-->
    <link href="{{asset('assets/css/sb-admin-2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/toaster.css')}}" rel="stylesheet" />
  </head>

  <body class="bg-gradient-dark">

    @yield('content')

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('assets/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('assets/js/sb-admin-2.min.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
    @if(Session::has('message'))
    var type = "{{ Session::get('alert-type','info') }}"
    switch(type){
        case 'info':
        toastr.info(" {{ Session::get('message') }} ");
        break;
        case 'success':
        toastr.success(" {{ Session::get('message') }} ");
        break;
        case 'warning':
        toastr.warning(" {{ Session::get('message') }} ");
        break;
        case 'error':
        toastr.error(" {{ Session::get('message') }} ");
        break; 
    }
    @endif 
    </script>
  </body>
</html>
