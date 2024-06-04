# UBC Insight - System Design

**Team Number:** 4

**Team Members:** Angelina Dubrule, Catherine Magoke, Sumer Mann, Kate Naychuk, Clement Abel-Nwachukwu

## Introduction

Insight is a Department Management System web application that will enhance the efficiency and transparency in the CMPS department. The department head will gain a clear overview of all instructors' activities, enabling the identification of both high-performing and underperforming instructors through intuitive data visualizations and insightful reports. Meanwhile, instructors benefit from accessing their own performance metrics, allowing them to gauge their progress against department-wide targets. The system will incentivize instructors to excel through analytical visualizations, ranking mechanisms, and proactive notifications. The department staff will manage the input of service roles, teaching assignments and TA assignments data and IT administrators will be responsible for account and system management. Overall, Insight fills a crucial gap in the department's infrastructure, fostering a culture of continuous improvement, ultimately increasing the quality of student experiences.

## System Architecture Design

![Image of architecture diagram](./images/SystemArchitecture.png)

For our system architecture, we have adopted the Model-View-Controller (MVC) pattern due to its modularity, scalability, and maintainability. Since this is a web-based application, the user will interact with the system via a browser. The Model represents the data layer where we use the built-in Laravel ORM to communicate with our MySQL database through a customer database adapter. This adapter will allow a seamless transition, if necessary, to another database. The View corresponds to the React components responsible for the user interface and presentation logic. React's component-based architecture simplifies UI development and enables reusability. The Controller encompasses the server-side logic implemented in Laravel, handling tasks such as HTTP requests and routing. Additionally, both the front-end and back-end will communicate with Tableau to display various data visualizations. Finally, all these separate components of the system will be containerized using docker to simplify the deployment process.

## Use Case Models

![Image of use case diagram](./images/usecase.png)

| ID | 1 |
|---|---|
| Name: | Register Account |
| Actor(s): | Instructor, Department Head, Department Staff, and Administrator |
| Flow of Events: | Navigates to the website and clicks on the sign-up button. The user is redirected to sign-up form  Users enter their information and choose their role as instructor, department head, department staff, or administrator User chooses a password and clicks ‘Register’ Upon creation of the account, redirects to the sign-in page  |
| Pre-Conditions: | Users must not have an existing account  |
| Post-Conditions: | Account is created for the user to access  User information is stored in the database  |
| Description: | A registration process for the user to access the website  |

| ID | 2.a |
|---|---|
| Name: | Successful Login |
| Actor(s): | Instructor, Department Head, Department Staff, and Administrator |
| Flow of Events: | Navigates to the website and clicks on the sign-in button. Enters the correct credentials  Redirects to the dashboard after successful login  |
| Pre-Conditions: | The user should be logged out The credentials entered by the user are correct  The credentials stored in the database are correct to the respective user  |
| Post-Conditions: | The user can log in |
| Description: | The login process for all users to the website  |

| ID | 2.b |
|---|---|
| Name: | Failed Login |
| Actor(s): | Instructor, Department Head, Department Staff, and Administrator |
| Flow of Events: | Navigates to the website and clicks on the sign-in button Enters the correct credentials  Toast requests user to retry form submission |
| Pre-Conditions: | The user should be logged out  The credentials entered by the user are incorrect  The credentials stored in the database are incorrect to the respective user  |
| Post-Conditions: | The user must reattempt log in |
| Description: | The login process for all users to the website  |

| ID | 3 |
|---|---|
| Name: | Access Account Settings |
| Actor(s): | Instructor, Department Head, Department Staff, and Administrator |
| Flow of Events: | Navigates to the website and clicks on the sign-in button Enters the correct credentials  Redirects to the dashboard after a successful login Clicks “Settings” icon Redirects to “Settings” page  |
| Pre-Conditions: | The user should have an existing account.  The credentials entered by the user are correct. |
| Post-Conditions: | The user can see their settings page,  |
| Description: | The user can access the account settings. |

| ID: | 4 |
|---|---|
| Name: | View Visualizations  |
| Actor(s): | Instructor, Department Head |
| Flow of Events: | Navigates to the website and clicks on the sign-in button Enters correct credentials Redirects to the dashboard after successful login The visualization will be displayed on the main dashboard |
| Pre-Conditions: | The credentials should be correct Data must be uploaded and synched for visualizations to appear |
| Post-Conditions: | The user’s login attempt is stored in the database  Users are able to see their current performance in the form of visualization and graphs  |
| Description: | Viewing the visualizations of progress and work in a given time period  |

| ID: | 5 |
|---|---|
| Name: | Track Performance |
| Actor(s): | Instructor, Department Head |
| Flow of Events: | Navigates to the website and clicks on the sign-in button Enters the correct credentials  Redirects to the dashboard after a successful login Scrolls down the dashboard The user is able to view performance metrics, service roles, and scheduled hours |
| Pre-Conditions: | The login credentials should be correct Data must be uploaded and synched for performance to appear |
| Post-Conditions: | The user’s login attempt is stored in the database Users can track their performance and set up future goals accordingly  |
| Description: | Users will be able to keep track of their service roles, scheduled hours, and other metrics |

| ID: | 6 |
|---|---|
| Name: | View Assigned TAs |
| Actor(s): | Instructor |
| Flow of Events: | Navigates to the website and clicks on the sign-in button Enters the correct credentials  Redirects to the dashboard after a successful login Scrolls down the dashboard The user can view their assigned TAs and hours associated with them |
| Pre-Conditions: | The login credentials should be correct The instructor must be assigned at least one TA  |
| Post-Conditions: | The user’s login attempt is stored in the database User is able to view their assigned TAs and their associated hours  |
| Description: | The user is able to view assigned TAs and the hours associated with them.  |

| ID: | 7.a |
|---|---|
| Name: | Assign Service Role |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to Assign Service Role page User chooses a service role and assigns it to the instructor(s) Submits request |
| Pre-Conditions: | Must be logged in |
| Post-Conditions: | Service role is assigned to the instructor(s) |
| Description: | The user will be able to assign service roles to one or more instructors  |

| ID: | 7.b |
|---|---|
| Name: | Assign Extra Hours  |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to assign service role page  Selects “Add Extra Hours” button Inputs title, description,  and hours Selects instructors who attended Submits request |
| Pre-Conditions: | Must be logged in |
| Post-Conditions: | Meeting hours will be added for the instructors that attended the meeting |
| Description: | The Department Head or Department Staff will be able to add hours for instructor who attended a meeting |

| ID | 8.a |
|---|---|
| Name: | Create Service Role/Extra Hours |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to Service Roles page Selects Create New Service Role or Add Extra Hours Fills out a form with new service role or extra hours information (name, hours, description, etc.) and selects instructors they apply to Submits request|
| Pre-Conditions: | Must be logged in |
| Post-Conditions: | New service role(s) or extra hours is added to the database |
| Description: | Creates new service role or extra hours |

| ID | 8.b |
|---|---|
| Name: | Edit Service Role/Extra Hours |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to Service Roles page Selects “Manage Service Roles” Searches for and selects the Service Roles or Extra Hours they want to edit Fills out a form to edit the information for the selected service role (name, hours, description, etc.) Submits request |
| Pre-Conditions: | Must be logged in Database must contain existing service role/extra hours data |
| Post-Conditions: | Service role information is modified in the database |
| Description: | Edits information for an existing service role |

| ID | 8.c. |
|---|---|
| Name: | Remove Service Role/Extra Hours |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to Service Role page  Selects “Manage Service Roles” Searches for and selects “Delete” on the Service Roles or Extra Hours they want to remove User is prompted with an “Are you sure you want to delete this Service Role/Extra Hours?”, and must enter and submit password to select “Yes” If password is correct, submits request |
| Pre-Conditions: | Must be logged in Database must contain existing service role/extra hours data |
| Post-Conditions: | Service role/extra hours information is removed from the database |
| Description: | Deletes existing service role or extra hours |

| ID | 9.a |
|---|---|
| Name: | Add Performance Data |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to Performance Data page Selects “Add Performance Data”, and selects performance data type (SEI Survey, course section, etc) Fills out a form or submits a CSV file containing new performance data information (including the instructor it applies to) Submits request|
| Pre-Conditions: | Must be logged in |
| Post-Conditions: | Performance data is added to the database |
| Description: | Adds performance data to an existing instructor |

| ID | 9.b |
|---|---|
| Name: | Remove Performance Data |
| Actor(s): | Department Head and Department Staff |
| Flow of Events: | Navigates to the “Performance Data” page Searches for and selects “Delete” on performance data they want to remove   User is prompted with an “Are you sure you want to delete this Service Role/Extra Hours?”, and must enter and submit password to select “Yes” If password is correct, submits request |
| Pre-Conditions: | Must be logged in Database must contain existing performance data |
| Post-Conditions: | Performance data is removed from the database |
| Description: | Deletes performance data for an existing instructor |

| ID: | 10 |
|---|---|
| Name: | View Instructor’s Visualizations |
| Actor(s): | Department Head |
| Flow of Events: | Navigates to “Performance by Instructor” page Searches for and selects instructor name Redirects to individual performance dashboard |
| Pre-Conditions: | Must be logged in Data must be uploaded and synched for visualizations to appear. |
| Post-Conditions: | Dashboard will be displayed with performance visualizations for the instructors |
| Description: | The Department Head will be able to see a dashboard with visualizations of an individual instructor’s performance |

| ID: | 11 |
|---|---|
| Name: | Export Instructor Report |
| Actor(s): | Department Head |
| Flow of Events: | Navigates to “Performance by Instructor” page Searches for and selects instructor name Redirects to individual performance dashboard Click download button to download the report |
| Pre-Conditions: | Must be logged in Performance data must exist |
| Post-Conditions: | Report will be downloaded to the device |
| Description: | The Department Head will be able to download the individual report for an instructor |

| ID: | 12 |
|---|---|
| Name: | Export Department Report |
| Actor(s): | Department Head |
| Flow of Events: | Navigates to the website and clicks on the sign-in button Enters the correct credentials Redirects to the dashboard after a successful login to view the department performance dashboard Click download button to download the report |
| Pre-Conditions: | Performance data must exist |
| Post-Conditions: | Report will be downloaded to the device |
| Description: | The Department Head will be able to download the report to view the performance overview of all instructors |

| ID: | 13.a |
|---|---|
| Name: | Create User Account |
| Actor(s): | Administrator |
| Flow of Events: | Navigates to “User Accounts” page Selects “Create New User” Selects user type (admin, dept. head, instructor) Fills out a form with new user information (email ID, password etc.) Submits request |
| Pre-Conditions: | Must be logged in |
| Post-Conditions: | New user account(s) is added to database |
| Description: | Creates a new user account |

| ID: | 13.b |
|---|---|
| Name: | Edit User Account Information |
| Actor(s): | Administrator |
| Flow of Events: | Navigates to “User Accounts” page Searches for and selects the user account they want to edit User fills out a form to edit account information of the selected user account (email ID, password, etc.) Submits request|
| Pre-Conditions: | Must be logged in Database must contain existing user account |
| Post-Conditions: | User account information is modified in the database |
| Description: | Edits account information for an existing user account |

| ID | 13.c |
|---|---|
| Name: | Remove user account |
| Actor(s): | Administrator |
| Flow of Events: | Navigates to the “User Accounts” page Searches for and selects “Delete” on the user account(s) they want to remove User is prompted with an “Are you sure you want to delete this Service Role/Extra Hours?”, and must enter and submit password to select “Yes” If password is correct, submits request |
| Pre-Conditions: | Must be logged in Database must contain existing user account |
| Post-Conditions: | User account(s) information is removed from the database |
| Description: | Deletes account of an existing user |

## Database Design 

![Image of ER diagram](./images/ERdiagram.png)

Our system consists of five main entities: Users, Course Sections, Assignments, SEI Survey Data, and Teaching Assistants. The User entity includes attributes for email (unique identifier), first name, last name, and password, with four sub-entities (Instructor, Department Head, Department Staff, and Administrator) to represent different user groups. Department Head and Department Staff include an additional department attribute. The Administrator is defined separately for role-based access control but has no unique attributes or relationships beyond user account data.

The Assignment entity includes attributes for a unique ID, name, description, hours, and subject area, and is divided into service roles and extra hours. The Department Head has a one-to-many relationship with Assignments, as they can assign multiple assignments. Instructors have a many-to-many relationship with Assignments, as they can be assigned multiple service roles or extra hours, and each role or hour can be assigned to multiple instructors.

Instructors have a one-to-many relationship with the Course Section entity, requiring each instructor to have at least one course section. Course Sections include a unique identifier, performance metrics (enrollment, dropouts, capacity), meeting times, and course duration to calculate total teaching hours. Each Course Section has a one-to-one relationship with the SEI Survey Data entity, which includes attributes for the Interpolated Median of six survey questions.

Finally, Course Sections have a many-to-many relationship with Teaching Assistants, where multiple teaching assistants can work on multiple courses, but each assistant must be assigned to at least one course. This diagram accurately represents the data managed within our system.

## Data Flow Diagram (Level 0/Level 1)

![Image of Level 0 and Level 1 DFD diagram](./images/DFDdiagram.png)

Our level 0 data flow diagram displays the four user groups (instructors, department heads, administrators, and department staff) which interact with the system. Both the instructors and department heads can receive information, however the department staff and administrators can only input or change data via an interface. The department heads and staff can add performance data while the administrators can only access account management functionality.

Like the level 0, our level 1 data flow diagram displays how the four user groups interact with the system. This diagram however, gives a deeper look into the actions done by the users and the process in which these requests are handled by the system. Each user needs to interact with the authentication page to verify who they are and the type of user. Certain users (instructors and department heads) can view data from the dashboard and certain users (department heads and department staff) can insert data that will be visible on the dashboard. In this diagram, these interactions are grouped together within Data Form as that will be the main way of inserting info. The administrator can perform various account interactions in Manage Accounts as seen on the diagram. These interaction requests are sent to the server to verify and if necessary, check with the database. If all is successful, the data gets updated in the database and is sent back to the user.

## User Interface (UI) Design

### UI Mockups

**User Authentication: Registration and Login**

![Image of Register Page](./images/register.png)
![Image of Login Page](./images/login.png)

**User Authentication: Forgot Password**

![Image of Forgot Password Page](./images/forgotpassword.png)

**User Settings**

Settings interface for the basic general user (Instructors). All users will see this but department heads and above will see additional data

![Image of Settings Page](./images/settings.png)

**Admin Dashboard**

On successful login, each user will be redirected to their dashboard which looks like this. For instructors, they will see just a preview of their performance data. Department heads and admins will see additional data based on their roles.

![Image of Dashboard Page](./images/dashboard.png)

**Admins Instructor list page**

Edit Mode

![Image of Edit mode Page](./images/editmode.png)

View Mode

![Image of view mode Page](./images/viewmode.png)

### Navigation Flow Diagrams

![Image of navigation diagram](./images/navprof.png)
![Image of navigation diagram](./images/navhead.png)
![Image of navigation diagram](./images/navstaff.png)
![Image of navigation diagram](./images/navadmin.png)
