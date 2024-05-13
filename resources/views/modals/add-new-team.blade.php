<div class="modal fade" id="create-team" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('teams.store') }}" method="POST">

                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="team_name">Team name</label>
                        <input type="text" class="form-control" id="team_name" name="team_name" placeholder="Enter team name..">
                    </div>
                    <div class="form-group">
                        <label for="team_description">Team description <small>* Not required</small></label>
                        <textarea name="team_description" id="team_description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="team_members">Team members</label>
                        <select name="team_members[]" id="team_members" multiple="multiple">
                            @foreach($users as $user)
                                <option value="{{ $user -> id }}">{{ $user -> name }}</option>
                            @endforeach
                        </select>
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
