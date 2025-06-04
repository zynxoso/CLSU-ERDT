# Notifications System Documentation

## Overview

The CLSU-ERDT Scholar Management System includes a notification system that informs users about important events and status changes. Notifications are sent through multiple channels, including email and database storage for in-app notifications.

## Notification Types

### Fund Request Status Changes

When an administrator changes the status of a fund request (to "Approved", "Under Review", or "Rejected"), the system automatically sends a notification to the scholar who submitted the request.

#### Implementation Details

The notification is implemented using the `FundRequestStatusChanged` notification class, which:

1. Sends an email to the scholar with details about the status change
2. Stores the notification in the database for display in the application's notification center
3. Includes relevant information such as:
   - The previous status
   - The new status
   - Any admin notes or comments
   - A link to view the fund request

#### Code Example

```php
// Send notification to scholar when fund request status changes
$scholar = $fundRequest->user;
if ($scholar) {
    $scholar->notify(new FundRequestStatusChanged(
        $fundRequest,
        $oldStatus,
        'Approved', // or 'Under Review', 'Rejected'
        $notes // Optional admin notes
    ));
}
```

### Manuscript Status Changes

Similar to fund requests, when a manuscript's status changes, the system notifies the relevant scholar using the `ManuscriptStatusChanged` notification class.

## Notification Channels

The system uses the following notification channels:

1. **Email**: Sends detailed notifications to the user's email address
2. **Database**: Stores notifications in the database for display in the application

## Database Structure

Notifications are stored in the `notifications` table with the following structure:

- `id`: Unique identifier for the notification
- `type`: The notification class (e.g., `App\Notifications\FundRequestStatusChanged`)
- `notifiable_type`: The model type that receives the notification (typically `App\Models\User`)
- `notifiable_id`: The ID of the user receiving the notification
- `data`: JSON data containing notification details
- `read_at`: Timestamp when the notification was read (null if unread)
- `created_at`: Timestamp when the notification was created
- `updated_at`: Timestamp when the notification was last updated

## Future Enhancements

Potential future enhancements to the notification system:

1. SMS notifications for urgent status changes
2. Push notifications for mobile users
3. Customizable notification preferences for users
4. Notification digests (daily/weekly summaries)
