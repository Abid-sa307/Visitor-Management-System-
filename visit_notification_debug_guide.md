# Visit Form Notification Debug Guide

## 🔍 **Debug Steps for Visit Form Notifications**

### **Step 1: Test the Notification System Directly**
**URL:** `http://localhost:8000/debug-notification-test`

**What this does:**
- Simulates the exact session data that should be set after visit form submission
- Tests if the notification system is working correctly
- Shows you what should happen when notifications trigger

**Expected Results:**
- ✅ Alert popup: "🔔 Visit form submitted for visitor: Test Visitor"
- ✅ 15-second bell sound
- ✅ Browser notification
- ✅ Console debug messages

**If this works:** The notification system is working, issue is in the visit form submission
**If this doesn't work:** There's a fundamental issue with the notification system

---

### **Step 2: Check Laravel Logs**
After submitting a visit form, check `storage/logs/laravel.log` for these messages:

**Expected Log Messages:**
```
Visit form submitted - Checking notifications for visitor ID: [ID]
Visit form submitted - Visitor company ID: [Company ID]
Visit form submitted - Visitor company name: [Company Name]
Visit form submitted - Company enable_visitor_notifications: true/false
Visit form submitted - Visitor notifications ENABLED for company: [Company Name], playing notification for visitor: [Visitor Name]
```

**If you see "DISABLED":** The company doesn't have notifications enabled
**If you see "ENABLED":** The notification should trigger

---

### **Step 3: Check Browser Console**
After submitting visit form, open browser console (F12) and look for:

**Expected Console Messages:**
```
NOTIFICATION DEBUG: play_notification session found
NOTIFICATION DEBUG: visitor_name = "[Visitor Name]"
NOTIFICATION DEBUG: notification_message = "Visit form submitted for visitor: [Visitor Name]"
NOTIFICATION DEBUG: Testing immediate audio...
NOTIFICATION DEBUG: Immediate test audio played successfully
NOTIFICATION DEBUG: DOM loaded, starting notification process
```

**Error Messages to Look For:**
- Audio path errors
- Notification permission errors
- JavaScript errors

---

### **Step 4: Verify Company Settings**
**URL:** `http://localhost:8000/debug-company-settings`

**Check:**
- Is the company listed?
- Is "Visitor Notifications" set to ✅ YES?
- If ❌ NO, click "Enable Notifications" button

---

### **Step 5: Test Complete Flow**

1. **Create a visitor** for a company with notifications enabled
2. **Go to visit form** (should redirect automatically)
3. **Fill and submit visit form**
4. **Check for:**
   - Alert popup
   - Sound playing
   - Browser notification
   - Console logs
   - Laravel logs

---

## 🔧 **Common Issues & Solutions**

### **Issue 1: No Alert Popup**
**Possible Causes:**
- Session data not being passed correctly
- Layout not rendering notification script
- JavaScript errors

**Debug:**
- Check if `debug-notification-test` works
- Check browser console for JavaScript errors
- Verify session data in Laravel logs

### **Issue 2: No Sound**
**Possible Causes:**
- Audio file path incorrect
- Browser blocking autoplay
- Audio file not accessible

**Debug:**
- Test `debug-notification-test` for audio
- Check console for audio errors
- Try `test_audio_path.html` for path testing

### **Issue 3: No Browser Notification**
**Possible Causes:**
- Browser notification permission denied
- Notification API not supported
- Permission not requested

**Debug:**
- Check browser notification settings
- Look for permission requests
- Test `debug-notification-test`

### **Issue 4: Company Notifications Disabled**
**Possible Causes:**
- Company setting not enabled
- Wrong company being checked
- Database issue

**Debug:**
- Use `debug-company-settings` to verify
- Check Laravel logs for company info
- Enable notifications for the company

---

## 📋 **Quick Test Checklist**

### **Before Testing:**
- [ ] Company has notifications enabled (check via debug-company-settings)
- [ ] Audio file exists in `public/sounds/`
- [ ] Browser allows notifications and audio

### **After Submitting Visit Form:**
- [ ] Alert popup appears
- [ ] Sound plays for 15 seconds
- [ ] Browser notification appears
- [ ] Console shows debug messages
- [ ] Laravel logs show "ENABLED" message

### **If Something Fails:**
- [ ] Test `debug-notification-test` first
- [ ] Check browser console for errors
- [ ] Check Laravel logs
- [ ] Verify company settings

---

## 🚀 **Next Steps**

1. **Test `debug-notification-test`** - This tells us if the system works at all
2. **Check company settings** - Make sure notifications are enabled
3. **Submit visit form** - Test the actual flow
4. **Check logs** - Both browser console and Laravel logs
5. **Report results** - What worked and what didn't

**Start with the debug test - it will quickly identify if the issue is with the notification system or the visit form submission!** 🎯
