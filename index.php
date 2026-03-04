<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Detector</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
        .info {
            margin: 15px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
            font-size: 18px;
        }
        .loading {
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🌍 Country Detection</h1>
        
        <div id="loading" class="loading">Loading...</div>
        
        <div id="result" style="display: none;">
            <div class="info">
                <div class="label">Your IP Address:</div>
                <div class="value" id="ip"></div>
            </div>
            
            <div class="info">
                <div class="label">Detected Country:</div>
                <div class="value" id="country"></div>
            </div>
            
            <div class="info">
                <div class="label">Language Code:</div>
                <div class="value" id="language"></div>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/detect.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('result').style.display = 'block';
                document.getElementById('ip').textContent = data.ip;
                document.getElementById('country').textContent = data.countryName + ' (' + data.countryCode + ')';
                document.getElementById('language').textContent = data.language;
            })
            .catch(error => {
                document.getElementById('loading').textContent = 'Error loading data';
                console.error('Error:', error);
            });
    </script>
</body>
</html>
