<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-lg modal-dialog" role="document">
      <div class=" modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New Task</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form action="{{ route ('cards.store') }}" method="POST">
              @csrf
              <div class="modal-body">
                  <!-- Form for adding new task -->
                  <div class="form-group">
                      <label for="taskName">Task Name</label>
                      <input type="text" class="form-control" id="taskName" placeholder="Enter task name">
                  </div>
                  <div class="form-group">
                      <label for="taskDesc">Task Description</label>
                      <textarea class="form-control" id="taskDesc" placeholder="Enter task description"></textarea>
                  </div>
                  <div class="form-group">
                      <label for="members">Members</label>
                      <select name="members[]" id="members" multiple="multiple">
                          @foreach($users as $user)
                          <option value="{{ $user->id }}" @if(auth()->id() === $user->id) selected @endif>{{ $user->name }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="form-group">
                      <label for="difficulty">Difficulty Level</label>
                      <select class="form-control" id="difficulty" name="difficulty">
                          <option value="1">Easy</option>
                          <option value="2">Medium</option>
                          <option value="3">Hard</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label for="labels">Labels</label>
                    <select name="labels[]" id="labels" multiple="multiple">
                        @foreach($board -> labels as $label)
                            <option value="{{ $label->id }}">{{ $label->name }}</option>
                        @endforeach
                    </select>
                </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" id="saveTask" class="btn btn-primary">Save Task</button>
              </div>
          </form>
      </div>
  </div>
</div>
