@extends('layouts.app')
 
@section('title', 'Grids')

@section('nav')
    @include('left_nav')
@endsection


@section('content')
<div class="container">
    <div class="row mb-1">
        <div class="col-12 d-flex justify-content-end">
            <button class="btn btn-sm p-2" id="btnAddGrid" data-type="add" data-bs-toggle="collapse" title="Add Grid" data-bs-target="#addGridForm" aria-expanded="false" aria-controls="">
                <i class="fa fa-plus"></i>
            </button>
        </div>
        <div class="collapse mb-2" id="addGridForm">
            <div class="d-flex justify-content-between">
                <input type="text" class="form-control me-3" id="gridName" name="grid_name" max="60" value="" placeholder="Grid Name">
                <button id="btnSaveGrid" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 border-top border-bottom d-flex p-2 mb-2 bg-body-tertiary">
            <div class="flex-grow-1 pe-4">
                <p class="mb-0 mt-1 ps-2" style="font-weight: bold;">Grid Name</p>
            </div>
            <div style="padding-right: 18px; font-weight: bold;">
                Action
            </div>
        </div>
        @foreach($grids as $grid)
        <div class="col-12 border-bottom d-flex pb-2 mb-2 grid-row">
            <div class="flex-grow-1 pe-4">
                <p class="mb-0 ps-2">{{ $grid->name }}</p>
                <input type="text" class="form-control form-control-sm input-grid-name" name="" value="{{ $grid->name }}" style="display: none;">
            </div>
            <div>
                <button class="btn btn-success btn-sm btn-grid-edit" title="Update Grid" data-status="edit" data-id="{{ $grid->id}}">
                    <i class="fa fa-edit icon-edit"></i>
                    <i class="fa fa-save icon-save" style="display: none;"></i>
                </button>
                <button class="btn btn-danger btn-sm btn-grid-close-update hide" title="Close"><i class="fa fa-times"></i></button>
                <button class="btn btn-danger btn-sm btn-grid-delete" data-id="{{ $grid->id }}" title="Delete Grid"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col d-flex justify-content-center">
            {{ $grids->links() }}
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        $('.btn-grid-edit').on('click', function(){
            let btnEdit = $(this);
            let id = btnEdit.attr('data-id');
            let parentDiv = btnEdit.parent().parent();
            let input = parentDiv.find('input')[0];
            let p = parentDiv.find('p')[0];

            if(btnEdit.attr('data-status') === 'edit'){
                btnEdit.attr('data-status', 'save');

                $(p).hide();
                $(input).show();
                $(btnEdit).find('.icon-edit').hide();
                $(btnEdit).find('.icon-save').show();

                btnEdit.next().next().hide();
                btnEdit.next().show();
            }else{
                let girdName = $(input).val();
                
                if(!gridNameValidation(girdName)){
                    hideUpdateInputField('failed', p, input, btnEdit)
                }

                $('#spinner').show();

                $.ajax({
                    url: "{{ route('grid_update')}}",
                    type: "POST",
                    data: {"_token": "{{ csrf_token() }}", "grid_id" : id, "grid_name" : girdName},
                    success: function(response){
                        $('#spinner').hide();
                        
                        if(response.success == true){                                                              
                            sessionStorage.toastType = 'success';
                            sessionStorage.toastMsg = response.msg;
                            location.reload();
                        }else if(response.success == false){                           
                            $errors = response.errors.grid_name;
                            hideUpdateInputField('failed', p, input, btnEdit);
                            
                            if($errors.length > 0){
                                showToastMessage('alert', $errors[0]);
                            }else{
                                showToastMessage('alert', response.msg);
                            }
                        }

                        btnEdit.next().hide();
                        btnEdit.next().next().show();
                    },
                    error: function(response){
                        $('#spinner').hide();
                        console.log(response);
                    }
                });
            }
        });

        $('.btn-grid-close-update').on('click', function(){
            let btnCloseEdit = $(this);
            let btnEdit = $(this).prev();

            let parentDiv = btnCloseEdit.parent().parent();
            let input = parentDiv.find('input')[0];
            let p = parentDiv.find('p')[0];

            btnEdit.attr('data-status', 'edit');

            $(input).hide();
            $(p).show();
            $(input).val($(p).text())
            $(btnEdit).find('.icon-save').hide();
            $(btnEdit).find('.icon-edit').show();

            btnCloseEdit.hide();
            btnCloseEdit.next().show();
        })

        $('#btnAddGrid').on('click', function(){
            let btnAddGrid = $('#btnAddGrid');
            let icon = btnAddGrid.find('i');
            icon.remove();

            if(btnAddGrid.attr('data-type') == 'add'){
                btnAddGrid.attr('data-type', 'close');
                btnAddGrid.prepend('<i class="fa fa-times"></i>');
            }else{              
                btnAddGrid.attr('data-type', 'add');
                btnAddGrid.prepend('<i class="fa fa-plus"></i>');
            }
        });

        $('.btn-grid-delete').on('click', function(){
            if(window.confirm("Are you sure you want to delete this grid?")){
                $('#spinner').show();
            
                $.ajax({
                        url: "{{ route('grid_delete')}}",
                        type: "GET",
                        data: {"grid_id" : $(this).attr('data-id')},
                        success: function(response){
                            $('#spinner').hide();
                            
                            if(response.status === 'success'){                                                              
                                sessionStorage.toastType = 'success';
                                sessionStorage.toastMsg = response.msg;
                                location.reload();
                            }else if(response.status === 'alert'){
                                showToastMessage('alert', response.msg);
                            }
                        },
                        error: function(response){
                            $('#spinner').hide();
                            console.log(response);
                        }
                });
            }
        });

        $('#btnSaveGrid').on('click', function(){
            let gridName = $('#gridName').val();
            if(!gridNameValidation(gridName)){
                return;
            }            

            $('#spinner').show();

            $.ajax({
                url: "{{ route('grid_store')}}",
                type: "POST",
                data: {"_token": "{{ csrf_token() }}" ,"grid_name" : gridName},
                success: function(response){
                    $('#spinner').hide();
                    if(response.success == true){
                        sessionStorage.toastMsg = response.msg;
                        location.reload();
                    }else if(response.success == false){
                        $errors = response.errors.grid_name;
                        if($errors.length > 0)
                            showToastMessage('alert', $errors[0]);
                    }                   
                },
                error: function(response){
                    $('#spinner').hide();
                    console.log(response);
                }
            });
        });

        function gridNameValidation(gridName){
            if(gridName == ""){
                showToastMessage('danger', "Grid name can not be empty.");
                return false;
            }
            if(gridName.length < 3){
                showToastMessage('danger', "Grid name can not be less than 3 character.");
                return false;
            }
            if(gridName.length > 60){
                showToastMessage('danger', "Grid name can not be greater than 3 character.");
                return false;
            }
            
            return true;
        }

        function hideUpdateInputField(status, p, input, btnEdit){
        
            if(status == 'success'){
                $(p).text($(input).val());
            }else{
                $(input).val($(p).text());
            }

            $(input).hide();
            $(p).show();                   
            $(input).val($(p).text());
            $(btnEdit).find('.icon-edit').show();
            $(btnEdit).find('.icon-save').hide();
            btnEdit.attr('data-status', 'edit');
        }
    });
</script>
@endsection