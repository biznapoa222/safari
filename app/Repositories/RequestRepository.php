<?php

namespace App\Repositories;

use App\Models\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RequestRepository
{
    public function __construct(private Request $model) {}

    public function find(int $id): ?Request
    {
        return $this->model->with([
            'client', 'assignedUser', 'consultant', 'notes.user', 'tasks.assignedUser',
            'files.user', 'followups.user', 'flags.user', 'tags', 'statusLogs.user',
        ])->find($id);
    }

    public function paginate(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $sort = in_array($filters['sort'] ?? null, ['created_at', 'arrival_date', 'request_date'], true)
            ? $filters['sort']
            : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $this->model->query()
            ->with(['client', 'assignedUser', 'consultant', 'itineraryTemplate'])
            ->filter($filters)
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Request
    {
        $data['request_number'] = $data['request_number'] ?? Request::generateRequestNumber();
        $data['request_date'] = $data['request_date'] ?? now()->toDateString();

        return DB::transaction(function () use ($data) {
            $request = $this->model->create($data);
            $request->logStatus(null, $data['status'] ?? 'new', 'Request created');
            return $request;
        });
    }

    public function update(Request $request, array $data): Request
    {
        return DB::transaction(function () use ($request, $data) {
            foreach ($data as $field => $value) {
                if ($request->{$field} != $value) {
                    $request->logHistory($field, $request->{$field}, $value);
                }
            }
            $request->update($data);
            return $request->fresh();
        });
    }

    public function updateStatus(Request $request, string $newStatus, ?string $notes = null): Request
    {
        return DB::transaction(function () use ($request, $newStatus, $notes) {
            $oldStatus = $request->status;
            $request->update(['status' => $newStatus]);
            $request->logStatus($oldStatus, $newStatus, $notes);
            $request->logHistory('status', $oldStatus, $newStatus, $notes);
            return $request->fresh();
        });
    }

    public function getStats(): array
    {
        $total = $this->model->count();
        $converted = $this->model->where('status', 'converted')->count();
        $avgValue = $this->model->whereNotNull('quote_value')->avg('quote_value');

        return [
            'total' => $total,
            'new' => $this->model->where('status', 'new')->count(),
            'contacted' => $this->model->where('status', 'contacted')->count(),
            'qualified' => $this->model->where('status', 'qualified')->count(),
            'quote_sent' => $this->model->where('status', 'quote_sent')->count(),
            'negotiation' => $this->model->where('status', 'negotiation')->count(),
            'confirmed' => $this->model->where('status', 'confirmed')->count(),
            'booked' => $this->model->where('status', 'booked')->count(),
            'cancelled' => $this->model->where('status', 'cancelled')->count(),
            'archived' => $this->model->onlyTrashed()->count(),
            'followups_due' => \App\Models\RequestFollowup::whereDate('followup_date', now()->toDateString())
                ->where('status', 'pending')
                ->whereHas('request', fn ($q) => $q->whereNull('deleted_at'))
                ->count(),
            'conversion_rate' => $total > 0 ? round(($converted / $total) * 100, 1) : 0,
            'avg_value' => $avgValue ? round($avgValue, 2) : 0,
        ];
    }

    public function searchClients(string $term): Collection
    {
        return \App\Models\Client::query()
            ->where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->limit(20)
            ->get();
    }

    public function upsertClient(array $data): \App\Models\Client
    {
        return \App\Models\Client::updateOrCreate(
            ['email' => $data['email'] ?? ''],
            $data
        );
    }
}
