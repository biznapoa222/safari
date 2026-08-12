<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Weekly Report</title>
<style>body{font-family:sans-serif;padding:2rem;}h1{font-size:1.5rem;}table{width:100%;border-collapse:collapse;margin-top:1rem;}th,td{padding:0.5rem;border:1px solid #ddd;text-align:left;}th{background:#f5f5f5;}</style>
</head>
<body>
    <h1>Weekly Management Report</h1>
    <p>{{ $data['week'] }}</p>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>New Leads</td><td>{{ $data['new_leads'] }}</td></tr>
        <tr><td>Converted Leads</td><td>{{ $data['converted_leads'] }}</td></tr>
        <tr><td>Revenue</td><td>${{ number_format($data['revenue']) }}</td></tr>
    </table>
</body>
</html>
