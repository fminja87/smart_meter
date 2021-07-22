<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>REGISTRATION</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

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
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a href="{{ route('login') }}" class="navbar-nav-link">
                    <i class="icon-user-lock"></i>
                    <span class="d-md-none ml-2">Customer Login</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Page content -->
<div class="page-content">

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">

            <!-- Registration form -->
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="card mb-0">
                            <form action="{{ route('register') }}" method="POST" >
                                @csrf

                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                                    <h5 class="mb-0">Create account</h5>
                                    <span class="d-block text-muted">All fields are required</span>
                                </div>

                                @if ($message = Session::get('error'))

                                    <div class="alert alert-danger alert-block">

                                        <button type="button" class="close" data-dismiss="alert">×</button>

                                        <strong>{{ $message }}</strong>

                                    </div>

                                @endif

                                @if ($message = Session::get('info'))

                                    <div class="alert alert-info alert-block">

                                        <button type="button" class="close" data-dismiss="alert">×</button>

                                        <strong>{{ $message }}</strong>

                                    </div>

                                @endif

                                @if ($message = Session::get('success'))

                                    <div class="alert alert-success alert-block">

                                        <button type="button" class="close" data-dismiss="alert">×</button>

                                        <strong>{{ $message }}</strong>

                                    </div>

                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" id="first_name" placeholder="First name" value="{{ old('first_name') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                            @if ($errors->has('first_name'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('second_name') ? ' is-invalid' : '' }}" name="second_name" id="second_name" placeholder="Second name" value="{{ old('second_name') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                            @if ($errors->has('second_name'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('second_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" placeholder="Your email" value="{{ old('email') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-mention text-muted"></i>
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number"  id="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-mobile text-muted"></i>
                                            </div>
                                            @if ($errors->has('phone_number'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('phone_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('region') ? ' is-invalid' : '' }}" name="region" id="region" placeholder="Region" value="{{ old('region') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-location3 text-muted"></i>
                                            </div>
                                            @if ($errors->has('region'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('region') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                      <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('district') ? ' is-invalid' : '' }}" name="district" id="district" placeholder="District" value="{{ old('district') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-location4 text-muted"></i>
                                            </div>
                                            @if ($errors->has('district'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('district') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('ward') ? ' is-invalid' : '' }}" name="ward" id="ward" placeholder="Ward" value="{{ old('ward') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-location3 text-muted"></i>
                                            </div>
                                            @if ($errors->has('ward'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('ward') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" name="street" id="street" placeholder="Street" value="{{ old('street') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-road text-muted"></i>
                                            </div>
                                            @if ($errors->has('street'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('street') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('house_number') ? ' is-invalid' : '' }}" name="house_number" id="house_number" placeholder="House Number" value="{{ old('house_number') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-road text-muted"></i>
                                            </div>
                                            @if ($errors->has('house_number'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('house_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" class="form-control{{ $errors->has('meter_number') ? ' is-invalid' : '' }}" name="meter_number" id="meter_number" placeholder="Meter Number" value="{{ old('meter_number') }}">
                                            <div class="form-control-feedback">
                                                <i class="icon-meter2 text-muted"></i>
                                            </div>
                                            @if ($errors->has('meter_number'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('meter_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Create password">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-lock text-muted"></i>
                                            </div>
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                   <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Repeat password">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-lock text-muted"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <button type="submit" class="btn btn-block bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Create account</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            <!-- /registration form -->

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

<!-- Core JS files -->
<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="{{ asset('assets/js/app.js') }}"></script>
<!-- /theme JS files -->
<script>
    function clearData() {

        $('#first_name').val('');
        $('#second_name').val('');
        $('#email').val('');
        $('#phone_number').val('');
        $('#region').val('');
        $('#district').val('');
        $('#ward').val('');
        $('#street').val('');
        $('#password').val('');
        $('#password_confirmation').val('');

    }


    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    function registerCustomer() {

        var first_name = $('#first_name').val()
        var second_name = $('#second_name').val()
        var email =  $('#email').val()
        var phone_number = $('#phone_number').val()
        var region = $('#region').val()
        var district = $('#district').val()
        var ward = $('#ward').val()
        var street = $('#street').val()
        var house_number = $('#house_number').val()
        var password = $('#password').val()
        var password_confirmation = $('#password_confirmation').val()

        $.ajax({
            type: "POST",
            dataType: "json",
            data: {first_name:first_name, second_name:second_name, email:email, phone_number:phone_number, region:region, district:district, ward:ward, street:street, house_number:house_number, password:password, password_confirmation:password_confirmation},
            url: "/register",
            success: function (data) {
                clearData();
                alert("Customer Registered Successful")
            }
        })
    }
</script>
</body>
</html>
