<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CUSTOMER | TRANSACTIONS</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script src="{{ asset('global_assets/js/demo_pages/datatables_basic.js') }}"></script>
    <!-- /theme JS files -->

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
        <a href="" class="d-inline-block">
            <h1 style="color: #fff"><b>SMWBS</b></h1>
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>


        </ul>

        <span class=" ml-md-3 mr-md-auto"></span>

        <ul class="navbar-nav">

            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('global_assets/images/placeholders/placeholder.jpg') }}" class="rounded-circle mr-2" height="34" alt="">
                    <span>{{Auth::user()->name}}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item"><i class="icon-profile"></i> My profile</a>
                    <a href="{{ route('customer.wallet') }}" class="dropdown-item"><i class="icon-wallet"></i> My balance</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('out-form').submit();" class="dropdown-item"><i class="icon-switch2"></i>
                        <form id="out-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <!-- /sidebar mobile toggler -->


        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">
                    <div class="media">
                        <div class="mr-3">
                            <a href="#"><img src="{{ asset('global_assets/images/placeholders/placeholder.jpg') }}" width="38" height="38" class="rounded-circle" alt=""></a>
                        </div>

                        <div class="media-body">
                            <div class="media-title font-weight-semibold">{{ Auth::User()->name }}</div>
                            <div class="font-size-xs opacity-50">
                                <i class="icon-pin font-size-sm"></i> &nbsp;{{Auth::user()->region}}, {{Auth::user()->district}}
                            </div>
                        </div>

                        <div class="ml-3 align-self-center">
                            <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /user menu -->


            <!-- Main navigation -->
            <div class="card card-sidebar-mobile">
                <ul class="nav nav-sidebar" data-nav-type="accordion">

                    <!-- Main -->
                    <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link active">
                            <i class="icon-home4"></i>
                            <span>
									Dashboard
								</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.wallet') }}" class="nav-link">
                            <i class="icon-wallet"></i>
                            <span>
									Wallet
								</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customer.profile') }}" class="nav-link">
                            <i class="icon-profile"></i>
                            <span>
									Profile
								</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /main navigation -->

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title d-flex">
                    <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>

            </div>

            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Customer</a>
                        <span class="breadcrumb-item active">Dashboard</span>
                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>


            </div>
        </div>
        <!-- /page header -->


        <!-- Content area -->
        <div class="content">

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Monthly Bills</h5>
                </div>

                <div class="card-body">
                  @if ($bill == null)
                  <h1 align="center">No Bill</h1>
                  @else
                  <div class="row">
                      <div class="col-lg-4">
                        <h4 align="center">
    
                           From: {{ $bill->starting_date }}
                        
                            </h4>
                      </div>
                      <div class="col-lg-4">
                        <h4 align="center">
                          
                           To: {{ $bill->end_date }}
                            </h4>
                    </div>
                    <div class="col-lg-4">
                        <h4 align="center">
                           Bill No: {{ $bill->vourcher_number }}
        
                            </h4>
                    </div>

                    <div class="col-lg-4">
                        <h4 align="center">
    
                           Volume: {{ $bill->total_litters }}L
                        
                            </h4>
                      </div>
                      <div class="col-lg-4">
                        <h4 align="center">
                          
                           Units: {{ $bill->total_units }}
                            </h4>
                    </div>
                    <div class="col-lg-4">
                        <h4 align="center">
                           Bill Amount: {{ $bill->total_bill }}TZS
        
                            </h4>
                    </div>
                  </div>
                  @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Bills</h5>
                </div>

                <div class="card-body">
                    <table class="table datatable-basic">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Litters</th>
                           <th>Units</th>
                           <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($new_bills as $bill)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($bill->updated_at)->format('d M Y') }}</td>
                                <td>
                                 {{ $bill->litters }}L
                                </td>
                               <td>{{ $bill->units }}</td>
                               <td>{{ number_format($bill->bill_price) }}TZS</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!-- /content area -->


        <!-- Footer -->
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="text-center d-lg-none w-100">
                <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
                    <i class="icon-unfold mr-2"></i>
                    Footer
                </button>
            </div>

            <div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; {{ date('Y') }}. <a href="{{ url('/') }}">SMWBS</a>
					</span>
            </div>
        </div>
        <!-- /footer -->

    </div>
    <!-- /main content -->

</div>
<!-- /page content -->

</body>
</html>

