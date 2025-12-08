<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Protection Test - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .test-card {
            margin: 20px 0;
            border: 2px solid #ddd;
        }
        .test-card.success {
            border-color: #28a745;
        }
        .test-card.error {
            border-color: #dc3545;
        }
        .result {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
        .result.success {
            background-color: #d4edda;
            color: #155724;
            display: block;
        }
        .result.error {
            background-color: #f8d7da;
            color: #721c24;
            display: block;
        }
        .code-block {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
            overflow-x: auto;
        }
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .status-indicator.active {
            background-color: #28a745;
        }
        .status-indicator.inactive {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">🔒 CSRF Protection Test Suite</h1>
    
    <!-- CSRF Status -->
    <div class="alert alert-info">
        <h5>CSRF Protection Status</h5>
        <p>
            <span class="status-indicator active"></span>
            <strong>ENABLED</strong> - All POST requests require valid CSRF tokens
        </p>
        <p class="mb-0">
            <small>Current CSRF Token: <code id="current-token"></code></small>
        </p>
    </div>

    <!-- Test 1: Using Helper Function (RECOMMENDED) -->
    <div class="card test-card">
        <div class="card-header bg-success text-white">
            <h5>✅ Test 1: Using CSRF Helper (RECOMMENDED)</h5>
        </div>
        <div class="card-body">
            <p>This test uses the <code>csrf-ajax.js</code> helper function that automatically includes the CSRF token.</p>
            
            <button class="btn btn-primary" onclick="test1()">
                Run Test 1
            </button>
            
            <div id="result1" class="result"></div>
            
            <div class="code-block mt-3">
csrfPost('test_csrf/test_protected_action', 
    { test_data: 'Hello from Test 1' },
    function(response) {
        // Success callback
    }
);
            </div>
        </div>
    </div>

    <!-- Test 2: Manual AJAX with CSRF Token -->
    <div class="card test-card">
        <div class="card-header bg-primary text-white">
            <h5>✅ Test 2: Manual AJAX with CSRF Token</h5>
        </div>
        <div class="card-body">
            <p>This test manually includes the CSRF token in the request.</p>
            
            <button class="btn btn-primary" onclick="test2()">
                Run Test 2
            </button>
            
            <div id="result2" class="result"></div>
            
            <div class="code-block mt-3">
fetch(url, {
    method: 'POST',
    body: 'test_data=value&csrf_test_name=' + getCsrfToken()
});
            </div>
        </div>
    </div>

    <!-- Test 3: AJAX WITHOUT CSRF Token (Should Fail) -->
    <div class="card test-card">
        <div class="card-header bg-danger text-white">
            <h5>❌ Test 3: AJAX Without CSRF Token (Should FAIL)</h5>
        </div>
        <div class="card-body">
            <p>This test deliberately omits the CSRF token to demonstrate protection is working.</p>
            
            <button class="btn btn-danger" onclick="test3()">
                Run Test 3 (Expected to Fail)
            </button>
            
            <div id="result3" class="result"></div>
            
            <div class="code-block mt-3">
fetch(url, {
    method: 'POST',
    body: 'test_data=value'  // NO CSRF TOKEN!
});
            </div>
        </div>
    </div>

    <!-- Test 4: Invalid CSRF Token (Should Fail) -->
    <div class="card test-card">
        <div class="card-header bg-danger text-white">
            <h5>❌ Test 4: Invalid CSRF Token (Should FAIL)</h5>
        </div>
        <div class="card-body">
            <p>This test uses a fake/invalid CSRF token to demonstrate protection.</p>
            
            <button class="btn btn-danger" onclick="test4()">
                Run Test 4 (Expected to Fail)
            </button>
            
            <div id="result4" class="result"></div>
            
            <div class="code-block mt-3">
fetch(url, {
    method: 'POST',
    body: 'test_data=value&csrf_test_name=FAKE_INVALID_TOKEN'
});
            </div>
        </div>
    </div>

    <!-- Test 5: Form Submission with CSRF -->
    <div class="card test-card">
        <div class="card-header bg-success text-white">
            <h5>✅ Test 5: Form Submission (Auto-CSRF)</h5>
        </div>
        <div class="card-body">
            <p>CodeIgniter automatically adds CSRF tokens when using <code>form_open()</code>.</p>
            
            <?= form_open('test_csrf/test_protected_action', ['id' => 'test-form']); ?>
                <input type="hidden" name="test_data" value="Form submission test">
                <button type="submit" class="btn btn-primary">Submit Form</button>
            <?= form_close(); ?>
            
            <div id="result5" class="result"></div>
        </div>
    </div>

    <!-- Back to App -->
    <div class="text-center mt-4 mb-5">
        <a href="<?= site_url('profile'); ?>" class="btn btn-secondary">Back to VividSpace</a>
        <a href="<?= site_url('login'); ?>" class="btn btn-outline-primary">Login</a>
    </div>
</div>

<!-- Include CSRF AJAX Helper -->
<script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>

<script>
// Display current CSRF token
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('current-token').textContent = getCsrfToken() || 'Not found';
});

// Test 1: Using Helper Function
function test1() {
    const resultDiv = document.getElementById('result1');
    resultDiv.innerHTML = '⏳ Testing...';
    resultDiv.className = 'result';
    
    csrfPost(
        '<?= site_url("test_csrf/test_protected_action"); ?>',
        { test_data: 'Hello from Test 1 - Using Helper Function' },
        function(response) {
            if (response.status === 'success') {
                resultDiv.className = 'result success';
                resultDiv.innerHTML = `
                    <strong>✅ SUCCESS!</strong><br>
                    ${response.message}<br>
                    <small>Data received: ${response.data_received}</small><br>
                    <small>New token: ${response.csrf_token}</small>
                `;
            } else {
                resultDiv.className = 'result error';
                resultDiv.innerHTML = `<strong>❌ ERROR:</strong> ${response.message}`;
            }
        },
        function(error) {
            resultDiv.className = 'result error';
            resultDiv.innerHTML = `<strong>❌ ERROR:</strong> ${error}`;
        }
    );
}

// Test 2: Manual AJAX with CSRF Token
function test2() {
    const resultDiv = document.getElementById('result2');
    resultDiv.innerHTML = '⏳ Testing...';
    resultDiv.className = 'result';
    
    const data = new URLSearchParams({
        test_data: 'Hello from Test 2 - Manual AJAX',
        csrf_test_name: getCsrfToken()
    });
    
    fetch('<?= site_url("test_csrf/test_protected_action"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            resultDiv.className = 'result success';
            resultDiv.innerHTML = `
                <strong>✅ SUCCESS!</strong><br>
                ${data.message}<br>
                <small>Data received: ${data.data_received}</small>
            `;
            updateCsrfCookie(data.csrf_token);
        } else {
            resultDiv.className = 'result error';
            resultDiv.innerHTML = `<strong>❌ ERROR:</strong> ${data.message}`;
        }
    })
    .catch(error => {
        resultDiv.className = 'result error';
        resultDiv.innerHTML = `<strong>❌ ERROR:</strong> ${error}`;
    });
}

// Test 3: WITHOUT CSRF Token (Should Fail)
function test3() {
    const resultDiv = document.getElementById('result3');
    resultDiv.innerHTML = '⏳ Testing (should fail)...';
    resultDiv.className = 'result';
    
    const data = new URLSearchParams({
        test_data: 'Hello from Test 3 - NO CSRF TOKEN'
        // Deliberately omitting csrf_test_name
    });
    
    fetch('<?= site_url("test_csrf/test_protected_action"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.text())
    .then(text => {
        resultDiv.className = 'result error';
        resultDiv.innerHTML = `
            <strong>✅ PROTECTION WORKING!</strong><br>
            Request was blocked because CSRF token was missing.<br>
            <small>Response: ${text.substring(0, 200)}...</small>
        `;
    })
    .catch(error => {
        resultDiv.className = 'result error';
        resultDiv.innerHTML = `
            <strong>✅ PROTECTION WORKING!</strong><br>
            Request was blocked: ${error}
        `;
    });
}

// Test 4: Invalid CSRF Token (Should Fail)
function test4() {
    const resultDiv = document.getElementById('result4');
    resultDiv.innerHTML = '⏳ Testing (should fail)...';
    resultDiv.className = 'result';
    
    const data = new URLSearchParams({
        test_data: 'Hello from Test 4 - FAKE TOKEN',
        csrf_test_name: 'INVALID_FAKE_TOKEN_123456789'
    });
    
    fetch('<?= site_url("test_csrf/test_protected_action"); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: data
    })
    .then(response => response.text())
    .then(text => {
        resultDiv.className = 'result error';
        resultDiv.innerHTML = `
            <strong>✅ PROTECTION WORKING!</strong><br>
            Request was blocked because CSRF token was invalid.<br>
            <small>Response: ${text.substring(0, 200)}...</small>
        `;
    })
    .catch(error => {
        resultDiv.className = 'result error';
        resultDiv.innerHTML = `
            <strong>✅ PROTECTION WORKING!</strong><br>
            Request was blocked: ${error}
        `;
    });
}

// Test 5: Form submission
document.getElementById('test-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const resultDiv = document.getElementById('result5');
    resultDiv.innerHTML = '⏳ Submitting form...';
    resultDiv.className = 'result';
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            resultDiv.className = 'result success';
            resultDiv.innerHTML = `
                <strong>✅ SUCCESS!</strong><br>
                ${data.message}<br>
                <small>CodeIgniter automatically added CSRF token to the form!</small>
            `;
        }
    })
    .catch(error => {
        resultDiv.className = 'result error';
        resultDiv.innerHTML = `<strong>❌ ERROR:</strong> ${error}`;
    });
});
</script>
</body>
</html>

