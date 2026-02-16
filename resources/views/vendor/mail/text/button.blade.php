@props(['url', 'color' => 'primary'])
@php
    $colorStyles = [
        'primary' => 'background-color: #4F46E5; border-color: #4F46E5;',
        'success' => 'background-color: #22C55E; border-color: #22C55E;',
        'error' => 'background-color: #EF4444; border-color: #EF4444;',
    ];
    $style = $colorStyles[$color] ?? $colorStyles['primary'];
@endphp
<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="center">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td>
                                    <a href="{{ $url }}" class="button" target="_blank" rel="noopener" style="{{ $style }} border-radius: 6px; color: #ffffff; display: inline-block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 1; padding: 16px 32px; text-decoration: none; text-align: center;">
                                        {{ $slot }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
