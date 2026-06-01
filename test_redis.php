<?php
// Test Redis connection from inside the container
try {
    $redis = new Redis();
    $redis->connect('redis', 6379);
    echo "✅ REDIS PING: " . $redis->ping() . "\n";
    
    // Test set/get
    $redis->set('diabtrack_test', 'working');
    echo "✅ REDIS SET: OK\n";
    echo "✅ REDIS GET: " . $redis->get('diabtrack_test') . "\n";
    $redis->del('diabtrack_test');
    
    echo "\n✅ Redis está funcionando correctamente!\n";
} catch (Exception $e) {
    echo "❌ REDIS ERROR: " . $e->getMessage() . "\n";
}
