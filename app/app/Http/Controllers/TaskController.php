<?php

namespace App\Http\Controllers;

use App\Jobs\CheckProxy;
use App\Models\Checker\Proxy;
use App\Models\Checker\Task;
use App\Rules\ValidIpAddressWithPort;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function dashboard(): \Inertia\Response
    {
        return Inertia::render('Dashboard');
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $tasks = Task::query()
            ->leftJoin('proxies', function (JoinClause $join) {
                $join->on('tasks.id','=','proxies.task_id');
            })
            ->select('tasks.*')
            ->selectRaw('COUNT(proxies.id) as total,
                                    SUM(CASE WHEN proxies.finished_at IS NOT NULL THEN 1 ELSE 0 END) AS finished,
                                    SUM(CASE WHEN proxies.status = 1 THEN 1 ELSE 0 END) AS worked
            ')
            ->groupBy('tasks.id')
            ->orderBy('tasks.id', 'desc')
            ->limit(50)
            ->get()
            ->toArray()
            ;
        return $this->success($tasks);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = $request->validate([
            'ips' => ['required', 'array'],
            'ips.*' => ['required', new ValidIpAddressWithPort],
        ]);

        $task = new Task();
        $task->created_at = $task->freshTimestamp();
        $task->save();

        foreach ($data['ips'] as $ip) {
            $proxy = new Proxy();
            $proxy->task_id = $task->id;
            $proxy->ip = $ip;
            $proxy->save();

            CheckProxy::dispatch($proxy->id);
        }

        return $this->success($task->toArray());
    }

    /**
     * @param Task $task
     * @return \Inertia\Response
     */
    public function show(Task $task)
    {
        return Inertia::render('Task', [
            'task' => $task,
            'proxies' => $task->proxies
        ]);
    }
}
