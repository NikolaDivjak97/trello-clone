<div class="modal fade" id="addLabelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addListModalLabel">Add New Label</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addListForm" action="{{ route('labels.store') }}" method="POST">

                @csrf

                <input type="hidden" name="label_board_id" id="label_board_id" value="{{ $board -> id }}">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="label_name">Label Name</label>
                        <input type="text" class="form-control" id="label_name" name="label_name" placeholder="Enter label name">
                    </div>

                    <div class="form-group">
                        <label for="label_color">Label color</label>
                        <input type="color" class="form-control" id="label_color" name="label_color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save List</button>
                </div>
            </form>

        </div>
    </div>
</div>
