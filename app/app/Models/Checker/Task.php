<?php

namespace App\Models\Checker;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read  Proxy[] $proxies
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class);
    }

    public function updateLast(): void
    {
        $proxy = Proxy::whereTaskId($this->id)
            ->orderByDesc('finished_at')
            ->first();

        if ($proxy) {
            $this->updated_at = $proxy->finished_at;
            $this->save();
        }
    }
}
