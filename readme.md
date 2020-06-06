# Job Test

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

## Setup

1. Enable Recaptcha v3 by changing the "XXXXXX" placeholders with their appropriate values in the "index.html" file.
2. Install required dependencies by executing "composer install" command from the "source" directory.
3. Generate Calendar API credentials and save them to the root directory under the "calendar_credentials.json" and "calendar_client_secret.json" filenames.
