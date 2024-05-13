<div class="modal fade" id="create-board" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Board</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('boards.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Board title</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter title">
                    </div>
                    <div class="form-group">
                        <label for="description">Board description <small>* Not required</small></label>
                        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="members">Members</label>
                        <select name="members[]" id="members" multiple="multiple">
                            @foreach($users as $user)
                                <option value="{{ $user -> id }}" @if(auth()->id() === $user -> id) selected @endif>{{ $user -> name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teams">or Select Members by team</label>
                        <select name="teams[]" id="teams" class="form-control" multiple="multiple">
                            @foreach($teams as $team)
                                <option value="{{ $team -> id }}">{{ $team -> name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-block">
                        <label for="background">Background</label>
                        <input type="file" id="background" name="background" class="file"
                               accept="image/jpeg, image/png, image/jpg">
                        <br/>
                        <output id="images-output" class="mt-3"></output>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
