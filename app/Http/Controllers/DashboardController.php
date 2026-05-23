<?php

namespace App\Http\Controllers;

use App\Models\BuildTimelineEntry;
use App\Models\InspectionPoint;
use App\Models\Maintenance;
use App\Models\Mod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = auth()->user();
        $carProfile = $user->activeCar();

        if (! $carProfile) {
            return redirect()->route('garage.setup');
        }

        $mods = Mod::where('user_id', $user->id)->where('car_profile_id', $carProfile->id)->latest()->get();
        $maintenances = Maintenance::where('user_id', $user->id)->where('car_profile_id', $carProfile->id)->latest()->get();
        $inspectionPoints = InspectionPoint::where('car_profile_id', $carProfile->id)->latest()->get();
        $timelineEntries = BuildTimelineEntry::where('user_id', $user->id)
            ->where('car_profile_id', $carProfile->id)
            ->latest('event_date')
            ->latest()
            ->get();

        return view('garage', [
            'carProfile' => $carProfile->loadMissing('photos'),
            'garageHealth' => $this->garageHealth($carProfile, $maintenances, $mods, $inspectionPoints, $timelineEntries),
            'currentProjects' => $this->currentProjects($carProfile, $mods, $inspectionPoints),
            'nextReminders' => $this->nextReminders($maintenances),
            'recentMaintenance' => $maintenances->take(4),
            'recentMods' => $mods->take(4),
            'recentPhotos' => $carProfile->photos->take(4),
            'latestInspectionPoints' => $inspectionPoints->take(4),
            'timelineEntries' => $timelineEntries->take(4),
            'costSummary' => $this->costSummary($maintenances, $mods, $timelineEntries),
            'recommendationPreview' => $this->recommendationPreview($carProfile, $mods, $maintenances, $inspectionPoints),
        ]);
    }

    private function garageHealth($carProfile, $maintenances, $mods, $inspectionPoints, $timelineEntries): array
    {
        $fields = collect([
            $carProfile->make,
            $carProfile->model,
            $carProfile->year,
            $carProfile->body_type,
            $carProfile->engine,
            $carProfile->color_name ?: $carProfile->color_code,
            $carProfile->build_vibe,
            $carProfile->theme_color,
        ]);

        $profileScore = (int) round(($fields->filter()->count() / $fields->count()) * 45);
        $activityScore = collect([
            $maintenances->isNotEmpty() ? 15 : 0,
            $mods->isNotEmpty() ? 15 : 0,
            $inspectionPoints->isNotEmpty() ? 10 : 0,
            $timelineEntries->isNotEmpty() ? 10 : 0,
            $carProfile->photos->isNotEmpty() ? 5 : 0,
        ])->sum();

        $score = min(100, $profileScore + $activityScore);

        return [
            'score' => $score,
            'label' => $score >= 75 ? __('ui.dashboard.health_strong') : ($score >= 45 ? __('ui.dashboard.health_building') : __('ui.dashboard.health_starting')),
        ];
    }

    private function currentProjects($carProfile, $mods, $inspectionPoints)
    {
        $profilePlans = collect(preg_split('/\r\n|\r|\n/', (string) $carProfile->future_plans))
            ->map(fn ($plan) => trim($plan))
            ->filter()
            ->map(fn ($plan) => [
                'title' => $plan,
                'meta' => __('ui.dashboard.from_garage_profile'),
            ]);

        $priorityMods = $mods
            ->filter(fn ($mod) => strtolower((string) $mod->priority) === 'high')
            ->take(3)
            ->map(fn ($mod) => [
                'title' => $mod->name,
                'meta' => $mod->category ?: __('ui.common.no_category'),
            ]);

        $openInspection = $inspectionPoints
            ->filter(fn ($point) => strtolower((string) $point->status) !== 'fixed')
            ->take(3)
            ->map(fn ($point) => [
                'title' => $point->name,
                'meta' => $point->priority ?: __('ui.common.no_priority'),
            ]);

        return $profilePlans->merge($priorityMods)->merge($openInspection)->take(6);
    }

    private function nextReminders($maintenances)
    {
        return $maintenances
            ->filter(fn ($maintenance) => $maintenance->next_due_date || $maintenance->next_due_mileage)
            ->sortBy(fn ($maintenance) => $maintenance->next_due_date ?: '9999-12-31')
            ->take(5)
            ->map(function ($maintenance) {
                $status = 'future';

                if ($maintenance->next_due_date) {
                    $due = Carbon::parse($maintenance->next_due_date)->startOfDay();
                    $today = now()->startOfDay();

                    if ($due->lt($today)) {
                        $status = 'overdue';
                    } elseif ($due->diffInDays($today) <= 30) {
                        $status = 'soon';
                    }
                }

                return [
                    'title' => $maintenance->title,
                    'date' => $maintenance->next_due_date,
                    'mileage' => $maintenance->next_due_mileage,
                    'status' => $status,
                ];
            });
    }

    private function costSummary($maintenances, $mods, $timelineEntries): array
    {
        $maintenanceSpent = (float) $maintenances->sum(fn ($maintenance) => $maintenance->cost ?? 0);
        $modsPlanned = (float) $mods->sum(fn ($mod) => $mod->price ?? 0);
        $installedMods = (float) $mods
            ->filter(fn ($mod) => strtolower((string) $mod->status) === 'installed')
            ->sum(fn ($mod) => $mod->price ?? 0);
        $timelineSpent = (float) $timelineEntries->sum(fn ($entry) => $entry->cost ?? 0);

        return [
            'total_spent' => $maintenanceSpent + $installedMods + $timelineSpent,
            'maintenance_spent' => $maintenanceSpent,
            'mods_planned' => $modsPlanned,
            'installed_mods' => $installedMods,
            'timeline_spent' => $timelineSpent,
        ];
    }

    private function recommendationPreview($carProfile, $mods, $maintenances, $inspectionPoints): array
    {
        if ($inspectionPoints->where('priority', 'High')->where('status', '!=', 'Fixed')->isNotEmpty()) {
            return [
                'mode' => __('ui.mods.reliability'),
                'title' => __('ui.dashboard.rec_inspection_title'),
                'copy' => __('ui.dashboard.rec_inspection_copy'),
            ];
        }

        if ($maintenances->isEmpty()) {
            return [
                'mode' => __('ui.maintenance.service_log'),
                'title' => __('ui.dashboard.rec_maintenance_title'),
                'copy' => __('ui.dashboard.rec_maintenance_copy'),
            ];
        }

        if ($mods->isEmpty()) {
            return [
                'mode' => __('ui.mods.priority_plan'),
                'title' => __('ui.dashboard.rec_mods_title'),
                'copy' => __('ui.dashboard.rec_mods_copy'),
            ];
        }

        return [
            'mode' => __('ui.mods.visual_style'),
            'title' => __('ui.dashboard.rec_theme_title', ['color' => $carProfile->color_name ?: __('ui.common.theme')]),
            'copy' => __('ui.dashboard.rec_theme_copy'),
        ];
    }
}
