@if ($pot_details['active'] == 1)
{!! Form::open(['route' => ['admin.pot.destroy', $pot_details['id']]]) !!}
<div class="modal fade" id="hide_confirmation" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hide Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This pot will be hidden from the customers.<br>
                Are you sure you want to hide this pot?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@else
{!! Form::open(['route' => ['admin.pot.restore', $pot_details['id']]]) !!}
<div class="modal fade" id="restore_confirmation" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restore Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                This pot will be shown to the customers.<br>
                Are you sure you want to unhide this pot?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endif