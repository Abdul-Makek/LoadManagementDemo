<div class="row">
    <div class="col d-flex border rounded me-2 pb-2 pt-2" style="background: #eee;">
        <div class="flex-fill text-start">Time</div>
        <div class="flex-fill text-center">Demand</div>
        <div class="flex-fill text-center">Supply</div>
        <div class="flex-fill text-end">Action</div>
    </div>
    <div class="col d-flex border rounded ms-2 pb-2 pt-2" style="background: #eee;">
        <div class="flex-fill text-start">Time</div>
        <div class="flex-fill text-center">Demand</div>
        <div class="flex-fill text-center">Supply</div>
        <div class="flex-fill text-end">Action</div>
    </div>
</div>

@php
    $firstHalf = array_slice($loads, 0, 12);
    $secondHalf = array_slice($loads, 12);
@endphp

<div class="row">
    <div class="col border-start">
        @foreach($firstHalf as $load => $value)
        <div class="row">
            <div class="col d-flex border-bottom me-2 pb-2 pt-2">
                <div class="flex-fill text-start">
                    {{ Carbon\Carbon::parse($load)->format('H:i') }}
                    <input type="hidden" name="load-time" class="input-time" value="{{$load}}">
                </div>
                <div class="flex-fill text-center pe-1">
                    <span class="">{{ $value->grid_demand }}</span>
                    <input type="number" name="" class="form-control form-control-sm hide input-demand" value="{{ $value->grid_demand }}">
                </div>
                <div class="flex-fill text-center ps-1">                   
                    <span class="">{{ $value->grid_supply }}</span>
                    <input type="number" name="" class="form-control form-control-sm hide input-supply" value="{{ $value->grid_supply }}">
                </div>
                <div class="flex-fill text-end">
                    <button class="btn btn-success btn-sm btn-load-edit" title="Update Load" data-status="edit" data-id="{{ $value->id }}">
                        <i class="fa fa-edit icon-edit"></i>
                        <i class="fa fa-save icon-save hide"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-load-close-update hide" title="Close"><i class="fa fa-times"></i></button>
                    <button class="btn btn-danger btn-sm btn-load-delete" data-id="{{ $value->id }}" title="Delete Load"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="col border-end">
        @foreach($secondHalf as $load => $value)
        <div class="row">
            <div class="col d-flex border-bottom ms-2 pb-2 pt-2">
                <div class="flex-fill text-start">
                    {{ Carbon\Carbon::parse($load)->format('H:i') }}
                    <input type="hidden" name="load-time" class="input-time" value="{{$load}}">
                </div>
                <div class="flex-fill text-center pe-1">
                    <span class="">{{ $value->grid_demand }}</span>
                    <input type="number" name="" class="form-control form-control-sm hide input-demand" value="{{ $value->grid_demand }}">
                </div>
                <div class="flex-fill text-center ps-1">                   
                    <span class="">{{ $value->grid_supply }}</span>
                    <input type="number" name="" class="form-control form-control-sm hide input-supply" value="{{ $value->grid_supply }}">
                </div>
                <div class="flex-fill text-end">
                    <button class="btn btn-success btn-sm btn-load-edit" title="Update Load" data-status="edit" data-id="{{ $value->id }}">
                        <i class="fa fa-edit icon-edit"></i>
                        <i class="fa fa-save icon-save hide"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btn-load-close-update hide" title="Close"><i class="fa fa-times"></i></button>
                    <button class="btn btn-danger btn-sm btn-load-delete" data-id="{{ $value->id }}" title="Delete Load"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>