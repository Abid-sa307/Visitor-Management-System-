// Debug script to test the current notification system
console.log('🔍 NOTIFICATION DEBUG: Loading debug script...');

// Test 1: Check if Notification API is available
if ('Notification' in window) {
    console.log('✅ Notification API is available');
    console.log('📋 Current permission:', Notification.permission);
} else {
    console.log('❌ Notification API is NOT available');
}

// Test 2: Test audio file loading
function testAudioFile() {
    console.log('🔊 Testing audio file...');
    const audio = new Audio('/sounds/mixkit-bell-notification-933.wav');
    
    audio.addEventListener('canplaythrough', () => {
        console.log('✅ Audio file can be played');
    });
    
    audio.addEventListener('error', (e) => {
        console.log('❌ Audio file error:', e);
    });
    
    // Try to load the audio
    audio.load();
}

// Test 3: Test immediate audio playback
function testImmediateAudio() {
    console.log('🔊 Testing immediate audio playback...');
    try {
        const audio = new Audio('/sounds/mixkit-bell-notification-933.wav');
        audio.volume = 0.5;
        audio.play().then(() => {
            console.log('✅ Immediate audio played successfully');
        }).catch(e => {
            console.log('❌ Immediate audio failed:', e);
            
            // Check if it's a browser policy issue
            if (e.name === 'NotAllowedError') {
                console.log('🚫 Audio blocked by browser policy - user interaction required');
            }
        });
    } catch (e) {
        console.log('❌ Audio creation failed:', e);
    }
}

// Test 4: Test browser notification
function testBrowserNotification() {
    console.log('🔔 Testing browser notification...');
    
    if (Notification.permission === 'granted') {
        const notification = new Notification('Debug Test', {
            body: 'This is a debug test notification',
            icon: '/favicon.ico',
            badge: '/favicon.ico'
        });
        
        console.log('✅ Browser notification created');
        
        setTimeout(() => {
            notification.close();
            console.log('🔕 Browser notification closed');
        }, 3000);
    } else {
        console.log('❌ Browser notification permission not granted');
    }
}

// Test 5: Check for session data
function checkSessionData() {
    console.log('📋 Checking for notification session data...');
    
    // This would normally be set by the backend
    // For testing, we'll simulate it
    console.log('🔍 Looking for play_notification session data...');
    
    // Check if there are any notification-related elements on the page
    const notificationScripts = document.querySelectorAll('script');
    let foundNotificationCode = false;
    
    notificationScripts.forEach(script => {
        if (script.textContent.includes('play_notification') || 
            script.textContent.includes('NOTIFICATION DEBUG')) {
            foundNotificationCode = true;
            console.log('✅ Found notification code in page');
        }
    });
    
    if (!foundNotificationCode) {
        console.log('❌ No notification code found in page');
    }
}

// Run all tests
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM loaded, running notification tests...');
    
    testAudioFile();
    testImmediateAudio();
    testBrowserNotification();
    checkSessionData();
    
    // Add a button to manually trigger tests
    const debugButton = document.createElement('button');
    debugButton.textContent = '🔔 Test Notifications Now';
    debugButton.style.position = 'fixed';
    debugButton.style.top = '10px';
    debugButton.style.right = '10px';
    debugButton.style.zIndex = '9999';
    debugButton.style.background = '#007bff';
    debugButton.style.color = 'white';
    debugButton.style.border = 'none';
    debugButton.style.padding = '10px';
    debugButton.style.borderRadius = '5px';
    debugButton.style.cursor = 'pointer';
    
    debugButton.addEventListener('click', function() {
        console.log('🔄 Manual test triggered...');
        testImmediateAudio();
        testBrowserNotification();
    });
    
    document.body.appendChild(debugButton);
    
    console.log('✅ Debug script loaded. Click the blue button in the top-right to test notifications manually.');
});

// Auto-test after 2 seconds
setTimeout(() => {
    console.log('⏰ Auto-testing after 2 seconds...');
    testImmediateAudio();
    testBrowserNotification();
}, 2000);
