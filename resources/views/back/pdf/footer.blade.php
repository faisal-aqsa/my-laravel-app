<table width="100%" style="background:#000;color:#fff;padding:15px 30px;">
    <tr>
        <td width="33%" align="left">
            {{ $settings->website_url ?? 'www.company.com' }}
        </td>
        <td width="34%" align="center">
            {{ $settings->phone ?? '+91 XXXXXXXXXX' }}
        </td>
        <td width="33%" align="right">
            {{ $settings->email ?? 'email@company.com' }}
        </td>
    </tr>
</table>
