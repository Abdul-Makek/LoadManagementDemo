@extends('layouts.app')
 
@section('title', 'Loads')

@section('nav')
    @include('left_nav')
@endsection


@section('content')
<div class="container">
    <div class="row mb-1">
        <div class="col d-flex justify-content-end p-0">
            <div class="me-2">
                <select class="form-control form-control-sm query-option" id="gridDropdown" style="min-width: 120px;">
                    @foreach($grids as $grid)
                    <option value="{{ $grid->id }}"> {{  $grid->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="">
                <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm query-option" id="loadDate">
            </div>
        </div>
    </div>

    <input type="hidden" name="currentLoadDate" value="{{ $date }}" id="hidCurrentLoadDate">

    <div id="loadDetailsContainer">
    @include('Load.loadDetails', ['loads' => $loads])
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            $('#loadDate').val($('#hidCurrentLoadDate').val());
        }

        $('#loadDetailsContainer').on('click', '.btn-load-edit', function(){
            let btnEdit = $(this);
            let id = btnEdit.attr('data-id');
            let parentDiv = btnEdit.parent().parent();
            let input = parentDiv.find('input');
            let span = parentDiv.find('span');

            if(btnEdit.attr('data-status') === 'edit'){
                btnEdit.attr('data-status', 'save');

                $(span).hide();
                $(input).show();
                $(btnEdit).find('.icon-edit').hide();
                $(btnEdit).find('.icon-save').show();

                btnEdit.next().next().hide();
                btnEdit.next().show();
            }else{
                let load_date = parentDiv.find('input.input-time').val();
                let load_demand = parseInt(parentDiv.find('input.input-demand').val());
                let load_supply = parseInt(parentDiv.find('input.input-supply').val());

                if(Number.isNaN(load_demand)){
                    showToastMessage('failed', "Provided load demand is invalid.");
                    return;
                }

                if(load_demand<=0){
                    showToastMessage('failed', "Demand must be greater than 0.");
                    return;
                }

                if(Number.isNaN(load_demand)){
                    showToastMessage('failed', "Provided load supply is invalid.");
                    return;
                }

                if(load_demand<=0){
                    showToastMessage('failed', "Supply must be greater than or equal to 0.");
                    return;
                }

                if(load_demand < load_supply){
                    showToastMessage('failed', "Supply value cannot be greater than demand value.");
                    return;
                }

                // validate date

                $('#spinner').show();

                $.ajax({
                    url: "{{ route('load_update')}}",
                    type: "POST",
                    data: {"_token": "{{ csrf_token() }}", "time" : load_date,"grid_id": $("#gridDropdown").val(), "load_demand" : load_demand, "load_supply" : load_supply},
                    success: function(response){
                        $('#spinner').hide();
                        if(response.success){
                            showToastMessage('success', response.msg);
                        }else{
                            showToastMessage('failed', response.msg);
                        }

                        $('.query-option:first').change();
                    },
                    error: function(response){
                        $('#spinner').hide();
                        console.log(response);
                    }
                });
            }
        });

        $('#loadDetailsContainer').on('click', '.btn-load-close-update', function(){
            let btnCloseEdit = $(this);
            let btnEdit = $(this).prev();

            let parentDiv = btnCloseEdit.parent().parent();
            let input = parentDiv.find('input');
            let span = parentDiv.find('span');

            btnEdit.attr('data-status', 'edit');

            $(input).hide();
            $(span).show();         
            $(btnEdit).find('.icon-save').hide();
            $(btnEdit).find('.icon-edit').show();

            btnCloseEdit.hide();
            btnCloseEdit.next().show();
        })
        
        $('.query-option').on('change', function(){
            $('#spinner').show();
            
            $.ajax({
                    url: "{{ route('load_details')}}",
                    type: "GET",
                    data: {"grid_id" : $("#gridDropdown").val(), "load_date": $('#loadDate').val()},
                    success: function(response){
                        $('#loadDetailsContainer').empty();
                        $('#loadDetailsContainer').append(response);
                        $('#spinner').hide();
                    },
                    error: function(response){
                        $('#spinner').hide();
                        console.log(response);
                    }
            });
        });

        $('#loadDetailsContainer').on('click', '.btn-load-delete', function(){
            let load_id = parseInt($(this).attr('data-id'));

            if(Number.isNaN(load_id)){
                showToastMessage('failed', "Load is invalid.");
                return;
            }

            if(window.confirm("Are you sure you want to delete this load?")){
                $('#spinner').show();
                
                $.ajax({
                    url: "{{ route('load_delete')}}",
                    type: "GET",
                    data: {"load_id" : load_id, "grid_id": $('#gridDropdown').val()},
                    success: function(response){
                        $('#spinner').hide();
                        
                        if(response.success){
                            showToastMessage('success', response.msg);
                        }else{
                            showToastMessage('failed', response.msg);
                        }
                        $('.query-option:first').change();
                    },
                    error: function(response){
                        $('#spinner').hide();
                        console.log(response);
                    }
                });
            }
        });

    });
</script>
@endsection