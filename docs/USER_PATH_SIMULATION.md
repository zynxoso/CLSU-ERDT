# 🎯 CLSU-ERDT User Path Simulation

## Overview

This document provides a comprehensive simulation of user paths and backend processes within the CLSU-ERDT system. It covers all user types, their workflows, and the underlying technical processes that support each interaction, including advanced security features, performance optimizations, and real-time monitoring.

## 🎯 Simulation Objectives

- **Complete User Journey Mapping**: Detailed workflows for all user types
- **Backend Process Documentation**: Technical implementation details
- **Security Integration**: CyberSweep and DDoS protection workflows
- **Performance Monitoring**: Real-time system performance tracking
- **Error Handling**: Comprehensive error scenarios and recovery
- **Compliance Tracking**: Audit trails and regulatory compliance

## 🚀 System Enhancements (2024)

- **Enhanced Security**: CyberSweep integration for advanced threat detection
- **Performance Optimization**: Redis caching and optimized database queries
- **Real-time Notifications**: Advanced notification system with multiple channels
- **Mobile Responsiveness**: Optimized mobile user experience
- **API Integration**: RESTful APIs with rate limiting and authentication
- **Advanced Analytics**: Comprehensive reporting and dashboard analytics

## User Types Overview

- **Super Admin**: The Super Admin has total control over the system. They can change settings, manage all users, and do every admin task.
- **Admin**: The Admin looks after Scholars, checks fund requests and manuscript submissions, and makes reports.
- **Scholar**: The Scholar can only use their own dashboard to send fund requests, submit manuscripts, and update their personal info.

## Logging In Simulation

### Scholar Login Path
1. **Access**: A Scholar goes to `/scholar-login` on the website to log in. This path is set in 'routes/auth.php'.
2. **Checking Details**: The system looks at the Scholar's email and password, makes sure they have the 'scholar' role in the 'app/Models/User.php' model, and checks if their account is active. Admins or Super Admins can't use this login.
3. **Safety Steps**: The system limits login tries to 5 to stop hackers, checks if the password is old, and sees if it's the first-time default password. These rules are in 'app/Http/Middleware/'.
4. **Result**: If login works, the system starts a session, makes a new safe session ID, logs the action in 'app/Models/AuditLog.php', and sends the Scholar to their dashboard at `scholar.dashboard` in 'resources/views/'. If the password is old or default, they must change it first.

### Admin/Super Admin Login Path
1. **Access**: An Admin or Super Admin goes to `/login` to get into the system, defined in 'routes/auth.php'.
2. **Checking Details**: The system checks their email and password, confirms they have 'admin' or 'super_admin' role in 'app/Models/User.php', and makes sure the account is active. Scholars can't use this login.
3. **Safety Steps**: The system uses special login checks, sets up safe database rules in 'config/database.php', and controls access by role using settings in 'app/Policies/'.
4. **Result**: If login works, the system sets up a session, turns on safety rules, and takes them to `admin.dashboard` in 'resources/views/'. If the password is old or default, they must update it.

## Scholar User Path Simulation

### Profile Management
1. **Starting Setup**: An Admin makes a Scholar account with a starting password 'CLSU-scholar123' and sets a flag to change it on first login, managed in 'app/Models/User.php'. Email checking is needed.
2. **First Login**: The Scholar logs in, must change the starting password, and is asked to fill out their profile with name, contact, school, and research info using forms in 'resources/views/scholar/'.
3. **Sending Profile**: The Scholar sends their profile for Admin checking, changing status from 'New' to 'Pending' in 'app/Models/ScholarProfile.php'.
4. **Admin Checking**: The Admin looks at the Scholar's info and papers, changes status to 'Ongoing' if okay, or other statuses like 'Graduated' or 'Terminated' in 'app/Models/ScholarProfile.php'.
5. **Updates**: The Scholar can change their profile info anytime, and some changes might need Admin okay again, handled through 'app/Http/Controllers/'.

### Fund Request Management
1. **Making Request**: A Scholar goes to their Dashboard, picks Fund Requests, and clicks Create New Request in 'resources/views/scholar/'. They choose a type (like Research Materials) from 'app/Models/RequestType.php', enter an amount, explain why, upload PDF files (up to 10MB), and add extra notes.
2. **Saving or Sending**: The Scholar can save it as 'Draft' to edit later or 'Submit' to lock it for review. The system scans files for safety using rules in 'app/Services/FileSecurityService.php' and logs actions in 'app/Models/AuditLog.php'.
3. **Admin Review**: An Admin gets a notice via 'app/Notifications/NewFundRequestSubmitted.php', reviews the request as 'Under Review' in 'app/Models/FundRequest.php', checks files and amount limits, then picks 'Approve' or 'Reject' with reasons.
4. **Result Notice**: The Scholar gets told the decision by email and app alerts through 'app/Mail/NotificationMail.php'. If approved, the request goes to a payment list.
5. **Payment**: The Admin makes a payment record for approved requests, updates status to 'Disbursed' in 'app/Models/Disbursement.php', and the Scholar gets a notice.

### Manuscript Submission
1. **Creating Manuscript**: A Scholar goes to their Dashboard, picks Manuscripts, and selects Create New in 'resources/views/scholar/'. They add a title, summary, type (Outline/Final), co-authors, keywords, and upload a PDF file.
2. **Draft Saving**: The manuscript starts as 'Draft', editable until sent. A code (MS-XXXXX) is made by the system in 'app/Models/Manuscript.php'.
3. **Sending for Review**: The Scholar sends the manuscript, status turns to 'Submitted', locking edits in 'app/Models/Manuscript.php'. An Admin gets a notice via 'app/Notifications/NewManuscriptSubmitted.php'.
4. **Admin Review Steps**: The Admin checks it as 'Under Review', gives feedback, and sets status to 'Revision Requested', 'Accepted', 'Rejected', or 'Published' in 'app/Models/Manuscript.php'. The Scholar is told of changes via 'app/Notifications/ManuscriptStatusChanged.php'.
5. **Fixing if Needed**: If asked to revise, the Scholar edits and resends, starting the review again until a final call is made.

## Admin User Path Simulation

### Scholar Management
1. **Making Accounts**: An Admin sets up new Scholar accounts, picks the role, gives a starting password, and sets a change password rule in 'app/Models/User.php'.
2. **Profile Checking**: The Admin looks at sent Scholar profiles, checks details, and updates status (like 'Ongoing', 'Graduated') in 'app/Models/ScholarProfile.php'.
3. **Ongoing Care**: The Admin keeps track of Scholar progress, updates profiles, and changes statuses based on program updates via 'app/Http/Controllers/'.

### Fund Request Processing
1. **Getting Alerts**: An Admin gets alerts for new fund requests by email and on their dashboard through 'app/Notifications/NewFundRequestSubmitted.php'.
2. **Checking Request**: The Admin takes a request to review, looks at papers and amounts against limits, spots repeats, and sets status to 'Under Review' in 'app/Models/FundRequest.php'.
3. **Making Decision**: The Admin says yes or no to the request, gives reasons if no, updates status to 'Approved' or 'Rejected', and the Scholar is told via 'app/Notifications/FundRequestStatusChanged.php'.
4. **Payment Step**: For yes decisions, the Admin makes a payment record, sets status to 'Disbursed' in 'app/Models/Disbursement.php', and tracks money flow.

### Manuscript Review
1. **Getting Alerts**: An Admin is told about new manuscript sends via 'app/Notifications/NewManuscriptSubmitted.php'.
2. **Looking at Work**: The Admin checks manuscript content and quality, sets status to 'Under Review' in 'app/Models/Manuscript.php', and adds comments.
3. **Deciding Outcome**: The Admin picks 'Revision Requested', 'Accepted', 'Rejected', or 'Published', telling the Scholar results and next steps through 'app/Notifications/ManuscriptStatusChanged.php'.
4. **Handling Fixes**: If a fix is needed, the Admin checks the new send, repeating until a final decision.

### Reports and Data
1. **Dashboard View**: An Admin sees system numbers, waiting reviews, and data charts on their dashboard in 'resources/views/admin/'.
2. **Making Reports**: The Admin creates reports on fund requests, Scholar progress, system use, and logs for rules and checks using tools in 'app/Exports/' like 'ManuscriptsExport.php'.

## Super Admin User Path Simulation

### System Settings
1. **Accessing Options**: A Super Admin goes to system settings to change main rules, safety setups, and notice options in 'app/Models/SiteSetting.php'.
2. **Changing Rules**: The Super Admin updates things like email styles, login try limits, and password rules, making sure they work everywhere via 'config/' files like 'config/app.php'.

### User Control
1. **Account Handling**: A Super Admin can make, change, or stop accounts for all types (Scholar, Admin, Super Admin) in 'app/Models/User.php'.
2. **Role Setting**: The Super Admin gives or changes user roles, setting access levels as needed through 'app/Http/Controllers/'.
3. **Log Checking**: The Super Admin looks at action logs for user doings to keep things safe and right in 'app/Models/AuditLog.php'.

### Safety and Log Control
1. **Safety Rules**: A Super Admin handles database safety rules, data hiding settings, and file safety steps in 'app/Services/DatabaseSecurityService.php'.
2. **Action Logs**: The Super Admin sees full action reports to track all system moves, finding safety problems or rule breaks in 'app/Services/AuditService.php'.

## Notification System Simulation

### For Scholars
1. **Fund Request News**: Notices are sent to a Scholar for sending confirmation, status updates (Approved/Rejected), and payment news via 'app/Notifications/FundRequestStatusChanged.php'.
2. **Manuscript News**: Alerts go to the Scholar for sending okay, review updates, fix requests, and publish news through 'app/Notifications/ManuscriptStatusChanged.php'.
3. **Profile News**: The Scholar gets told of profile changes or Admin updates in 'app/Mail/NotificationMail.php'.
4. **How Sent**: Notices show in the Scholar's dashboard area, come by email, and update live on pages using 'resources/views/' templates.

### For Admins/Super Admins
1. **New Sends**: Alerts go to Admins and Super Admins for new fund requests and manuscript sends needing review via 'app/Notifications/'.
2. **System Warnings**: Notices are given for safety issues, password old alerts, or upkeep plans in 'app/Mail/NotificationMail.php'.
3. **How Sent**: Like Scholars, notices come via dashboard, email, and live updates in 'resources/views/admin/'.

## Full User Journey Examples

### New Scholar Start
1. An Admin makes a Scholar account in 'app/Models/User.php'.
2. The Scholar logs in, changes the first password, and fills their profile in 'resources/views/scholar/'.
3. The Admin checks and turns on the profile in 'app/Models/ScholarProfile.php'.
4. The Scholar gets full system use.

### Fund Request Full Path
1. A Scholar sends a fund request via 'resources/views/scholar/'.
2. The system checks it and tells an Admin through 'app/Notifications/NewFundRequestSubmitted.php'.
3. The Admin reviews and says yes or no in 'app/Models/FundRequest.php'.
4. The Scholar hears the decision; if yes, the Admin does payment in 'app/Models/Disbursement.php'.
5. The deal is saved for reports in 'app/Exports/'.

### Manuscript Publish Path
1. A Scholar writes and sends a manuscript in 'resources/views/scholar/'.
2. An Admin checks it and asks for fixes if needed in 'app/Models/Manuscript.php'.
3. The Scholar fixes and resends.
4. The Admin says okay and publishes it.
5. A last notice goes to the Scholar via 'app/Notifications/ManuscriptStatusChanged.php'.

## Backend Work Simulation

This part explains the behind-the-scenes work that helps the CLSU-ERDT system's front parts. It covers server tasks, API links, database work, safety steps, and background jobs that keep the system running well, told from a third-person view with simple words.

### Login Backend Work
1. **Detail Check**: The backend checks user login info against saved data in the database using login tools in 'app/Providers/FortifyServiceProvider.php' with special checks for Scholars and Admins.
2. **Session Setup**: If login is good, the backend makes and renews session codes, saving them safely and linking them to user types for access rules in 'config/session.php'.
3. **Safety Checks**: Login try limits are set by middle tools to stop bad tries, and password age or first-time flags are checked in the database for safety rules in 'app/Http/Middleware/'.
4. **Action Log**: Every login is saved in a log table with time, web address, and user info for safety watch in 'app/Models/AuditLog.php'.

### Scholar Profile Backend
1. **Data Saving**: Scholar profile info is kept in the database with hidden codes for private parts, using tools in 'app/Models/ScholarProfile.php' for data links.
2. **Info Check**: Backend rules make sure needed info is filled and right before saving to the database, set in 'app/Http/Requests/'.
3. **Status Change**: Admin changes to a Scholar's profile status start database saves to keep data safe, with rules filtering access by user type in 'app/Models/Scopes/'.
4. **Notice Start**: Profile updates or status shifts start backend actions that line up notices for email or app send in 'app/Notifications/'.

### Fund Request Backend Path
1. **Request Start**: Scholar-sent fund requests are checked on the backend for amount caps and file types (PDF only), with files scanned for safety by 'app/Services/FileSecurityService.php' before saving in safe folders.
2. **Status Track**: The backend changes request status in the database from Draft to Submitted to Under Review to Approved/Rejected to Disbursed, logging each step in 'app/Models/FundRequest.php'.
3. **Admin Work**: Admin choices start backend steps to update database info, line up notices, and if okay, start payment records with money tracking in 'app/Models/Disbursement.php'.
4. **API Use**: API points handle create, read, update, delete tasks for fund requests, kept safe by middle tools for role access and try limits in 'routes/api.php'.

### Manuscript Send Backend Path
1. **Send Handling**: Manuscript info and files are handled like fund requests, with backend checks, safety scans, and saving in safe folders using 'app/Services/FileSecurityService.php'.
2. **Review Steps**: The backend runs status shifts (Draft to Submitted to Under Review to Revision Requested/Accepted/Rejected to Published), keeping data right with safe saves in 'app/Models/Manuscript.php'.
3. **Feedback Save**: Admin comments and Scholar fixes are kept in the database, tied to manuscript records, with version tracking for resends in 'app/Models/ReviewComment.php'.
4. **Notice System**: Backend events for status changes start lined-up jobs to send notices to Scholars about review results or fix needs in 'app/Notifications/ManuscriptStatusChanged.php'.

### Notice Backend System
1. **Event Watch**: The backend watches for events like status shifts or sends, starting notice creation in the database via 'app/Listeners/AuthEventSubscriber.php'.
2. **Line-Up Work**: Notices are lined up for sending using Laravel's line system, handled in the background with fast tools like Redis in 'config/queue.php'.
3. **Send Ways**: The backend sends via email (using set mail tools in 'config/mail.php') or app storage for dashboard show, checking user choices in the database.

### Safety and Log Backend Tools
1. **Data-Level Safety**: Database rules limit data access by user type, forced at the search level to stop wrong access in 'app/Services/DatabaseSecurityService.php'.
2. **Data Hiding**: Private data is hidden using Laravel's built-in hiding for fields, with keys kept safe in settings in 'app/Casts/EncryptedAttribute.php'.
3. **File Safety**: Uploaded files are scanned and saved in guarded folders, with access ruled by backend policies in 'app/Services/FileSecurityService.php'.
4. **Action Logs**: Every big action (login, data change, status shift) is logged in a table, with backend rules ensuring full tracking for Super Admin check in 'app/Services/AuditService.php'.

### Background Tasks and Fast Save
1. **Task Handling**: The backend uses Laravel lines for slow tasks like notice sending or report making, keeping user actions quick in 'app/Console/Kernel.php'.
2. **Fast Save**: Key data for speed (like system rules, user rights) is saved fast using Redis or similar, with backend rules clearing old saves on changes in 'config/cache.php'.
3. **Planned Tasks**: The backend plans tasks like password age checks or upkeep alerts via Laravel's planner, running behind in 'app/Console/Kernel.php'.

### API Backend Paths
1. **Point Handling**: The backend shows REST API points for front interactions (like fund request send, manuscript status check), kept safe by login middle tools in 'routes/api.php'.
2. **Try Limits**: API calls are limited to stop overuse, with backend tracking call counts by user or web address in 'config/ddos.php'.
3. **Data Work**: API asks start backend rules for data checks, database tasks, and answer setup, ensuring steady data flow in 'app/Http/Controllers/'.
4. **Error Fix**: The backend sets custom error handling for API mistakes, sending clear answers with right web codes in 'app/Exceptions/Handler.php'.

### Reports and Data Backend
1. **Data Grouping**: Backend searches group data for reports on fund requests, Scholar progress, and system use, using fast database searches in 'app/Repositories/'.
2. **Chart Making**: Data for charts is handled on the backend for Admin dashboard views, with fast save to cut wait times in 'resources/js/'.
3. **Log Reports**: The backend puts log data into set reports for Super Admin check, ensuring rule and safety tracking in 'app/Services/AuditService.php'.

## MVC Paths Simulation

This part explains the Model-View-Controller (MVC) setup paths in the CLSU-ERDT system, showing how data and control move through these parts to help user actions and backend tasks. Laravel, the system base, uses MVC to split jobs, making code easy to keep and grow, told from a third-person view with simple words.

### Overview of MVC in CLSU-ERDT
- **Model**: The Model is the data part, dealing with database tasks, main rules, and data checks. Models include things like `User`, `ScholarProfile`, `FundRequest`, and `Manuscript` in 'app/Models/'.
- **View**: The View is the show part, making the user screen with Blade pages. Views show data to users and get user input via forms and dashboards in 'resources/views/'.
- **Controller**: The Controller is the middle link between Model and View, taking user asks, handling input, using model rules, and sending back right screens or API answers. Controllers run task flows like login, sends, and checks in 'app/Http/Controllers/'.

### Login MVC Flow
1. **Controller**: Login starts with controllers handling asks in 'app/Http/Controllers/Auth/'. For a Scholar login, the controller gets input from `/scholar-login`, checks details with Laravel's login tool, and forces role rules.
2. **Model**: The `User` model talks to the database to check details, see account status (`is_active`), and get role info in 'app/Models/User.php'. Safety rules like try limits are set by middle tools in 'app/Http/Middleware/'.
3. **View**: If login works, the controller sends to the right dashboard screen (`scholar.dashboard` or `admin.dashboard`), made by Blade pages in 'resources/views/'. If login fails or password change needed, an error or ask screen shows.
4. **Flow Short**: A user's input comes, the Controller checks and works it, the Model looks at the database, the Controller picks the result, and the View shows the outcome (dashboard or error).

### Scholar Profile MVC Flow
1. **Controller**: A controller takes asks for profile make or change, getting input from Scholars via dashboard forms in 'app/Http/Controllers/'. It checks input and calls model ways.
2. **Model**: The `ScholarProfile` model handles data save, checks (like needed fields), and hides private info in 'app/Models/ScholarProfile.php'. It updates the database with status shifts (like 'New' to 'Pending') and starts notice events.
3. **View**: Blade pages make the profile form for input and show profile status or Admin comments to Scholars in 'resources/views/scholar/'. Admin screens show waiting profiles for check.
4. **Flow Short**: A Scholar sends profile data, the Controller works the input, the Model saves to database and starts events, and the View updates with status or comments.

### Fund Request MVC Flow
1. **Controller**: Controllers handle fund request sends, taking form data and file uploads from Scholars in 'app/Http/Controllers/'. They check input, update status, and manage Admin choices (okay/no).
2. **Model**: The `FundRequest` model checks data (like amount caps), handles file save with safety scans, and updates database records through status steps (Draft to Disbursed) in 'app/Models/FundRequest.php'. It also starts notice events.
3. **View**: Views make forms for request start, show request status on Scholar dashboards, and list waiting requests on Admin dashboards for check in 'resources/views/'.
4. **Flow Short**: A Scholar sends a request, the Controller checks and works it, the Model updates database and files, and the View refreshes with status or Admin choice.

### Manuscript Send MVC Flow
1. **Controller**: Controllers manage manuscript sends, handling form data and files, locking sends from edits once sent, and running Admin review choices in 'app/Http/Controllers/'.
2. **Model**: The `Manuscript` model handles data save, status shifts (Draft to Published), and ties comments or fixes in the database in 'app/Models/Manuscript.php'. It keeps data right and starts notices.
3. **View**: Blade pages give forms for manuscript uploads, show send status to Scholars, and offer review screens with comment choices to Admins in 'resources/views/'.
4. **Flow Short**: A Scholar uploads a manuscript, the Controller works the send, the Model updates database and status, and the View shows new status or comments.

### Notice System MVC Flow
1. **Controller**: Controllers might start notices by handling actions that make events (like status updates). Some notices might be run straight via API calls in 'app/Http/Controllers/'.
2. **Model**: Notice models (`CustomNotification`) save notice data in the database in 'app/Models/CustomNotification.php'. Event watchers in the model part start lined-up jobs for sending based on user actions.
3. **View**: Views make notice centers on dashboards for app alerts and might show okay for sent email notices in 'resources/views/'.
4. **Flow Short**: An action starts an event, the Model makes a notice record and lines up sending, the Controller (if API) or background job does sending, and the View shows app alerts.

### Admin and Super Admin Work MVC Flow
1. **Controller**: Admin controllers handle Scholar accounts, review sends, and update statuses. Super Admin controllers manage system rules and user role sets, working input from Admin screens in 'app/Http/Controllers/'.
2. **Model**: Models like `User`, `SiteSetting`, and log models handle data tasks, force safety rules (like data-level safety), and track all actions in logs in 'app/Models/'.
3. **View**: Admin views make dashboards with data, forms for choices, and setting screens for Super Admins to set the system in 'resources/views/admin/'.
4. **Flow Short**: An Admin does an action, the Controller works the ask, the Model updates database with safety checks, and the View shows the new dashboard or settings.

### Key MVC Links
- **Job Split**: Controllers run the flow, making sure Models do data rules and Views do show, stopping direct Model-View talk.
- **Middle Tools**: Middle tools are used at the controller level for login, role checks, and try limits, keeping MVC paths safe in 'app/Http/Middleware/'.
- **Events and Watchers**: Models often start events (like status change) that watchers handle, which might call other controllers or line up jobs, growing the MVC flow in 'app/Listeners/'.
- **API Mix**: For API tasks, controllers send JSON answers instead of views, but the MVC setup stays with Models doing data and Controllers running rules in 'routes/api.php'.

## Conclusion
This simulation shows the main user paths for all types in the CLSU-ERDT system, explaining login, profile care, fund requests, manuscript sends, admin tasks, and notice flows. Each path focuses on safety, clear steps, and good work flow as per the system's plan.
