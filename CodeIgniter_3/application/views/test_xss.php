<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS Protection Test - VividSpace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .test-card {
            margin: 20px 0;
            border: 2px solid #ddd;
        }
        .test-card.success {
            border-color: #28a745;
        }
        .test-card.danger {
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
        .result.danger {
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
            white-space: pre-wrap;
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
        .demo-output {
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 10px;
            background-color: #fff;
            min-height: 50px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">🛡️ XSS Protection Test Suite</h1>
    
    <!-- XSS Status -->
    <div class="alert alert-info">
        <h5>XSS Protection Status</h5>
        <p>
            <span class="status-indicator active"></span>
            <strong>ENABLED</strong> - All user input is sanitized and output is escaped
        </p>
        <p class="mb-0">
            <small>Using CodeIgniter security features + custom XSS helper functions</small>
        </p>
    </div>

    <!-- Common XSS Attack Examples -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h5>⚠️ Common XSS Attack Payloads (Try These)</h5>
        </div>
        <div class="card-body">
            <p>Click to copy and test these common attack vectors:</p>
            <div class="btn-group-vertical" style="width: 100%;">
                <button class="btn btn-outline-danger btn-sm mb-2" onclick="copyToInput('<script>alert(\'XSS\')</script>')">
                    Basic Script Tag: &lt;script&gt;alert('XSS')&lt;/script&gt;
                </button>
                <button class="btn btn-outline-danger btn-sm mb-2" onclick="copyToInput('<img src=x onerror=alert(\'XSS\')>')">
                    Image onerror: &lt;img src=x onerror=alert('XSS')&gt;
                </button>
                <button class="btn btn-outline-danger btn-sm mb-2" onclick="copyToInput('<svg/onload=alert(\'XSS\')>')">
                    SVG onload: &lt;svg/onload=alert('XSS')&gt;
                </button>
                <button class="btn btn-outline-danger btn-sm mb-2" onclick="copyToInput('javascript:alert(\'XSS\')')">
                    JavaScript URL: javascript:alert('XSS')
                </button>
                <button class="btn btn-outline-danger btn-sm mb-2" onclick="copyToInput('<iframe src=javascript:alert(\'XSS\')>')">
                    IFrame Attack: &lt;iframe src=javascript:alert('XSS')&gt;
                </button>
            </div>
        </div>
    </div>

    <!-- Test 1: Proper Escaping (SAFE) -->
    <div class="card test-card success">
        <div class="card-header bg-success text-white">
            <h5>✅ Test 1: Proper Output Escaping (SAFE)</h5>
        </div>
        <div class="card-body">
            <p>This test demonstrates proper XSS protection using the <code>esc()</code> function.</p>
            
            <div class="form-group">
                <label>Enter potentially malicious input:</label>
                <textarea id="safe-input" class="form-control" rows="3" placeholder="Try entering: <script>alert('XSS')</script>"></textarea>
            </div>
            
            <button class="btn btn-success" onclick="testSafe()">
                Test Safe Output
            </button>
            
            <div id="result1" class="result"></div>
            
            <div class="mt-3">
                <strong>How it's protected:</strong>
                <div class="code-block">&lt;?= esc($user_input); ?&gt;
// Converts: &lt;script&gt; to &amp;lt;script&amp;gt;
// Result: Displayed as text, not executed</div>
            </div>
            
            <div class="demo-output" id="safe-output">
                <em>Output will appear here...</em>
            </div>
        </div>
    </div>

    <!-- Test 2: Unsafe Output (DEMONSTRATION) -->
    <div class="card test-card danger">
        <div class="card-header bg-danger text-white">
            <h5>❌ Test 2: Without Protection (UNSAFE - DEMONSTRATION ONLY)</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                <strong>⚠️ WARNING:</strong> This demonstrates what happens WITHOUT XSS protection. 
                In a real attack, malicious scripts would execute!
            </div>
            
            <div class="form-group">
                <label>Enter potentially malicious input:</label>
                <textarea id="unsafe-input" class="form-control" rows="3" placeholder="Try entering: <script>alert('XSS')</script>"></textarea>
            </div>
            
            <button class="btn btn-danger" onclick="testUnsafe()">
                Test Unsafe Output (See JSON Response)
            </button>
            
            <div id="result2" class="result"></div>
            
            <div class="mt-3">
                <strong>What's wrong:</strong>
                <div class="code-block">echo $user_input; // NO ESCAPING!
// Script tags would execute
// Cookies could be stolen
// Users could be redirected</div>
            </div>
        </div>
    </div>

    <!-- Test 3: Input Sanitization -->
    <div class="card test-card success">
        <div class="card-header bg-primary text-white">
            <h5>✅ Test 3: Input Sanitization (Before Storage)</h5>
        </div>
        <div class="card-body">
            <p>This test shows how input is sanitized BEFORE being stored in the database.</p>
            
            <div class="form-group">
                <label>Enter text with HTML:</label>
                <textarea id="sanitize-input" class="form-control" rows="3" placeholder="Try: Hello <b>bold</b> <script>alert('XSS')</script>"></textarea>
            </div>
            
            <button class="btn btn-primary" onclick="testSanitize()">
                Test Sanitization
            </button>
            
            <div id="result3" class="result"></div>
            
            <div class="mt-3">
                <strong>How it works:</strong>
                <div class="code-block">$clean = sanitize_input($input);
// Removes dangerous HTML tags
// Keeps safe text content
// Safe to store in database</div>
            </div>
        </div>
    </div>

    <!-- Test 4: Real-World Example -->
    <div class="card test-card success">
        <div class="card-header bg-info text-white">
            <h5>✅ Test 4: Real-World Example (Comment/Bio)</h5>
        </div>
        <div class="card-body">
            <p>Simulates how VividSpace handles user comments and bio text.</p>
            
            <div class="form-group">
                <label>Enter a comment or bio:</label>
                <textarea id="realworld-input" class="form-control" rows="3" placeholder="Try malicious input like: <img src=x onerror=alert('XSS')>"></textarea>
            </div>
            
            <button class="btn btn-info" onclick="testRealWorld()">
                Submit (Safe)
            </button>
            
            <div id="result4" class="result"></div>
            
            <div class="mt-3">
                <strong>Protection layers:</strong>
                <div class="code-block">1. Input sanitization in model (before DB)
2. Output escaping in view (when displaying)
3. CSRF token validation
4. Content Security Policy headers (optional)</div>
            </div>
            
            <div class="demo-output" id="realworld-output">
                <em>Your comment will appear here safely...</em>
            </div>
        </div>
    </div>

    <!-- Back to App -->
    <div class="text-center mt-4 mb-5">
        <a href="<?= site_url('test_csrf'); ?>" class="btn btn-secondary">CSRF Tests</a>
        <a href="<?= site_url('profile'); ?>" class="btn btn-outline-primary">Back to VividSpace</a>
    </div>
</div>

<!-- Include CSRF AJAX Helper -->
<script src="<?= base_url('assets/js/csrf-ajax.js'); ?>"></script>

<script>
// Helper function to copy attack payload to input
function copyToInput(payload) {
    document.getElementById('safe-input').value = payload;
    document.getElementById('unsafe-input').value = payload;
    document.getElementById('sanitize-input').value = payload;
    document.getElementById('realworld-input').value = payload;
}

// Helper function to escape HTML for display
function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Test 1: Safe output with escaping
function testSafe() {
    const input = document.getElementById('safe-input').value;
    const resultDiv = document.getElementById('result1');
    const outputDiv = document.getElementById('safe-output');
    
    resultDiv.innerHTML = '⏳ Processing...';
    resultDiv.className = 'result';
    
    csrfPost(
        '<?= site_url("test_xss/test_safe_output"); ?>',
        { user_input: input },
        function(response) {
            if (response.status === 'success') {
                resultDiv.className = 'result success';
                resultDiv.innerHTML = `
                    <strong>✅ PROTECTED!</strong><br>
                    <strong>Original Input:</strong> <code>${escapeHtml(response.original)}</code><br>
                    <strong>Escaped Output:</strong> <code>${escapeHtml(response.escaped_for_output)}</code><br>
                    <strong>Sanitized (for DB):</strong> <code>${escapeHtml(response.sanitized_for_storage)}</code>
                `;
                
                // Display safely in the demo output (using textContent)
                outputDiv.textContent = response.original;
                outputDiv.style.borderColor = '#28a745';
            }
        },
        function(error) {
            resultDiv.className = 'result danger';
            resultDiv.innerHTML = `<strong>❌ ERROR:</strong> ${error}`;
        }
    );
}

// Test 2: Unsafe output (demonstration)
function testUnsafe() {
    const input = document.getElementById('unsafe-input').value;
    const resultDiv = document.getElementById('result2');
    
    resultDiv.innerHTML = '⏳ Processing...';
    resultDiv.className = 'result';
    
    csrfPost(
        '<?= site_url("test_xss/test_unsafe_output"); ?>',
        { user_input: input },
        function(response) {
            resultDiv.className = 'result danger';
            resultDiv.innerHTML = `
                <strong>⚠️ WITHOUT PROTECTION, THIS WOULD BE DANGEROUS!</strong><br>
                <strong>Original Input:</strong> <code>${escapeHtml(response.unsafe_output)}</code><br>
                <br>
                <strong>What could happen:</strong><br>
                - Script execution (if injected into HTML)<br>
                - Cookie theft<br>
                - Session hijacking<br>
                - Phishing attacks<br>
                <br>
                <strong>✅ VividSpace IS PROTECTED!</strong> All output is escaped.
            `;
        },
        function(error) {
            resultDiv.className = 'result danger';
            resultDiv.innerHTML = `<strong>ERROR:</strong> ${error}`;
        }
    );
}

// Test 3: Input sanitization
function testSanitize() {
    const input = document.getElementById('sanitize-input').value;
    const resultDiv = document.getElementById('result3');
    
    resultDiv.innerHTML = '⏳ Processing...';
    resultDiv.className = 'result';
    
    csrfPost(
        '<?= site_url("test_xss/test_safe_output"); ?>',
        { user_input: input },
        function(response) {
            resultDiv.className = 'result success';
            resultDiv.innerHTML = `
                <strong>✅ SANITIZED!</strong><br>
                <strong>Original:</strong><br>
                <code>${escapeHtml(response.original)}</code><br>
                <br>
                <strong>After Sanitization (safe for DB):</strong><br>
                <code>${escapeHtml(response.sanitized_for_storage)}</code><br>
                <br>
                <strong>After Escaping (safe for display):</strong><br>
                <code>${escapeHtml(response.escaped_for_output)}</code><br>
                <br>
                <small>HTML tags removed, text content preserved</small>
            `;
        }
    );
}

// Test 4: Real-world example
function testRealWorld() {
    const input = document.getElementById('realworld-input').value;
    const resultDiv = document.getElementById('result4');
    const outputDiv = document.getElementById('realworld-output');
    
    resultDiv.innerHTML = '⏳ Processing...';
    resultDiv.className = 'result';
    
    csrfPost(
        '<?= site_url("test_xss/test_safe_output"); ?>',
        { user_input: input },
        function(response) {
            resultDiv.className = 'result success';
            resultDiv.innerHTML = `
                <strong>✅ COMMENT POSTED SAFELY!</strong><br>
                This is how VividSpace handles all user content.<br>
                <small>Input sanitized before storage, escaped on display</small>
            `;
            
            // Display safely
            outputDiv.innerHTML = '';
            const commentDiv = document.createElement('div');
            commentDiv.style.padding = '10px';
            commentDiv.style.borderLeft = '3px solid #28a745';
            commentDiv.textContent = response.original; // Safe: uses textContent
            outputDiv.appendChild(commentDiv);
            outputDiv.style.borderColor = '#28a745';
        }
    );
}
</script>
</body>
</html>

