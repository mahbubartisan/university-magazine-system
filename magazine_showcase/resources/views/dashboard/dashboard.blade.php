@extends('layouts.back_end')

@section('content')

    @if(Auth::user()->user_type == 'Administrator' || Auth::user()->user_type == 'Marketing Manager')
{{--        <h1 style="border-bottom: 1px solid #ccc;">General Info.</h1><br>--}}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Administrator"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Users</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($users)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Computer-Secure"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Faculties</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($faculties)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Check"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Events</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($cont_settings)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Library"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Contributions</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($contributions)}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Contributions of last 7 days</div>
                        @if(count($contributions) < 1)
                            Nothing to show!
                        @else
                            <div id="last_7_day_contributions" style="width: 100%; height: 300px;"></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Contributions by %</div>
                        <div class="text-center alert alert-primary">Total Contribution: {{count($contributions)}}</div>
                        @if(count($contributions) < 1)
                            Nothing to show!
                        @else
                            <div id="total_contribution_by_department_chart" style="width: 100%; height: 300px;"></div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Contribution Status</div>
                        <div class="row pr-3 pl-3">
                            <div class="col-md-4 text-center alert alert-success">Approved: {{count($contributions->where('status','Approved'))}}</div>
                            <div class="col-md-4 text-center alert alert-danger">Disapproved: {{count($contributions->where('status','Disapproved'))}}</div>
                            <div class="col-md-4 text-center alert alert-warning">Pending: {{count($contributions->where('status','Pending'))}}</div>
                        </div>
                        @if(count($contributions) < 1)
                            Nothing to show!
                        @else
                            <div id="approved_disapproved_pending_chart" style="width: 100%; height: 300px;"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
{{--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>--}}
<script type="text/javascript">

    google.charts.load("current", {packages:["corechart"]});

    //donut chart

    google.charts.setOnLoadCallback(drawDonutChart);
    function drawDonutChart() {
        var data = google.visualization.arrayToDataTable([
            ['Name', 'Value'],
            <?php
            foreach($faculties as $faculty){
                echo "['".$faculty->name."',".count($contributions->where("faculty_id","=",$faculty->id))."],";
            }
            ?>
        ]);

        var options = {
            pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('total_contribution_by_department_chart'));
        chart.draw(data, options);
    }

    //pie chart

    google.charts.setOnLoadCallback(drawPieChart);
    function drawPieChart() {
        var data = google.visualization.arrayToDataTable([
            ['Status', 'Value'],
            ['Approved', <?php echo count($contributions->where('status','Approved')); ?>],
            ['Disapproved', <?php echo count($contributions->where('status','Disapproved')); ?>],
            ['Pending', <?php echo count($contributions->where('status','Pending')); ?>],
        ]);

        var options = {
            pieHole: 0,
        };

        var chart = new google.visualization.PieChart(document.getElementById('approved_disapproved_pending_chart'));
        chart.draw(data, options);
    }

    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Element", "Density", {role: "style"}],
            /*["","",""]*/

            [
                "<?php echo date ("d-m-Y", strtotime("-6 day", strtotime(date(now())))); ?>",
                <?php
                    echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-6 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-6 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],
            [
                "<?php echo date ("d-m-Y", strtotime("-5 day", strtotime(date(now())))); ?>",
                <?php
                echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-5 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-5 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],
            [
                "<?php echo date ("d-m-Y", strtotime("-4 day", strtotime(date(now())))); ?>",
                <?php
                echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-4 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-4 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],
            [
                "<?php echo date ("d-m-Y", strtotime("-3 day", strtotime(date(now())))); ?>",
                <?php
                echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-3 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-3 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],
            [
                "<?php echo date ("d-m-Y", strtotime("-2 day", strtotime(date(now())))); ?>",
                <?php
                echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-2 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-2 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],
            [
                "<?php echo date ("d-m-Y", strtotime("-1 day", strtotime(date(now())))); ?>",
                <?php
                echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-1 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-1 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],
            [
                "<?php echo date ("d-m-Y", strtotime("-0 day", strtotime(date(now())))); ?>",
                <?php
                echo count($contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-0 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-0 day", strtotime(date(now()))))." 24:00:00"]));
                ?>,
                "#639"
            ],

        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {
                calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation",
            },
            2]);

        var options = {
            /*title: "Density of Precious Metals, in g/cm^3",
            width: 600,
            height: 400,*/
            vAxis: {format: '0'},
            beginAtZero: true,
            bar: {groupWidth: "95%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("last_7_day_contributions"));
        chart.draw(view, options);
    }

</script>

    @endif

    @if(Auth::user()->user_type == 'Marketing Coordinator' || Auth::user()->user_type == 'Guest')
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Add"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Contributions</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Approved-Window"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Approved</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications->where('status','=','Approved'))}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Danger"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Disapproved</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications->where('status','=','Disapproved'))}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Loading-2"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Pending</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications->where('status','=','Pending'))}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Contributions of last 7 days</div>
                        <div class="col-md-12 text-center alert alert-primary">Contributions so far: {{count($notifications)}}</div>
                        @if(count($notifications) < 1)
                            Nothing to show!
                        @else
                            <div id="last_7_day_contributions" style="width: 100%; height: 300px;"></div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-sm-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="card-title">Contribution Status</div>
                        <div class="row pr-3 pl-3">
                            <div class="col-md-4 text-center alert alert-success">Approved: {{count($notifications->where('status','Approved'))}}</div>
                            <div class="col-md-4 text-center alert alert-danger">Disapproved: {{count($notifications->where('status','Disapproved'))}}</div>
                            <div class="col-md-4 text-center alert alert-warning">Pending: {{count($notifications->where('status','Pending'))}}</div>
                        </div>
                        @if(count($notifications) < 1)
                            Nothing to show!
                        @else
                            <div id="approved_disapproved_pending_chart" style="width: 100%; height: 300px;"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">

            google.charts.load("current", {packages:["corechart"]});

            //pie chart
            google.charts.setOnLoadCallback(drawPieChart);
            function drawPieChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Status', 'Value'],
                    ['Approved', <?php echo count($notifications->where('status','Approved')); ?>],
                    ['Disapproved', <?php echo count($notifications->where('status','Disapproved')); ?>],
                    ['Pending', <?php echo count($notifications->where('status','Pending')); ?>],
                ]);

                var options = {
                    pieHole: 0,
                };

                var chart = new google.visualization.PieChart(document.getElementById('approved_disapproved_pending_chart'));
                chart.draw(data, options);
            }

            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ["Element", "Density", {role: "style"}],
                    /*["","",""]*/

                    [
                        "<?php echo date ("d-m-Y", strtotime("-6 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-6 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-6 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],
                    [
                        "<?php echo date ("d-m-Y", strtotime("-5 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-5 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-5 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],
                    [
                        "<?php echo date ("d-m-Y", strtotime("-4 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-4 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-4 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],
                    [
                        "<?php echo date ("d-m-Y", strtotime("-3 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-3 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-3 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],
                    [
                        "<?php echo date ("d-m-Y", strtotime("-2 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-2 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-2 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],
                    [
                        "<?php echo date ("d-m-Y", strtotime("-1 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-1 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-1 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],
                    [
                        "<?php echo date ("d-m-Y", strtotime("-0 day", strtotime(date(now())))); ?>",
                        <?php
                        echo count($notifications->whereBetween('created_at', [date ("Y-m-d", strtotime("-0 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-0 day", strtotime(date(now()))))." 24:00:00"]));
                        ?>,
                        "#639"
                    ],

                ]);

                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,
                    {
                        calc: "stringify",
                        sourceColumn: 1,
                        type: "string",
                        role: "annotation",
                    },
                    2]);

                var options = {
                    /*title: "Density of Precious Metals, in g/cm^3",
                    width: 600,
                    height: 400,*/
                    vAxis: {format: '0'},
                    beginAtZero: true,
                    bar: {groupWidth: "95%"},
                    legend: {position: "none"},
                };
                var chart = new google.visualization.ColumnChart(document.getElementById("last_7_day_contributions"));
                chart.draw(view, options);
            }

        </script>
    @endif

        @if(Auth::user()->user_type == 'Student')

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Add"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Contributions</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{count($student_contributions)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Approved-Window"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Approved</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{count($student_contributions->where('status','=','Approved'))}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Danger"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Disapproved</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{count($student_contributions->where('status','=','Disapproved'))}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Loading-2"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">Pending</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{count($student_contributions->where('status','=','Pending'))}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Contributions of last 7 days</div>
                            <div class="col-md-12 text-center alert alert-primary">Contributions so far: {{count($student_contributions)}}</div>
                            @if(count($student_contributions) < 1)
                                Nothing to show!
                            @else
                                <div id="last_7_day_contributions" style="width: 100%; height: 300px;"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Contribution Status</div>
                            <div class="row pr-3 pl-3">
                                <div class="col-md-4 text-center alert alert-success">Approved: {{count($student_contributions->where('status','Approved'))}}</div>
                                <div class="col-md-4 text-center alert alert-danger">Disapproved: {{count($student_contributions->where('status','Disapproved'))}}</div>
                                <div class="col-md-4 text-center alert alert-warning">Pending: {{count($student_contributions->where('status','Pending'))}}</div>
                            </div>
                            @if(count($student_contributions) < 1)
                                Nothing to show!
                            @else
                                <div id="approved_disapproved_pending_chart" style="width: 100%; height: 300px;"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">

                google.charts.load("current", {packages:["corechart"]});

                //pie chart
                google.charts.setOnLoadCallback(drawPieChart);
                function drawPieChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Status', 'Value'],
                        ['Approved', <?php echo count($student_contributions->where('status','Approved')); ?>],
                        ['Disapproved', <?php echo count($student_contributions->where('status','Disapproved')); ?>],
                        ['Pending', <?php echo count($student_contributions->where('status','Pending')); ?>],
                    ]);

                    var options = {
                        pieHole: 0,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('approved_disapproved_pending_chart'));
                    chart.draw(data, options);
                }

                google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ["Element", "Density", {role: "style"}],
                        /*["","",""]*/

                        [
                            "<?php echo date ("d-m-Y", strtotime("-6 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-6 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-6 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],
                        [
                            "<?php echo date ("d-m-Y", strtotime("-5 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-5 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-5 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],
                        [
                            "<?php echo date ("d-m-Y", strtotime("-4 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-4 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-4 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],
                        [
                            "<?php echo date ("d-m-Y", strtotime("-3 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-3 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-3 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],
                        [
                            "<?php echo date ("d-m-Y", strtotime("-2 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-2 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-2 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],
                        [
                            "<?php echo date ("d-m-Y", strtotime("-1 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-1 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-1 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],
                        [
                            "<?php echo date ("d-m-Y", strtotime("-0 day", strtotime(date(now())))); ?>",
                            <?php
                            echo count($student_contributions->whereBetween('created_at', [date ("Y-m-d", strtotime("-0 day", strtotime(date(now()))))." 00:00:00",date ("Y-m-d", strtotime("-0 day", strtotime(date(now()))))." 24:00:00"]));
                            ?>,
                            "#639"
                        ],

                    ]);

                    var view = new google.visualization.DataView(data);
                    view.setColumns([0, 1,
                        {
                            calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation",
                        },
                        2]);

                    var options = {
                        /*title: "Density of Precious Metals, in g/cm^3",
                        width: 600,
                        height: 400,*/
                        vAxis: {format: '0'},
                        beginAtZero: true,
                        bar: {groupWidth: "95%"},
                        legend: {position: "none"},
                    };
                    var chart = new google.visualization.ColumnChart(document.getElementById("last_7_day_contributions"));
                    chart.draw(view, options);
                }

            </script>
        @endif
    {{--@if(Auth::user()->user_type == 'Guest')
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Add"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Contributions</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Approved-Window"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Approved</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications->where('status','=','Approved'))}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Danger"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Disapproved</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications->where('status','=','Disapproved'))}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-primary o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="i-Loading-2"></i>
                        <div class="content">
                            <p class="text-muted mt-2 mb-0">Pending</p>
                            <p class="text-primary text-24 line-height-1 mb-2">{{count($notifications->where('status','=','Pending'))}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif--}}

    <br><br>



@endsection
