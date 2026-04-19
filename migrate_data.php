<?php

use App\Models\Module;
use App\Models\SubModule;

$modules = Module::all();

foreach ($modules as $module) {
    if (!empty($module->content)) {
        // Create a submodule from the existing content
        SubModule::firstOrCreate(
            ['module_id' => $module->id, 'order' => 1],
            [
                'title' => 'Materi: ' . $module->title,
                'content' => $module->content,
            ]
        );
    }
}

// Assign order to existing tasks
$tasks = \App\Models\Task::all();
$tasksByModule = $tasks->groupBy('module_id');

foreach ($tasksByModule as $moduleId => $moduleTasks) {
    // SubModule is order 1. Tasks will start from order 2.
    $order = 2;
    foreach ($moduleTasks as $task) {
        if ($task->order == 0) {
            $task->order = $order++;
            $task->save();
        }
    }
}

echo "Migrasi Data Selesai!\n";
