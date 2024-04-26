<!-- Start Top Header Bar -->
<section class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-xs-12 col-sm-4">
                {{-- <div class="contact-number">
                    <i class="tf-ion-ios-telephone"></i>
                    <span>0129- 12323-123123</span>
                </div> --}}
            </div>
            <div class="col-md-4 col-xs-12 col-sm-4">
                <!-- Site Logo -->
                <div class="logo text-center">
                    <a href="{{ url('/') }}" style="font-family:Playfair Display; font-size:50px;">
                        <!-- replace logo here -->
                        {{ env('APP_NAME') ?? 'Laravel' }}
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-4">
                <!-- Cart -->
                <ul class="top-menu text-right list-inline">
                    {{-- <li class="dropdown cart-nav dropdown-slide">
                        <a href="{{ route('login') }}" class="dropdown-toggle"><i class="tf-ion-ios-users"></i>
                            Login</a>


                    </li><!-- / Cart --> --}}



                </ul><!-- / .nav .navbar-nav .navbar-right -->
            </div>
        </div>
    </div>
</section><!-- End Top Header Bar -->


<!-- Main Menu Section -->
<section class="menu">
    <nav class="navbar navigation">
        <div class="container">
            <div class="navbar-header">
                <h2 class="menu-title">Menu Utama</h2>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div><!-- / .navbar-header -->

            <!-- Navbar Links -->
            <div id="navbar" class="navbar-collapse collapse text-center">
                <ul class="nav navbar-nav">

                    <!-- Home -->
                    <li class="dropdown ">
                        <a href="{{ url('/') }}">Home</a>
                    </li><!-- / Home -->
                    <li class="dropdown ">
                        @guest
                            <a href="{{ route('login') }}">Login</a>
                        @else
                            <a href="{{ route('home') }}">Dashboard</a>
                        @endguest
                    </li><!-- / login -->


                </ul><!-- / .nav .navbar-nav -->

            </div>
            <!--/.navbar-collapse -->
        </div><!-- / .container -->
    </nav>
</section>
