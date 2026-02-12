<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} - Olongpich Transport</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .info-card h3 {
            color: #667eea;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }
        
        .info-card ul {
            list-style: none;
        }
        
        .info-card li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        
        .info-card li:last-child {
            border-bottom: none;
        }
        
        .info-card li::before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .phase-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .phase-section h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 25px;
        }
        
        .phase-item {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }
        
        .phase-item:last-child {
            border-bottom: none;
        }
        
        .phase-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .phase-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        
        .phase-status {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .phase-status.completed {
            background: #d4edda;
            color: #155724;
        }
        
        .phase-status.in_progress {
            background: #fff3cd;
            color: #856404;
        }
        
        .phase-status.pending {
            background: #f8d7da;
            color: #721c24;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        
        .footer {
            text-align: center;
            color: white;
            margin-top: 30px;
            padding: 20px;
        }
        
        .tech-stack {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .tech-badge {
            padding: 8px 16px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚌 Olongpich Transport</h1>
            <p>Admin Dashboard - CakePHP to Laravel Migration</p>
            <span class="status-badge warning">Migration In Progress</span>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <h3>🎯 Current Status</h3>
                <ul>
                    <li>Laravel 12.51.0 Installed</li>
                    <li>Database Configured</li>
                    <li>Core Models Created</li>
                    <li>Initial Migrations Ready</li>
                    <li>Legacy Code Preserved</li>
                </ul>
            </div>
            
            <div class="info-card">
                <h3>📦 Technology Stack</h3>
                <div class="tech-stack">
                    <span class="tech-badge">PHP 8.3.6</span>
                    <span class="tech-badge">Laravel 12.51.0</span>
                    <span class="tech-badge">MySQL</span>
                    <span class="tech-badge">Blade</span>
                    <span class="tech-badge">Eloquent ORM</span>
                </div>
            </div>
            
            <div class="info-card">
                <h3>📊 Migration Stats</h3>
                <ul>
                    <li>69 Controllers to Migrate</li>
                    <li>28 Models to Migrate</li>
                    <li>74 View Directories</li>
                    <li>13 Migrations Created</li>
                    <li>8 Models Completed</li>
                </ul>
            </div>
        </div>
        
        <div class="phase-section">
            <h2>Migration Progress</h2>
            
            @foreach($migrationStatus as $phase)
            <div class="phase-item">
                <div class="phase-header">
                    <span class="phase-name">{{ $phase['name'] }}</span>
                    <span class="phase-status {{ $phase['status'] }}">
                        {{ ucfirst(str_replace('_', ' ', $phase['status'])) }}
                    </span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $phase['percentage'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="footer">
            <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
            <p><strong>PHP Version:</strong> {{ PHP_VERSION }}</p>
            <p style="margin-top: 10px;">
                <a href="https://github.com/TheTevea/olp_backend_admin_dashboard" style="color: white; text-decoration: underline;">
                    View on GitHub
                </a>
            </p>
        </div>
    </div>
</body>
</html>
