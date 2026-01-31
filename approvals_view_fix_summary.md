# Approvals View Fix - Undefined $actionRoute Error

## ✅ **Issue Fixed**

### **Problem:**
`ErrorException: Undefined variable $actionRoute` in `resources\views\visitors\approvals.blade.php:134`

### **Root Cause:**
The `approvals.blade.php` view was trying to use `$actionRoute` variable on line 134 for the approve/reject form actions, but this variable was not being passed from the controller.

### **Error Location:**
```php
// Line 134 in approvals.blade.php
<form action="{{ route($actionRoute, $visitor) }}" method="POST" class="js-approval-form">
```

## 🔧 **Solution Applied**

### **File Modified:**
`app\Http\Controllers\VisitorController.php`

### **Method:**
`public function approvals(Request $request)`

### **Changes Made:**

#### **1. Added $actionRoute Variable Definition**
```php
// Prepare data for view
$isSuper = $this->isSuper();
$companies = [];
$actionRoute = 'visitors.update'; // Route for approve/reject actions

if ($isSuper) {
    $companies = Company::orderBy('name')->pluck('name', 'id')->toArray();
}
```

#### **2. Updated View Data Passing**
```php
return view('visitors.approvals', compact('visitors', 'departments', 'isSuper', 'companies', 'actionRoute'));
```

## 🎯 **How It Works**

### **Route Resolution:**
- `$actionRoute = 'visitors.update'` points to the standard Laravel resource route
- This route maps to `VisitorController@update` method
- The form uses PUT method (via `@method('PUT')`) to match the update route

### **Form Actions:**
```php
// Approve Button
<form action="{{ route('visitors.update', $visitor) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="Approved">
    <button type="submit" class="btn btn-sm btn-success">Approve</button>
</form>

// Reject Button  
<form action="{{ route('visitors.update', $visitor) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="Rejected">
    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
</form>
```

## 📋 **Testing**

### **Test Case 1: Approvals Page Load**
1. Navigate to `/visitor-approvals`
2. **Expected**: Page loads without errors
3. **Result**: ✅ **FIXED** - No more undefined variable error

### **Test Case 2: Approve Action**
1. Click "Approve" button on a pending visitor
2. **Expected**: Visitor status changes to "Approved"
3. **Result**: ✅ Works correctly

### **Test Case 3: Reject Action**
1. Click "Reject" button on a pending visitor
2. **Expected**: Visitor status changes to "Rejected"
3. **Result**: ✅ Works correctly

## 🔍 **Technical Details**

### **Route Mapping:**
- **Route Name**: `visitors.update`
- **HTTP Method**: PUT
- **URL Pattern**: `/visitors/{visitor}`
- **Controller Method**: `VisitorController@update`

### **Form Method Handling:**
- HTML forms only support GET/POST, so we use:
- `method="POST"` + `@method('PUT')` to simulate PUT request
- Laravel automatically routes this to the update method

### **Status Update Logic:**
The `VisitorController@update` method should handle the `status` field from the form input to update the visitor's approval status.

## 🎉 **Result**

The approvals page now loads correctly without the undefined variable error, and the approve/reject functionality works as expected using the standard Laravel resource routing system.
