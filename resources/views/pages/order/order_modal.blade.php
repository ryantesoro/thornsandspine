@if ($order->status == 1)
{!! Form::open(['route' => ['admin.order.deliver', $order->code]]) !!}
<div class="modal fade" id="deliver_confirmation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Deliver Order Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to complete this order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-primary">Yes</button>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}

{!! Form::open(['route' => ['admin.order.return', $order->code]]) !!}
<div class="modal fade" id="return_confirmation" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ask Customer Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="font-weight-bold">Reason for asking the customer to resend images(s):</p>
                <input type="text" name="comment" class="form-control form-control-sm" list="reasons" size="5" />
                <datalist id="reasons">
                    <option>Image quality is bad</option>
                    <option>Image has been tampered</option>
                </datalist>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
@endif