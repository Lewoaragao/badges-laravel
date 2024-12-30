<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBadgeController extends Controller
{
    public function generate(Request $request)
    {
        // Validação dos parâmetros recebidos
        $request->validate([
            'label' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'color' => 'required|string|max:50',
            'style' => 'nullable|in:flat,plastic,flat-square,for-the-badge',
        ]);

        // Dados do badge
        $label = $request->input('label');
        $status = $request->input('status');
        $color = $request->input('color');
        $style = $request->input('style', 'flat'); // Valor padrão

        // Gerar SVG
        $svg = $this->generateSvg($label, $status, $color, $style);

        // Retornar o SVG como resposta
        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    private function generateSvg($label, $status, $color, $style)
    {
        // Estilos baseados na escolha
        $styles = [
            'flat' => ['width' => 200, 'height' => 20, 'rx' => 3],
            'plastic' => ['width' => 200, 'height' => 20, 'rx' => 5],
            'flat-square' => ['width' => 200, 'height' => 20, 'rx' => 0],
            'for-the-badge' => ['width' => 300, 'height' => 30, 'rx' => 10],
        ];

        $styleConfig = $styles[$style];

        // Gerar SVG dinâmico
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$styleConfig['width']}" height="{$styleConfig['height']}" viewBox="0 0 {$styleConfig['width']} {$styleConfig['height']}">
    <rect width="50%" height="100%" fill="#555" rx="{$styleConfig['rx']}"/>
    <rect x="50%" width="50%" height="100%" fill="$color" rx="{$styleConfig['rx']}"/>
    <text x="25%" y="50%" fill="#fff" font-family="Verdana" font-size="12" text-anchor="middle" alignment-baseline="middle">$label</text>
    <text x="75%" y="50%" fill="#fff" font-family="Verdana" font-size="12" text-anchor="middle" alignment-baseline="middle">$status</text>
</svg>
SVG;
    }
}