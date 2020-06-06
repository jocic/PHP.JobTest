# Test

Form should represent meeting scheduler.

## Frontend 

Input fields (all required except note):

*  Name
*  Phone
*  Email
*  Time (time picker)
*  Date (date picker)

**Notes:**

*  Form should have some spam/robot protection.
*  Email and Phone fields should have standard validation
*  Form should have success/fail messages

## Backend

After SUBMIT, the form will connect to the google calendar account via Google calendar API, and create the calendar event with the reminder (client should receive remainders 15 and 30 mins before the meeting).

**Notes:**

*  Task should be hosted on Github and shared a link over email  xxx@xxxxxx.xx.xx.
*  Event should have all entered data.
*  Notification that event is created should be delivered to entered email.
*  Create some fake google account to store calendar events.
*  Task should include ReadMe.md file within the documentation.
