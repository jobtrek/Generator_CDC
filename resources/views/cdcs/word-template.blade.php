<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cdc->title }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        h1 {
            font-size: 18pt;
            font-weight: bold;
            color: #1a5490;
            margin-top: 0;
            margin-bottom: 20pt;
            text-align: center;
        }
        h2 {
            font-size: 14pt;
            font-weight: bold;
            color: #1a5490;
            margin-top: 20pt;
            margin-bottom: 10pt;
            border-bottom: 2px solid #1a5490;
            padding-bottom: 5pt;
        }
        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15pt;
            margin-bottom: 8pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10pt 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8pt;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        ul, ol {
            margin: 10pt 0;
            padding-left: 20pt;
        }
        li {
            margin-bottom: 5pt;
        }
        .section {
            margin-bottom: 20pt;
        }
        .signature-table {
            margin-top: 30pt;
        }
        .signature-table td {
            height: 60pt;
        }
        p {
            margin: 8pt 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 5pt;
        }
        .label {
            font-weight: bold;
            width: 150pt;
        }
    </style>
</head>
<body>

{{-- EN-TÊTE --}}
<div style="text-align: center; margin-bottom: 30pt;">
    <p style="margin: 0; font-size: 10pt;">Procédure de qualification : 88600/1/2/3 - 88614 Informaticien/ne CFC (Ordo 2014/21)</p>
    <h1 style="margin-top: 10pt;">Cahier des charges</h1>
    <p style="font-size: 9pt; color: #666;">Version 1.1 - {{ now()->format('d.m.Y') }}</p>
</div>

{{-- 1. INFORMATIONS GÉNÉRALES --}}
<div class="section">
    <h2>1 INFORMATIONS GÉNÉRALES</h2>

    <table>
        <tr>
            <th style="width: 30%;">Candidat</th>
            <th style="width: 20%;">Nom :</th>
            <td style="width: 50%;">{{ $cdc->data['candidat_nom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>Prénom :</th>
            <td>{{ $cdc->data['candidat_prenom'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Lieu de travail :</th>
            <td colspan="2">{{ $cdc->data['lieu_travail'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Orientation :</th>
            <td colspan="2">{{ $cdc->data['orientation'] ?? '' }}</td>
        </tr>
    </table>

    <table style="margin-top: 15pt;">
        <tr>
            <th style="width: 30%;">Chef de projet</th>
            <th style="width: 20%;">Nom :</th>
            <td style="width: 50%;">{{ $cdc->data['chef_projet_nom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>Prénom :</th>
            <td>{{ $cdc->data['chef_projet_prenom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>📧 :</th>
            <td>{{ $cdc->data['chef_projet_email'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>☎ :</th>
            <td>{{ $cdc->data['chef_projet_telephone'] ?? '' }}</td>
        </tr>
    </table>

    <table style="margin-top: 15pt;">
        <tr>
            <th style="width: 30%;">Expert 1</th>
            <th style="width: 20%;">Nom :</th>
            <td style="width: 50%;">{{ $cdc->data['expert1_nom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>Prénom :</th>
            <td>{{ $cdc->data['expert1_prenom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>📧 :</th>
            <td>{{ $cdc->data['expert1_email'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>☎ :</th>
            <td>{{ $cdc->data['expert1_telephone'] ?? '' }}</td>
        </tr>
    </table>

    <table style="margin-top: 15pt;">
        <tr>
            <th style="width: 30%;">Expert 2</th>
            <th style="width: 20%;">Nom :</th>
            <td style="width: 50%;">{{ $cdc->data['expert2_nom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>Prénom :</th>
            <td>{{ $cdc->data['expert2_prenom'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>📧 :</th>
            <td>{{ $cdc->data['expert2_email'] ?? '' }}</td>
        </tr>
        <tr>
            <th></th>
            <th>☎ :</th>
            <td>{{ $cdc->data['expert2_telephone'] ?? '' }}</td>
        </tr>
    </table>

    <table style="margin-top: 15pt;">
        <tr>
            <th style="width: 40%;">Période de réalisation :</th>
            <td>{{ $cdc->data['periode_realisation'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Horaire de travail :</th>
            <td>{{ $cdc->data['horaire_travail'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Nombre d'heures :</th>
            <td>{{ $cdc->data['nombre_heures'] ?? '' }}</td>
        </tr>
    </table>

    @if(!empty($cdc->data['planning_analyse']) || !empty($cdc->data['planning_implementation']))
        <table style="margin-top: 15pt;">
            <tr>
                <th colspan="2" style="background-color: #d0d0d0;">Planning (en H ou %)</th>
            </tr>
            @if(!empty($cdc->data['planning_analyse']))
                <tr>
                    <th style="width: 40%;">Analyse :</th>
                    <td>{{ $cdc->data['planning_analyse'] }}</td>
                </tr>
            @endif
            @if(!empty($cdc->data['planning_implementation']))
                <tr>
                    <th>Implémentation :</th>
                    <td>{{ $cdc->data['planning_implementation'] }}</td>
                </tr>
            @endif
            @if(!empty($cdc->data['planning_tests']))
                <tr>
                    <th>Tests :</th>
                    <td>{{ $cdc->data['planning_tests'] }}</td>
                </tr>
            @endif
            @if(!empty($cdc->data['planning_documentation']))
                <tr>
                    <th>Documentations :</th>
                    <td>{{ $cdc->data['planning_documentation'] }}</td>
                </tr>
            @endif
        </table>
    @endif
</div>

{{-- 2. PROCÉDURE --}}
<div class="section">
    <h2>2 PROCÉDURE</h2>
    @php
        $procedure = $cdc->data['procedure'] ?? '';
        $procedureItems = $procedure ? explode("\n", $procedure) : [];
    @endphp
    @if(count($procedureItems) > 0)
        <ul>
            @foreach($procedureItems as $item)
                @if(trim($item))
                    <li>{{ trim($item) }}</li>
                @endif
            @endforeach
        </ul>
    @else
        <p><em>Non renseigné</em></p>
    @endif
</div>

{{-- 3. TITRE --}}
<div class="section">
    <h2>3 TITRE</h2>
    <p style="font-weight: bold; font-size: 12pt;">{{ $cdc->title }}</p>
</div>

{{-- 4. MATÉRIEL ET LOGICIEL À DISPOSITION --}}
@if(!empty($cdc->data['materiel_logiciel']))
    <div class="section">
        <h2>4 MATÉRIEL ET LOGICIEL À DISPOSITION</h2>
        @php
            $materiel = $cdc->data['materiel_logiciel'];
            $materielItems = explode("\n", $materiel);
        @endphp
        <ul>
            @foreach($materielItems as $item)
                @if(trim($item))
                    <li>{{ trim($item) }}</li>
                @endif
            @endforeach
        </ul>
    </div>
@endif

{{-- 5. PRÉREQUIS --}}
@if(!empty($cdc->data['prerequis']))
    <div class="section">
        <h2>5 PRÉREQUIS</h2>
        @php
            $prerequis = $cdc->data['prerequis'];
            $prerequisItems = explode("\n", $prerequis);
        @endphp
        <ul>
            @foreach($prerequisItems as $item)
                @if(trim($item))
                    <li>{{ trim($item) }}</li>
                @endif
            @endforeach
        </ul>
    </div>
@endif

{{-- 6. DESCRIPTIF DU PROJET --}}
<div class="section">
    <h2>6 DESCRIPTIF DU PROJET</h2>
    <div style="white-space: pre-wrap;">{{ $cdc->data['descriptif_projet'] ?? 'Non renseigné' }}</div>
</div>

{{-- 7. LIVRABLES --}}
@if(!empty($cdc->data['livrables']))
    <div class="section">
        <h2>7 LIVRABLES</h2>
        <p>Le candidat est responsable de livrer à son chef de projet et aux deux experts :</p>
        @php
            $livrables = $cdc->data['livrables'];
            $livrablesItems = explode("\n", $livrables);
        @endphp
        <ul>
            @foreach($livrablesItems as $item)
                @if(trim($item))
                    <li>{{ trim($item) }}</li>
                @endif
            @endforeach
        </ul>
    </div>
@endif

{{-- 8. POINTS TECHNIQUES ÉVALUÉS (si champs customs) --}}
@php
    $customFields = collect($cdc->data)->filter(function($value, $key) {
        return !in_array($key, [
            'candidat_nom', 'candidat_prenom', 'lieu_travail', 'orientation',
            'chef_projet_nom', 'chef_projet_prenom', 'chef_projet_email', 'chef_projet_telephone',
            'expert1_nom', 'expert1_prenom', 'expert1_email', 'expert1_telephone',
            'expert2_nom', 'expert2_prenom', 'expert2_email', 'expert2_telephone',
            'periode_realisation', 'horaire_travail', 'nombre_heures',
            'planning_analyse', 'planning_implementation', 'planning_tests', 'planning_documentation',
            'procedure', 'titre_projet', 'materiel_logiciel', 'prerequis', 'descriptif_projet', 'livrables'
        ]) && !empty($value);
    });
@endphp

@if($customFields->count() > 0)
    <div class="section">
        <h2>8 POINTS TECHNIQUES ÉVALUÉS SPÉCIFIQUES AU PROJET</h2>
        <p>La grille d'évaluation définit les critères généraux selon lesquels le travail du candidat sera évalué (documentation, journal de travail, respect des normes, qualité, …).</p>
        <p>En plus de cela, le travail sera évalué sur les points spécifiques suivants :</p>
        <ol>
            @foreach($customFields as $key => $value)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong><br>{{ $value }}</li>
            @endforeach
        </ol>
    </div>
@endif

{{-- 9. VALIDATION --}}
<div class="section">
    <h2>9 VALIDATION</h2>
    <table class="signature-table">
        <tr>
            <th style="width: 30%;">Lu et approuvé le :</th>
            <th style="width: 70%;">Signature :</th>
        </tr>
        <tr>
            <td>Candidat :</td>
            <td></td>
        </tr>
        <tr>
            <td>Expert n°1 :</td>
            <td></td>
        </tr>
        <tr>
            <td>Expert n°2 :</td>
            <td></td>
        </tr>
        <tr>
            <td>Chef de projet :</td>
            <td></td>
        </tr>
    </table>
</div>

{{-- FOOTER --}}
<div style="position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9pt; color: #666; border-top: 1px solid #ccc; padding-top: 10pt;">
    <p style="margin: 0;">FONDATION JOBTREK</p>
    <p style="margin: 0;">v1.1, {{ now()->format('Y-m-d') }}, JT_DEV_B100_1.0</p>
    <p style="margin: 0;">jobtrek.ch</p>
</div>

</body>
</html>
