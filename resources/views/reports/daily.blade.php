@extends('layouts.app')
 
@section('title', 'Page Title')

@section('nav')
    @include('left_nav')
@endsection

@section('content')

<div class="container">
    <div class="row mb-1">
        <div class="col d-flex justify-content-end p-0">
            <div class="" id="filterContainer">
                <input type="date" name="date" value="{{ $report_date }}" class="form-control form-control-sm" id="reportDate">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col grid-group p-0">
            <?php $rowNo = 0; ?>
            <table class="table table-striped table-bordered text-center table-sm">
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;">Time</th>
                        @foreach($grids as $grid)
                        <th colspan="3">{{ $grid->name }}</th>
                        @endforeach
                        <th colspan="3">Total PBS</th>
                        <th rowspan="2" style="vertical-align: middle;">%</th>
                    </tr>
                    <tr>
                        @foreach($grids as $grid)
                        <th title="Demand">D</th>
                        <th title="Supply">S</th>
                        <th title="Loadshedding">L</th>
                        @endforeach
                        <th title="Total Demand">D</th>
                        <th title="Total Supply">S</th>
                        <th title="Total Loadshedding">L</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataTable as $key => $dataRow)
                    <tr class="data-table-row">
                        <?php
                            $totalDemand = 0;
                            $totalSupply = 0;
                            $percent_loadshedding = 0;
                        ?>
                    
                        <td>{{ Carbon\Carbon::parse($key)->format('H:i') }}</td>
                        @foreach($dataRow as $value)
                            <td title="Demand" class="demand{{$rowNo}}">{{$value['grid_demand']}}</td>
                            <td title="Supply" class="supply{{$rowNo}}">{{$value['grid_supply']}}</td>
                            <td title="Loadshedding">{{ intval($value['grid_demand']) - intval($value['grid_supply'])}}</td>

                            <?php
                                if($value['grid_demand'] != "-"){
                                    $totalDemand += intval($value['grid_demand']);
                                    $totalSupply += intval($value['grid_supply']);
                                    
                                    if($totalDemand>0)
                                        $percent_loadshedding = number_format((($totalDemand - $totalSupply)*100)/$totalDemand, 2);
                                }                           
                            ?>

                        @endforeach
                            <td title="Total Demand" class="total_demand{{$rowNo}}">{{ $totalDemand }}</td>
                            <td title="Total Supply" class="total_supply{{$rowNo}}">{{ $totalSupply }}</td>
                            <td title="Total Loadshedding" class="total_loadshedding{{$rowNo}}">{{ $totalDemand - $totalSupply }}</td>
                            <td title="% of Loadshedding" class="percent_loadshedding{{$rowNo++}}"> {{ $percent_loadshedding }} %</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>
@endsection

@section('script')
<script>
    $('#reportDate').on('change', function(){
        let url = '{{ route('daily_report')}}/';

        window.location = url + $(this).val();
    });
</script>
@endsection