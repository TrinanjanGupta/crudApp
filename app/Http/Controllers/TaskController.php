<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'required|max:100',
        
    ]);

    // Create a new task instance
    $task = new Task();
    $task->name = $request->name;
    $task->description = $request->description;
    $task->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Task added successfully!');
}
public function taskView(){
    $taskData = Task::all();
    return view('task',compact('taskData'));
}

public function destroy($id)
{
    // Find the task by ID
    $task = Task::find($id);

    // Delete the task
    $task->delete();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Task deleted successfully!');
}

public function update(Request $request)
{
    $validatedData = $request->validate([
        'editTaskId' => 'required', // Validate the task ID
        'editTaskName' => 'required|max:100', // Validate the task name
        'editTaskDescription' => 'required', // Validate the task description
    ]);

    $task = Task::findOrFail($validatedData['editTaskId']); // Find the task by ID

    $task->name = $validatedData['editTaskName'];
    $task->description = $validatedData['editTaskDescription'];
    $task->save();

    return redirect()->back()->with('success', 'Task updated successfully!'); // Redirect back with success message
}

}
