<?php

namespace App\Models\Checker;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $task_id
 * @property string $ip
 * @property int|null $proxy_type
 * @property string|null $started_at
 * @property string|null $finished_at
 * @property string|null $location
 * @property int|null $status
 * @property int|null $timeout
 * @property string|null $real_ip
 * @property-read  Task $task
 * @method static Builder|Proxy newModelQuery()
 * @method static Builder|Proxy newQuery()
 * @method static Builder|Proxy query()
 * @method static Builder|Proxy whereFinishedAt($value)
 * @method static Builder|Proxy whereId($value)
 * @method static Builder|Proxy whereIp($value)
 * @method static Builder|Proxy whereProxyType($value)
 * @method static Builder|Proxy whereRealIp($value)
 * @method static Builder|Proxy whereLocation($value)
 * @method static Builder|Proxy whereStartedAt($value)
 * @method static Builder|Proxy whereStatus($value)
 * @method static Builder|Proxy whereTaskId($value)
 * @method static Builder|Proxy whereTimeout($value)
 * @mixin \Eloquent
 */
class Proxy extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
