<?php

namespace Dainsys\Evaluate\Http\Livewire\Charts;

use Dainsys\Evaluate\Enums\ColorsEnum;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Dainsys\Evaluate\Services\Ticket\OldestTicketService;

class WeeklyTicketsSatisfactionRateChart extends BaseChart
{
    public $department;

    public function render()
    {
        parent::render();

        return view('evaluate::livewire.charts.weekly-tickets-completion-and-compliance-rate', [
            'chart' => $this->createChart()
        ]);
    }

    protected function initChart(): BaseChartModel
    {
        return new LineChartModel();
    }

    protected function createChart()
    {
        $weeks_sinces_oldest_ticket = (new OldestTicketService())->weeksSinceOldestTicket(config('evaluate.dashboard.weeks'));

        foreach (range($weeks_sinces_oldest_ticket, 0) as $index) {
            $service = new \Dainsys\Evaluate\Services\Ticket\RatingService();
            $date = now()->subWeeks($index);
            $title = $date->copy()->endOfWeek()->format('Y-M-d');

            $rate = $service->avg($index, [
                'department_id' => $this->department?->id
            ]);

            $formated_value = number_format($rate / 5 * 100, 0);
            $context_color = ColorsEnum::contextColor(config('evaluate.dashboard.context.good'), $rate ?: 0);

            $this->chart
                ->setDataLabelsEnabled(false)
                ->addMarker(
                    $title,
                    $formated_value,
                    $context_color,
                    $formated_value,
                    ColorsEnum::WHITE,
                    $context_color
                )
                ->addPoint(
                    $title,
                    $formated_value,
                    $context_color
                );
        }

        return $this->chart;
    }
}
