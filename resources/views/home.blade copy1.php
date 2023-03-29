@extends('layouts.app')

@section('content')
<div class="content cardhead">

    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
                <div class="dash-widgetimg">
                    <span><img src="{{URL::asset('admin_asset/img/icons/dash1.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>₹ <span class="counters" data-count="{{$cashcounter['total_purchase_amt']}}">₹
                            {{$cashcounter['total_purchase_amt']}}</span></h5>
                    <h6>Total Purchase Amount</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                    <span><img src="{{URL::asset('admin_asset/img/icons/dash2.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>₹ <span class="counters" data-count="{{$cashcounter['total_sale_amt']}}">₹
                            {{$cashcounter['total_sale_amt']}}</span></h5>
                    <h6>Total Sales Order Amount</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash3">
                <div class="dash-widgetimg">
                    <span><img src="{{URL::asset('admin_asset/img/icons/dash4.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>₹ <span class="counters" data-count="{{$cashcounter['sale_due_amt']}}">₹
                            {{$cashcounter['sale_due_amt']}}</span></h5>
                    <h6>Total Invoice Due</h6>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash2">
                <div class="dash-widgetimg">
                    <span><img src="{{URL::asset('admin_asset/img/icons/dash3.svg')}}" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>₹ <span class="counters" data-count="{{$cashcounter['sale_received_amt']}}">₹
                            {{$cashcounter['sale_received_amt']}}</span></h5>
                    <h6>Total Invoice Received</h6>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count">
                <div class="dash-counts">
                    <h4>{{$counter['customer']}}</h4>
                    <h5>Customers</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
                <div class="dash-counts">
                    <h4>{{$counter['vendor']}}</h4>
                    <h5>Vendors</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
                <div class="dash-counts">
                    <h4>{{$counter['purchaseorder']}}</h4>
                    <h5>Purchase Order</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file-text"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das3">
                <div class="dash-counts">
                    <h4>{{$counter['salesorder']}}</h4>
                    <h5>Sales Order</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Raw Material Inventory Details</div>
                </div>
                <div class="card-body chart-set">
                    <div class="h-250" id="inventoryPie"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Manufacture Order</div>
                </div>
                <div class="card-body chart-set">
                    <div class="h-250" id="ManufactureOrderDount"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Purchase Order Material Details</div>
                </div>
                <div class="card-body chart-set">
                    <div class="h-250" id="purchaseDount"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Sales Receivable Details</div>
                </div>
                <div class="card-body chart-set">
                    <div class="h-250" id="InvoiceDount"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">Today Raw Material Stock Activity</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>StockIn</th>
                            <th>StockOut</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayStockActivity['RawMaterial'] as $k1=>$v1)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{RawMaterialName($v1->raw_id)->code}}</td>
                            <td>{{RawMaterialName($v1->raw_id)->name}}</td>
                            <td>{{$v1->stockIn}}</td>
                            <td>{{$v1->stockOut}}</td>
                            <td>{{RawMaterialName($v1->raw_id)->quantity}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Today Product Stock Activity</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>StockIn</th>
                            <th>StockOut</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayStockActivity['Product'] as $k1=>$v1)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{ProductDetail($v1->product_id)->sku}}</td>
                            <td>{{ProductDetail($v1->product_id)->name}}</td>
                            <td>{{$v1->stockIn}}</td>
                            <td>{{$v1->stockOut}}</td>
                            <td>{{ProductDetail($v1->product_id)->quantity}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Raw Material Requirement</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table datanew">
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Available Stock</th>
                            <th>Requirement</th>
                            <th>Open PO</th>
                            <th>Need to Buy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($MaterialRequiement as $k1=>$v1)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{RawMaterialName($v1->raw_id)->code}}</td>
                            <td>{{RawMaterialName($v1->raw_id)->name}}</td>
                            <td>{{$v1->Instock}}</td>
                            <td>{{$v1->requirement}}</td>
                            <td>{{$v1->pending_po}}</td>
                            <td>{{$v1->new_po}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Purchase & Sales</h5>
                    <div class="graph-sets">
                        <ul>
                            <li>
                                <span>Sales</span>
                            </li>
                            <li>
                                <span>Purchase</span>
                            </li>
                        </ul>
                        <div class="dropdown">
                            <button class="btn btn-white btn-sm dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                2022 <img
                                    src="https://dreamspos.dreamguystech.com/laravel/template/public/assets/img/icons/dropdown.svg"
                                    alt="img" class="ms-2">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2020</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sales_charts"></div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
    $(function() {
        'use strict';
        var options = {
            series: [<?php echo $RMRC['Value'] ?>],
            chart: {
                type: 'pie',
                height: '300',
            },
            labels: [<?php echo $RMRC['Key'] ?>],
            dataLabels: {
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            legend: {
                position: 'top'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#inventoryPie"), options);
        chart.render();

        var optionsPO = {
            series: [<?php echo $POQuantity['Value'] ?>],
            chart: {
                type: 'donut',
                height: '300',
            },
            labels: [<?php echo $POQuantity['Key'] ?>],
            dataLabels: {
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            legend: {
                position: 'top'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var purchasechart = new ApexCharts(document.querySelector("#purchaseDount"), optionsPO);
        purchasechart.render();

        var optionsIN = {
            series: [<?php echo $IOQuantity['Value'] ?>],
            chart: {
                type: 'donut',
                height: '300',
            },
            labels: [<?php echo $IOQuantity['Key'] ?>],
            colors: ['#F44336', '#E91E63', '#9C27B0'],
            dataLabels: {
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            legend: {
                position: 'top'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var invoicechart = new ApexCharts(document.querySelector("#InvoiceDount"), optionsIN);
        invoicechart.render();


        var optionsMO = {
            series: [<?php echo $MODATA['Value'] ?>],
            chart: {
                type: 'donut',
                height: '300',
            },
            labels: [<?php echo $MODATA['Key'] ?>],
            colors: ['#f9a3a4', '#90ee7e', '#f48024', '#69d2e7', '#A5978B', '#2b908f'],
            dataLabels: {
                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            legend: {
                position: 'top'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var mochart = new ApexCharts(document.querySelector("#ManufactureOrderDount"), optionsMO);
        mochart.render();


    });
    </script>
    @endsection