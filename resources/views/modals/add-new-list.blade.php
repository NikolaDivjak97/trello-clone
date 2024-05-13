<div class="modal fade" id="addListModal" tabindex="-1" role="dialog" aria-labelledby="addListModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addListModalLabel">Add New List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addListForm" action="{{ route('phases.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Form for adding a new list -->
                        <div class="form-group">
                            <label for="listName">List Name</label>
                            <input type="text" class="form-control" id="listName" name="name" placeholder="Enter list name">
                            <input type="hidden" class="form-control" id="boardId" name="board_id" value="{{ $board -> id }}">
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