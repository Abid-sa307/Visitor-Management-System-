# Visitor Notifications Debug Guide

## 🔍 **Debugging Steps**

Please follow these steps to debug the visitor notifications:

### **Step 1: Test Visitor Creation**
1. Make sure the company has "Enable Visitor Notifications" checked
2. Create a new visitor for that company
3. Watch for the following indicators:

### **Step 2: Check Browser Alert**
**Question:** Do you see an alert popup that says "DEBUG: Notification should play! Visitor: [Name]"?

- **If YES**: Session data is working, issue is with audio/browser notifications
- **If NO**: Session data is not being passed correctly

### **Step 3: Check Browser Console**
Open browser developer tools (F12) and check the Console tab for these messages:

```
NOTIFICATION DEBUG: play_notification session found
NOTIFICATION DEBUG: visitor_name = "[Visitor Name]"
NOTIFICATION DEBUG: play_notification value = "true"
NOTIFICATION DEBUG: DOM loaded, starting notification process
NOTIFICATION DEBUG: Requesting notification permission
NOTIFICATION DEBUG: Permission result: [granted/denied]
NOTIFICATION DEBUG: Attempting to play audio
NOTIFICATION DEBUG: Audio playing successfully
NOTIFICATION DEBUG: Showing browser notification
```

### **Step 4: Check Laravel Logs**
Check `storage/logs/laravel.log` for these messages:

```
Checking visitor notifications for visitor ID: [ID]
Visitor company ID: [ID]
Company enable_visitor_notifications: [true/false]
Visitor notifications ENABLED for company: [Company Name], playing notification for visitor: [Visitor Name]
```

### **Step 5: Test Audio File Directly**
Open this URL directly in your browser:
`http://localhost:8000/sounds/mixkit-bell-notification-933.wav`

- **If it plays**: Audio file is accessible
- **If it doesn't**: Audio file issue or path problem

### **Step 6: Check Browser Notification Permissions**
1. In Chrome: Settings → Privacy and security → Site Settings → Notifications
2. In Firefox: Options → Privacy & Security → Permissions → Notifications
3. Make sure `localhost` is allowed to show notifications

## 🛠️ **Common Issues & Solutions**

### **Issue 1: No Alert Popup**
**Cause:** Session data not being passed
**Solution:** Check Laravel logs for controller debug messages

### **Issue 2: Alert Shows But No Audio**
**Cause:** Browser audio policy or file path issue
**Solution:** Check console for audio errors, test audio file directly

### **Step 3: Alert Shows But No Browser Notification**
**Cause:** Browser notification permission denied
**Solution:** Grant notification permission to localhost

### **Issue 4: Debug Messages Missing**
**Cause:** Layout not being loaded or cache issue
**Solution:** Clear browser cache, check correct layout is being used

## 📋 **Report Back With:**

1. **Alert popup status:** Yes/No
2. **Console messages:** Copy/paste relevant debug messages
3. **Laravel log messages:** Copy/paste relevant log entries
4. **Audio file test:** Works/Doesn't work
5. **Browser notification permission:** Granted/Denied

## 🎯 **Quick Test**

If you want to test immediately, you can manually trigger the notification by visiting:
`http://localhost:8000/visitors/create?test_notification=1`

(This will require adding a test route, but the main test should work with normal visitor creation)
