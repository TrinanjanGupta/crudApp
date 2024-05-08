<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{ url('assets/css/styles.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<title>Task Manager</title>
</head>
<body>
<div class="container">
  <h1>Task Manager</h1>
  <div class="tasks">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
      <table id="taskTable" class="display">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
            @foreach($taskData as $dataTask)
          <tr>
            <td>{{$dataTask->id}}</td>
            <td>{{$dataTask->name}}</td>
            <td>{{$dataTask->description}}</td>
            <td><button type="button" class="editTaskBtn">Edit</button></td>
            <td><button type="button" onclick="confirmDelete({{ $dataTask->id }})" class="deleteTaskBtn">Delete</button></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <button id="addTaskBtn">Add Task</button>
  </div>

  <!-- Modal for adding tasks -->
  <div id="addTaskModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Add Task</h2>
    <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST">
      @csrf
      <label for="name">Task Name:</label>
      <input type="text" id="name" name="name">
      <span id="addTaskNameError" class="error-message"></span> <!-- Error message placeholder -->
      <label for="description">Task Description:</label>
      <textarea id="description" name="description" rows="4"></textarea>
      <button type="submit" id="saveAddTaskBtn">Save Task</button>
    </form>
  </div>
</div>

  <!-- Modal for editing tasks -->
  <div id="editTaskModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Task</h2>
    <form id="editTaskForm" action="{{ route('tasks.update') }}" method="POST">
      @csrf
      @method('PUT') <!-- Use PUT method for editing tasks -->
      <input type="hidden" id="editTaskId" name="editTaskId"> <!-- Hidden input for task ID -->
      <label for="editTaskName">Task Name:</label>
      <input type="text" id="editTaskName" name="editTaskName" >
      <span id="editTaskNameError" class="error-message"></span>
      <label for="editTaskDescription">Task Description:</label>
      <textarea id="editTaskDescription" name="editTaskDescription" rows="4" ></textarea>
      <button type="submit" id="saveEditTaskBtn">Save Task</button>
    </form>
  </div>
</div>

  <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteConfirmationModal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Deletion</h5>
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> --}}
          </div>
          <div class="modal-body">
            Are you sure you want to delete this task?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a id="deleteUserLink" class="btn btn-danger" href="#">Delete</a>
          </div>
        </div>
      </div>
    </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <!-- Other scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <!-- Bootstrap JavaScript -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Initialize DataTable
      $('#taskTable').DataTable();

      // Open add task modal
      $('#addTaskBtn').click(function() {
        $('#addTaskModal').show();
      });

      // Open edit task modal when Edit button is clicked
      $('#taskTable tbody').on('click', '.editTaskBtn', function() {
        $('#editTaskModal').show();
      });

      // Close modals
      $('.close').click(function() {
        $('.modal').hide();
      });


    // Client-side validation for Add Task form
    $('#addTaskForm').submit(function(event) {
      var taskName = $('#name').val();
      var taskDescription = $('#description').val();
      var isValid = true;

      // Validate task name
      if (taskName.trim() === '') {
        $('#addTaskNameError').text('Task name is required');
        $('#addTaskNameError').addClass('error'); // Add error class for styling
        isValid = false;
      } else if (taskName.length > 100) {
        $('#addTaskNameError').text('Task name cannot exceed 100 characters');
        $('#addTaskNameError').addClass('error'); // Add error class for styling
        isValid = false;
      } else {
        $('#addTaskNameError').text('');
        $('#addTaskNameError').removeClass('error'); // Remove error class
      }

      if (!isValid) {
        event.preventDefault(); // Prevent form submission
      }
    });

      // Client-side validation for Edit Task form (similar to Add Task form)
      $('#editTaskForm').submit(function(event) {
        var taskName = $('#editTaskName').val();
        var taskDescription = $('#editTaskDescription').val();
        var isValid = true;

        if (taskName.trim() === '') {
          $('#editTaskNameError').text('Task name is required');
          isValid = false;
        } else if (taskName.length > 100) {
          $('#editTaskNameError').text('Task name cannot exceed 100 characters');
          isValid = false;
        } else {
          $('#editTaskNameError').text('');
        }

        if (!isValid) {
          event.preventDefault(); // Prevent form submission
        }
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Automatically close success alert after 3 seconds
      setTimeout(function() {
        $('.alert-success').fadeOut('slow');
      }, 3000); // 3000 milliseconds = 3 seconds
    });

//function for delete
    function confirmDelete(taskId) {
    // Set the user ID for the delete link in the modal
    document.getElementById("deleteUserLink").href = "{{ url('deleteTasks') }}/" + taskId;

    // Open the modal
    $('#deleteConfirmationModal').modal('show');
  }

  function openEditModal(taskId, taskName, taskDescription) {
    $('#editTaskId').val(taskId); // Set the task ID in the hidden input
    $('#editTaskName').val(taskName); // Set the task name in the input field
    $('#editTaskDescription').val(taskDescription); // Set the task description in the textarea
    $('#editTaskModal').show(); // Show the edit modal
  }

  function closeEditModal() {
    $('#editTaskModal').hide(); // Hide the edit modal
  }

  $('.close').click(function() {
    closeEditModal(); // Close modal when close button is clicked
  });

  // Assume you have a button with class 'editTaskBtn' in your task table rows
  $('#taskTable tbody').on('click', '.editTaskBtn', function() {
    var taskId = $(this).closest('tr').find('td:first').text(); // Get the task ID from the table
    var taskName = $(this).closest('tr').find('td:eq(1)').text(); // Get the task name from the table
    var taskDescription = $(this).closest('tr').find('td:eq(2)').text(); // Get the task description from the table
    openEditModal(taskId, taskName, taskDescription); // Open the edit modal with task details
  });
  </script>
</body>
</html>
