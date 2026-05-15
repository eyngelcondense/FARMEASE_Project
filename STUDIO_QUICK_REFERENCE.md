# Studio Management - Quick Reference Guide

## 🎯 Dashboard Overview
**URL:** `http://localhost:8080/admin/studios`

### Statistics Section (Top)
| Card | Shows | Use For |
|------|-------|---------|
| **Total** | All studios in system | Inventory management |
| **Active** | Studios available for booking | Current capacity |
| **Inactive** | Studios not available | Maintenance tracking |
| **Avg Capacity** | Average person count | Planning events |
| **Bookings** | Total bookings across all studios | Activity metrics |

---

## 🔍 Search & Filter Panel

### Search Box
- **What it searches:** Studio name, location, description
- **When it updates:** Instantly as you type
- **Performance:** Optimized with 250ms debounce

### Status Filter
```
[ All ] [ Active ] [ Inactive ]
```
- **All:** Show every studio
- **Active:** Only available studios
- **Inactive:** Only unavailable studios

### Action Buttons
| Button | Icon | Function |
|--------|------|----------|
| **Add Studio** | + | Open create form |
| **Refresh** | ⟳ | Reload data table |

---

## 📋 Data Table Columns

| Column | Content | Interactive |
|--------|---------|-------------|
| **Name** | Studio name | Clickable → View details |
| **Location** | Where it's located | Text only |
| **Capacity** | Max persons | Blue badge |
| **Cost/Hour** | Hourly rate (₱) | Text only |
| **Bookings** | # of bookings | Gray badge |
| **Status** | Active or Inactive | Green/Yellow badge |
| **Actions** | Control buttons | 4 buttons |

---

## ⚙️ Action Buttons (Per Studio)

### Button 1: View Details (Eye Icon)
- **What it does:** Opens studio detail page
- **Shows:** All studio info, bookings, revenue
- **Editing:** Edit button available from there

### Button 2: Edit (Pencil Icon)
- **What it does:** Opens edit modal/page
- **Fields:** Name, location, capacity, cost, description
- **Saves:** AJAX submission with instant feedback

### Button 3: Toggle Status (Circle with Check/X)
- **Green (Active):** Click to deactivate
- **Yellow (Inactive):** Click to activate
- **Result:** Status changes instantly

### Button 4: Delete (Trash Icon)
- **Confirmation:** "Are you sure?" dialog
- **Blocked if:** Studio has upcoming bookings
- **Permanent:** Cannot be undone

---

## ➕ Creating a New Studio

**Step 1:** Click "Add Studio" button  
**Step 2:** Fill form:
```
Studio Name:        [Required] Min 2, Max 255 chars
Location:           [Required] Where in resort
Capacity:           [Required] How many people
Cost per Hour:      [Required] Hourly rate (₱)
Description:        [Optional] Details, amenities, etc.
```
**Step 3:** Click "Create Studio"  
**Step 4:** Success message → Returns to dashboard

---

## ✏️ Editing a Studio

**Method 1: From Dashboard**
1. Click edit (pencil) icon in table row
2. Update any field
3. Click "Update Studio"
4. Changes saved with confirmation

**Method 2: From Detail Page**
1. Click view (eye) icon → opens detail page
2. Click "Edit Studio" button
3. Update fields
4. Click "Update Studio"

---

## 👁️ Viewing Studio Details

**Information Displayed:**
```
┌─────────────────────────────────────┐
│ Studio Name | Location | [Status]  │
├─────────────────────────────────────┤
│ Capacity: 50 | Rate: ₱1,500/hr    │
│ Total Bookings: 5 | Upcoming: 2    │
│ Revenue: ₱125,000                 │
├─────────────────────────────────────┤
│ Description: [Full details]        │
├─────────────────────────────────────┤
│ Recent Bookings (Last 10):         │
│ [Table of bookings]                │
├─────────────────────────────────────┤
│ [Edit] [Deactivate] [Delete] [Back]│
└─────────────────────────────────────┘
```

---

## 🔒 Common Restrictions

### Cannot Delete Studio If:
- ❌ Has bookings on current date or later
- ✅ Has past bookings only (OK to delete)

### Cannot Modify:
- Nothing is locked (all fields editable)

### Auto-Restrictions:
- Cannot create duplicate bookings
- Cannot overbook same time slot
- Cost must be positive number

---

## 💰 Cost & Pricing

### Studio Rates
- **Hourly Rate:** Set per studio in ₱
- **Example:** 1,500 per hour
- **Calculation:** Rate × Duration (hours)
- **Fee:** Added +10% admin fee to total

### Booking Cost Example
```
Studio Rate:        ₱1,500/hour
Duration:           3 hours
Base Cost:          ₱4,500
Admin Fee (10%):    ₱450
Total Studio Cost:  ₱4,950
```

---

## 📊 Analytics (Available via API)

**Endpoint:** `GET /admin/studios/statistics?month=2026-05&studio_id=1`

**Returns:**
- Total bookings for period
- Total revenue generated
- Average booking value
- Utilization rate (%)

---

## 🔄 Integration with Other Systems

### When Booking Is Created
- Studio selected by client
- Availability checked instantly
- Pricing calculated with 10% fee

### When Booking Is Approved
- Studio marked as booked
- Staff assignment form shows studio pre-filled
- Cannot assign conflicting staff

### When Payment Is Processed
- Studio cost included in total
- Line item shown: "₱X.XX in studio booking"

### When Contract Is Generated
- Studio details embedded in PDF
- Location, capacity, rate included

---

## ⚡ Quick Actions

| Action | URL/Shortcut | Time |
|--------|-------------|------|
| Go to Studios | `/admin/studios` | 1 click |
| Create Studio | `/admin/studios/create` | 2 clicks |
| Edit Studio #5 | `/admin/studios/5/edit` | 1 click |
| View Studio #5 | `/admin/studios/5` | 1 click |

---

## 📱 Responsive Design

- **Desktop:** Full data table with all columns
- **Tablet:** Columns collapse, card layout
- **Mobile:** Stack vertically, essential info only
- **All:** Touch-friendly buttons and spacing

---

## 🆘 Troubleshooting

### Studio Not Appearing in Dropdown
**Solution:** Check if:
1. Studio is set to Active status
2. Capacity ≥ guest count needed
3. Date/time slots are not booked

### Cannot Delete Studio
**Solution:** 
1. Check for upcoming bookings
2. Only past bookings → should be deletable
3. Contact admin if still blocked

### Search Not Working
**Solution:**
1. Check internet connection
2. Wait 250ms for debounce
3. Try refreshing page
4. Clear search and try again

### Wrong Cost Showing
**Solution:**
1. Verify hourly rate in studio details
2. Check admin fee is 10%
3. Multiply by actual duration

---

## 💡 Tips & Best Practices

1. **Always set description** - Helps clients understand studio features
2. **Check bookings before deactivating** - Won't prevent deactivation but shows info
3. **Use consistent naming** - "Studio A", "Studio B" easier than nicknames
4. **Update capacity accurately** - Used for availability checking
5. **Regular review** - Check active vs inactive ratio monthly

---

## 🔒 User Permissions

**Who can access Studio Management:**
- ✅ Admin users only
- ❌ Clients cannot access
- ❌ Staff cannot access (read-only in some contexts)

**Login required:** Yes  
**Role required:** Admin  
**Group:** Must be in 'admin' group

---

## 📞 Support

**For issues contact:** Admin Dashboard Support  
**For enhancements request:** Development Team  
**For reports:** Use Statistics API  
**For exports:** Use AJAX endpoints

---

**Last Updated:** May 15, 2026  
**System:** San Isidro Labrador Resort - Event Management
