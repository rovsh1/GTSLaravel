<?php

namespace Gsdk\Grid\Renderer\View;

use Gsdk\Grid\Grid;
use Gsdk\Grid\Renderer\ColumnRenderer;

class Table extends AbstractTable
{
    protected function renderTable(Grid $grid): string
    {
        $html = '<table' . $this->tableAttributes($grid) . '>';

        if (false !== $grid->getOption('header')) {
            $html .= $this->renderTHead($grid);
        }

        $html .= $this->renderTBody($grid);

        $html .= $this->renderTFoot($grid);

        $html .= '</table>';

        return $html;
    }

    protected function renderTHead(Grid $grid): string
    {
        $html = '<thead>';
        $html .= '<tr>';

        foreach ($grid->getColumns() as $column) {
            $html .= (new ColumnRenderer($grid, $column))->th();
        }

        $html .= '</tr>';
        $html .= '</thead>';

        return $html;
    }

    protected function renderTBody(Grid $grid): string
    {
        $html = '<tbody>';
        foreach ($grid->getData()->get() as $row) {
            $html .= '<tr' . ($row?->id ? ' data-id="' . $row->id . '"' : '') . '>';
            foreach ($grid->getColumns() as $column) {
                $html .= (new ColumnRenderer($grid, $column))->td($row);
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        return $html;
    }

    protected function renderTFoot(Grid $grid): string
    {
        $html = '';

        return ($html ? '<tfoot>' . $html . '</tfoot>' : '');
    }

    private function tableAttributes(Grid $grid): string
    {
        $attributes = '';
        foreach (['id', 'class'] as $k) {
            if ($grid->getOption($k)) {
                $attributes .= ' ' . $k . '="' . $grid->getOption($k) . '"';
            }
        }
        return $attributes;
    }
}
