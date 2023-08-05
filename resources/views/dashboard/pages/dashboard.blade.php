@extends('dashboard.layouts.layout')
@section('content')

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                    <div class="card hovercard">
                        <div class="cardheader" style="background: url('{{ $bannerImageUrl }}') no-repeat center center; background-size: cover;">
                        </div>
                        <div class="avatar">
                            <img alt="" src="{{$thumbnails}}">
                        </div>



                        <div class="info">
                            <div class="title">
                                <h2 class="text-white">{{$channelName}}</h2>
                            </div>
                            <div class="text-white">{{$customUrl}} - {{$country}}</div>
        
                            <div class="text-white">{{$description}}</div>
                        </div>
                    </div>
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            {{-- <h1 class="h3 mb-0 text-gary-200">{{$channelName}}</h1> --}}
                            <!-- <a
                                href="#"
                                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                ><i class="fas fa-download fa-sm text-white-50"></i> Generate
                                Report</a
                            > -->
                        </div>
    
                        <!-- Content Row -->
                        <div class="row">
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Earnings (Average)
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    ${{($earningsSum) / 12}}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Total Earning (Life Time)
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    ${{$earningsSum}}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Tax
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                     $ -{{$dataTax[0]}}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Wallet
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    $ {{$wallet}}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-landmark fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- **** -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Subscribe (Peroid Update)
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    {{$subscribersCount}} subscribers
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Channel View
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    {{$viewCount}} views
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-eye fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Video QTY
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    {{$uploadsCount}} video
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fab fa-youtube fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    Short QTY
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gary-200">
                                                    {{$hiddenSubscriberCount}} Short
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Content Row -->
    
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-white">
                                            Earnings Overview
                                        </h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="myAreaChart"></canvas>
                                        </div>
                                        <div class="my-4">
                                            <label for="yearSelect">Select Year:</label>
                                            <select id="yearSelect" class="form-control" style="background-color: #333; color: #fff;">
                                                @foreach ($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Content Row -->
                        <div class="row">
                            <div class="col-lg-6 mb-4">
                                <!-- Project Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">Store QTY</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($storeQuantities->sortByDesc(function ($item, $key) {
                                            return $item;
                                        }) as $key => $item)
                                            <h4 class="small font-weight-bold">
                                                {{ $key }} <span class="float-right">{{ number_format($item) }}</span>
                                            </h4>
                                            <div class="progress mb-4">
                                                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ ($item / $totalQuantities) * 100 }}% "
                                                    aria-valuenow="{{ $item }}" aria-valuemin="0" aria-valuemax="3"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- Content Column -->
                            <div class="col-lg-6 mb-4">
                                <!-- Project Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">Store Profit $</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($storeEarning->sortByDesc(function ($item, $key) {
                                            return $item;
                                        }) as $key => $item)
                                            <h4 class="small font-weight-bold">
                                                {{ $key }} <span class="float-right">${{ number_format($item) }}</span>
                                            </h4>
                                            <div class="progress mb-4">
                                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ ($item / intval($earningsSum)) * 100 }}%"
                                                    aria-valuenow="{{ $item }}" aria-valuemin="0" aria-valuemax="3"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            
                            <div class="col-lg-6 mb-4">
                                <!-- Project Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">Song QTY</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($songQuantities->sortByDesc(function ($item, $key) {
                                            return $item;
                                        }) as $key => $item)
                                            <h4 class="small font-weight-bold">
                                                {{ $key }} <span class="float-right">{{ number_format($item) }}</span>
                                            </h4>
                                            <div class="progress mb-4">
                                                <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ ($item / intval($totalQuantities)) * 100 }}%"
                                                    aria-valuenow="{{ $item }}" aria-valuemin="0" aria-valuemax="3"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-4">
                                <!-- Project Card Example -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">Song Profite $</h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($songEarning->sortByDesc(function ($item, $key) {
                                            return $item;
                                        }) as $key => $item)
                                            <h4 class="small font-weight-bold">
                                                {{ $key }} <span class="float-right">${{ number_format($item) }}</span>
                                            </h4>
                                            <div class="progress mb-4">
                                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ ($item / intval($earningsSum)) * 100 }}%"
                                                    aria-valuenow="{{ $item }}" aria-valuemin="0" aria-valuemax="3"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-lg-12 mb-4">
                                <!-- Illustrations -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">
                                            Illustrations
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div aria-level="1" class="logo-button-container _ngcontent-awn-AWSM-2" role="heading">
                                                <div buttondecorator="" class="app-bar-logo _ngcontent-awn-AWSM-2 glif-lockup ads-logo en"
                                                    keyboardonlyfocusindicator="" aria-label="Google Ads – Go to starting page" tabindex="0"
                                                    role="button" aria-disabled="false" style="">
                                                    <!---->
                                                    <div class="lockup-logo _ngcontent-awn-AWSM-2" role="presentation">
                                                        <svg class="lockup-ads-logo _ngcontent-awn-AWSM-2" enable-background="new 0 0 192 192"
                                                            height="192" viewBox="0 0 192 192" width="192" xmlns="http://www.w3.org/2000/svg">
                                                            <g class="_ngcontent-awn-AWSM-2">
                                                                <rect fill="none" height="192" width="192" class="_ngcontent-awn-AWSM-2"></rect>
                                                                <g class="_ngcontent-awn-AWSM-2">
                                                                    <rect fill="#FBBC04" height="58.67"
                                                                        transform="matrix(0.5 -0.866 0.866 0.5 -46.2127 103.666)" width="117.33"
                                                                        x="8" y="62.52" class="_ngcontent-awn-AWSM-2"></rect>
                                                                    <path
                                                                        d="M180.07,127.99L121.4,26.38c-8.1-14.03-26.04-18.84-40.07-10.74c-14.03,8.1-18.84,26.04-10.74,40.07 l58.67,101.61c8.1,14.03,26.04,18.83,40.07,10.74C183.36,159.96,188.16,142.02,180.07,127.99z"
                                                                        fill="#4285F4" class="_ngcontent-awn-AWSM-2"></path>
                                                                    <circle cx="37.34" cy="142.66" fill="#34A853" r="29.33"
                                                                        class="_ngcontent-awn-AWSM-2"></circle>
                                                                </g>
                                                            </g>
                                                        </svg><svg class="lockup-google-logo _ngcontent-awn-AWSM-2" height="24"
                                                            viewBox="0 0 74 24" width="74" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.24 8.19v2.46h5.88c-.18 1.38-.64 2.39-1.34 3.1-.86.86-2.2 1.8-4.54 1.8-3.62 0-6.45-2.92-6.45-6.54s2.83-6.54 6.45-6.54c1.95 0 3.38.77 4.43 1.76L15.4 2.5C13.94 1.08 11.98 0 9.24 0 4.28 0 .11 4.04.11 9s4.17 9 9.13 9c2.68 0 4.7-.88 6.28-2.52 1.62-1.62 2.13-3.91 2.13-5.75 0-.57-.04-1.1-.13-1.54H9.24zm15.76-2c-3.21 0-5.83 2.44-5.83 5.81 0 3.34 2.62 5.81 5.83 5.81s5.83-2.46 5.83-5.81c0-3.37-2.62-5.81-5.83-5.81zm0 9.33c-1.76 0-3.28-1.45-3.28-3.52 0-2.09 1.52-3.52 3.28-3.52s3.28 1.43 3.28 3.52c0 2.07-1.52 3.52-3.28 3.52zm28.58-8.03h-.09c-.57-.68-1.67-1.3-3.06-1.3C47.53 6.19 45 8.72 45 12c0 3.26 2.53 5.81 5.43 5.81 1.39 0 2.49-.62 3.06-1.32h.09v.81c0 2.22-1.19 3.41-3.1 3.41-1.56 0-2.53-1.12-2.93-2.07l-2.22.92c.64 1.54 2.33 3.43 5.15 3.43 2.99 0 5.52-1.76 5.52-6.05V6.49h-2.42v1zm-2.93 8.03c-1.76 0-3.1-1.5-3.1-3.52 0-2.05 1.34-3.52 3.1-3.52 1.74 0 3.1 1.5 3.1 3.54.01 2.03-1.36 3.5-3.1 3.5zM38 6.19c-3.21 0-5.83 2.44-5.83 5.81 0 3.34 2.62 5.81 5.83 5.81s5.83-2.46 5.83-5.81c0-3.37-2.62-5.81-5.83-5.81zm0 9.33c-1.76 0-3.28-1.45-3.28-3.52 0-2.09 1.52-3.52 3.28-3.52s3.28 1.43 3.28 3.52c0 2.07-1.52 3.52-3.28 3.52zM58 .24h2.51v17.57H58zm10.26 15.28c-1.3 0-2.22-.59-2.82-1.76l7.77-3.21-.26-.66c-.48-1.3-1.96-3.7-4.97-3.7-2.99 0-5.48 2.35-5.48 5.81 0 3.26 2.46 5.81 5.76 5.81 2.66 0 4.2-1.63 4.84-2.57l-1.98-1.32c-.66.96-1.56 1.6-2.86 1.6zm-.18-7.15c1.03 0 1.91.53 2.2 1.28l-5.25 2.17c0-2.44 1.73-3.45 3.05-3.45z"
                                                                class="_ngcontent-awn-AWSM-2"></path>
                                                        </svg>
                                                        <span class="lockup-brand _ngcontent-awn-AWSM-2">Ads</span>
                                                    </div>
                                                    <!---->
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            What is YouTube Monetization? YouTube monetization is
                                            defined as your ability to derive income from your videos.
                                            If you're interested in qualifying for YouTube's
                                            monetization program, you need, at minimum, 1,000
                                            subscribers to your channel and 4,000 watch hours over the
                                            past 12 months.
                                        </p>
                                        <a target="_blank" class="text-danger" href="https://ads.google.com/intl/en_IQ/home/">Browse Illustrations on Youtube
                                            &rarr;</a>
                                    </div>
                                </div>
    
                                <!-- Approach -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-white">
                                            Development Approach
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                                                <use xlink:href="#exclamation-triangle-fill" />
                                            </svg>
                                            <div>
                                                These features are only available to partners who use
                                                YouTube Studio Content Manager.
                                            </div>
                                        </div>
                                        <h5><b>Policy requirements:</b></h5>
                                        <ul>
                                            <li>
                                                Content managers are prohibited from engaging in
                                                practices that attempt to go around or interfere with
                                                YouTube’s systems, processes, or policies.
                                            </li>
                                            <li>
                                                Violating this policy may be considered egregious abuse,
                                                and may result in termination of your entire content
                                                owner family.
                                            </li>
                                        </ul>
                                        <h5>
                                            <b>
                                                Complete the steps below to get your account set for
                                                your first AdSense payment.
                                            </b>
                                        </h5>
                                        <ol>
                                            <li>
                                                Provide your tax information. Depending on your
                                                location, we may be required to collect tax-related
                                                information.
                                            </li>
                                            <li>Confirm your personal information</li>
                                            <li>Select your form of payment.</li>
                                            <li>Meet the payment threshold.</li>
                                        </ol>
                                        <small>need Help
                                            <a class="text-danger" href="https://support.google.com/youtube/answer/1709858?hl=en">Steps to getting paid</a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  <!-- End of Main Content -->

            
                  <script>
                    document.addEventListener("DOMContentLoaded", function () {
                      var ctx = document.getElementById("myAreaChart");
                      var myLineChart;
                  
                      function updateChart(yearData) {
                        if (myLineChart) {
                          myLineChart.destroy();
                        }
                  
                        myLineChart = new Chart(ctx, {
                          type: "line",
                          data: {
                            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            datasets: [
                              {
                                label: "Earnings",
                                data: yearData,
                                tension: 0.3,
                                borderColor: "#cc0022",
                                pointRadius: 3,
                                pointBackgroundColor: "#fff",
                                pointBorderColor: "#fff",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(204, 0, 34, 1)",
                                pointHoverBorderColor: "rgba(204, 0, 34, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                              },
                            ],
                          },
                          options: {
                            maintainAspectRatio: false,
                            layout: {
                              padding: {
                                left: 10,
                                right: 25,
                                top: 25,
                                bottom: 0,
                              },
                            },
                            scales: {
                              x: {
                                grid: {
                                  display: false,
                                  drawBorder: false,
                                },
                                ticks: {
                                  color: "#ffffff",
                                  maxTicksLimit: 12,
                                },
                              },
                              y: {
                                maxTicksLimit: 5,
                                padding: 10,
                                color: "white",
                                ticks: {
                                  color: "#ffffff",
                                  maxTicksLimit: 12,
                                  callback: function (value, index, values) {
                                  return "$" + value.toFixed(2);
                                },
                                },
                                grid: {
                                  color: "rgb(234, 236, 244)",
                                  borderColor: "rgb(234, 236, 244)",
                                  drawBorder: false,
                                  borderDash: [2],
                                  borderDashOffset: [2],
                                },
                              },
                            },
                            plugins: {
                              legend: {
                                display: false,
                              },
                              tooltip: {
                                backgroundColor: "#333",
                                bodyFontColor: "#eee",
                                titleFontColor: "#eee",
                                borderColor: "#eee",
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: "index",
                                caretPadding: 10,
                                callbacks: {
                                  label: function (context) {
                                    var label = context.dataset.label || "";
                                    if (label) {
                                      label += ": ";
                                    }
                                    if (context.parsed.y !== null) {
                                      label += "$" + context.parsed.y.toFixed(2);
                                    }
                                    return label;
                                  },
                                },
                              },
                            },
                          },
                        });
                      }
                  
                      // Initialize the chart with the first year's data
                      var sortedData = {!! json_encode($sortedData) !!};
                      var years = Object.keys(sortedData);
                      updateChart(sortedData[years[0]]);
                  
                      // Add event listener for year selection
                      document.getElementById("yearSelect").addEventListener("change", function () {
                        var selectedYear = this.value;
                        var selectedYearData = sortedData[selectedYear] || [];
                        updateChart(selectedYearData);
                      });
                    });
                  </script>
                  
                  
{{-- @endsection --}}
@endsection