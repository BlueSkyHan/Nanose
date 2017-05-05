<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>硒旺</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">

    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">

    <link href="{{asset('css/nanose.css')}}" rel="stylesheet">

    <!-- Font Awesome -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">--}}

    <!-- Ionicons -->
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">--}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    @include('index.header')

    <!-- Left side column. contains the logo and sidebar -->
    @if(Auth::check())
    @if(Auth::user()->isAdmin())
        @include('index.adminSidebar')
    @else
        @include('index.employeeSidebar')
    @endif
    @endif
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">

        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    @include('index.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->
<script src="{{asset('js/nanose.js')}}"></script>

@if(Auth::check())
    @if(Auth::user()->isAdmin())
        <script>
            $(function(){
                $('#member-index').click(function(e){
                    e.preventDefault();
                    $.get('member', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#sales-order-index').click(function(e){
                    e.preventDefault();
                    $.get('store/' + 0 + '/salesOrder', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#store-index').click(function(e){
                    e.preventDefault();
                    $.get('store', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#productType-index').click(function(e){
                    e.preventDefault();
                    $.get('productType', function(data){
                        $('section.content').html(data);
                    });
                });

                var anHour = 60 * 60 * 1000;
                setTimeout(function(){
                    $('#logout-form').prev().click();
                }, anHour);
            });
        </script>
    @elseif(Auth::user()->employee->employeeType->name == '门店管理')
        <script>
            $(function(){
                $('#member-index').click(function(e){
                    e.preventDefault();
                    $.get('member', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#sales-order-index').click(function(e){
                    e.preventDefault();
                    $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/salesOrder', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#warehouse-index').click(function(e){
                    e.preventDefault();
                    $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#employee-index').click(function(e){
                    e.preventDefault();
                    $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/employee', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#productType-index').click(function(e){
                    e.preventDefault();
                    $.get('productType', function(data){
                        $('section.content').html(data);
                    });
                });

                var anHour = 60 * 60 * 1000;
                setTimeout(function(){
                    $('#logout-form').prev().click();
                }, anHour);
            });
        </script>
    @else
        <script>
            $(function(){
                $('#member-index').click(function(e){
                    e.preventDefault();
                    $.get('member', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#sales-order-index').click(function(e){
                    e.preventDefault();
                    $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/salesOrder', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#warehouse-index').click(function(e){
                    e.preventDefault();
                    $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/warehouse', function(data){
                        $('section.content').html(data);
                    });
                });

                $('#productType-index').click(function(e){
                    e.preventDefault();
                    $.get('productType', function(data){
                        $('section.content').html(data);
                    });
                });

                var anHour = 60 * 60 * 1000;
                setTimeout(function(){
                    $('#logout-form').prev().click();
                }, anHour);
            });
        </script>
    @endif
    @if(!Auth::user()->isAdmin())
        <script>
            $(function(){
                $('#employee-edit-button').click(function(){
                    var employeeId = $(this).parent().prop('id').replace('employee-id-', '');
                    $.get('store/' + '{{Auth::user()->employee->store_id}}' + '/employee/' + employeeId + '/edit', function(data){
                        $('section.content').html(data);
                    });
                });
            });
        </script>
    @endif
@endif

</body>
</html>