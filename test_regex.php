<?php

echo "Testing regex patterns from LogSuspiciousRequests middleware...\n\n";

$patterns = [
    'sqli_attempt' => '/(union\s+select|information_schema|drop\s+table|or\s+1=1|--)/i',
    'xss_attempt' => '/(<script>|javascript:|onerror=|onload=|alert\()/i',
    'path_traversal' => '/(\.\.\/|\.\.\\\\)/',
];

$testCases = [
    'sqli_attempt' => ['union select', 'information_schema', 'drop table', 'or 1=1', '--'],
    'xss_attempt' => ['<script>', 'javascript:', 'onerror=', 'onload=', 'alert('],
    'path_traversal' => ['../', '..\\'],
];

foreach ($patterns as $type => $pattern) {
    echo "Testing pattern: $type\n";
    echo "Pattern: $pattern\n";
    
    try {
        // Test if pattern is valid
        $result = @preg_match($pattern, 'test');
        if ($result === false) {
            echo "❌ ERROR: Invalid regex pattern!\n";
            echo "Error: " . preg_last_error_msg() . "\n\n";
        } else {
            echo "✅ Pattern is valid!\n";
            
            // Test with actual malicious inputs
            if (isset($testCases[$type])) {
                echo "Testing with sample inputs:\n";
                foreach ($testCases[$type] as $testInput) {
                    $match = preg_match($pattern, $testInput);
                    echo "  - '$testInput': " . ($match ? "✅ Detected" : "❌ Not detected") . "\n";
                }
            }
            echo "\n";
        }
    } catch (Exception $e) {
        echo "❌ EXCEPTION: " . $e->getMessage() . "\n\n";
    }
}

echo "All tests completed!\n";
