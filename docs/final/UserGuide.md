## Insight: User Guide to GitHub Markdown

# Staff 

## Introduction 

The staff page provides an overview of all the staff members associated with the department. This section includes basic information such as names, emails, and performance data.

## Details

On the staff page you can: 
* Search up staff members
* Sort by the headings
* Filter by area
* Add target Hours
* Edit or Remove Target Hours
* View Instructor Reports 

### Target Hours

#### Add Target Hours

To add target hours, you first need to select at least one staff member. Then navigate to the action dropdown where you can click the 'Add Target Hours' link. This will bring up a popup that will allow you to input the target hours. Note: Target hours should be no greater then 2000. 

#### Edit Target Hours

To edit target hours, you can click on the 'Edit mode' link from the drop down. This will make the target hours column editable. When done editing, click save. Note: Target hours should be no greater then 2000. 

#### Remove Target Hours

To remove target hours, you can click on the 'Edit mode' link from the drop down. Then leave the input fields blank for the staff members you want to remove the target hours from and click save. 

#### Select Year

You can select the year you would like to view the instructor performance for by using the dropdown on the 'Target Hours' heading. Only years which have performance data will be shown.

### Instructor Performance

#### Completed Hours

You can view the completed hours for the selected month for each instructor. You can select which month you would like to view.

### Reports

#### Instructor Report

You can view the report of each instructor by clicking on the report icon in the report column. The report has information on the instructor's courses, services roles and/or extra hours for the selected year. You can select a different year from the dropdown as long as a report for that year exists. 

### User Accounts 

#### Add New Account 

To add a new user account, you will need to click on the 'Add' button. A popup will be shown that will allow you to enter the user's full name, email and password. You may also select the roles for the user and then submit the form.

#### Edit Multiple Accounts

To edit multiple accounts, you will need to click on the 'Edit' button. Then the roles and status field will become editable where you can toggle the enabled status and check or remove checks from the user's roles. When done, click 'Save'.

#### Delete Multiple Accounts

To delete multiple accounts, first select all the user accounts you would like to delete. Then click on the delete button which will bring up a popup asking you to confirm. Once confirmed, the selected accounts will be deleted. 

#### Edit Individual Account

To edit an individual account, you can click on the edit icon on the user account you would like to edit. Then the roles and status field for that user will become editable. Once done, you can click on the save icon to save. 

#### Delete Individual Account

To delete an individual account, you can click on the delete icon on the user account you would like to delete. Then a popup will appear, asking you to confirm. Once confirmed, the user account will be deleted. 

#### Send Reset Link 

To send a reset link to a user account, click on the send reset link icon... 

# Service Roles 

## Introduction

Service Roles represent specific job duties and responsibilities assigned to instructors within a department. They provide a structured way to manage and track the various roles instructors fulfill beyond traditional teaching.

## Viewing Service Roles

You can view service roles in two modes:

- **Table View:** Displays service roles in a tabular format, with columns for ID, Role, Area, Year, Description, Room, and Instructors.
- **Card View:** Presents each service role as a card with key information.

You can switch between these views using the 'View Mode' button in the toolbar.

## Adding Service Roles

You can add service roles in two ways:

- **Manually:** Click the 'New/Import' button, then 'Add Row' to create a new service role record. Fill in the form fields for the role's details and click 'Save'.
- **Importing:** Click the 'New/Import' button, then 'Import' to upload a file containing multiple service role records. The supported file formats are CSV, Excel, and JSON. Once uploaded, you can review and edit the imported data before saving it.

## Managing Service Roles

The platform offers several tools for managing Service Roles:

- **Editing Service Roles:** Click the 'Edit' button on a service role to modify its details, such as name, description, area, monthly hours, and assigned instructors.
- **Archiving Service Roles:** Click the 'Archive' button to mark a service role as inactive. This removes it from the main list but retains its information.
- **Deleting Service Roles:**  **[Admin Only]**  Click the 'Delete' button to permanently remove a service role and its associated data. 

## Assigning Instructors

To assign an instructor to a Service Role:

1. Navigate to the 'Manage Service Role' page.  For example: <a class="text-[#ea4040]" href="/svcroles/manage/">Manage Service Role ID 1</a>
2. Click the 'Assign Instructor' button.
3. Select an instructor from the dropdown list.
4. Choose the appropriate role from the 'Role' dropdown.
5. Click 'Save'.

## Tracking Extra Hours 

You can log additional hours worked by instructors beyond their assigned monthly hours. These extra hours can be associated with a specific instructor, area, or the entire Service Role. To add extra hours:

1. Go to the 'Manage Service Role' page. <br>
2. Click the 'Add Extra Hours' button. 
3. Fill in the required details, such as date, hours, description, and any relevant associations (instructor or area). 
4. Click 'Save'.

# Leaderboard

## Introduction

The Leaderboard ranks instructors within a department based on their scores, from highest to lowest. The instructor with the highest score is placed first. 

## Filtering

The Leaderboard can be filtered by department area to display specific ranks for users within a particular area of the department. 

### Score Calculation

Instructor scores are calculated using a custom function based on Insight performance metrics. The calculation involves the following steps: 

#### Step 1:

Enrollment & Dropped Scalers: Calculated by dividing the dropped average percentage by 100 and dividing the dropped average percentage by 100 and adding 1, both of these will be used to scale the course section value (see step 2). [Formula: enrolled_scaler = (enrolled_average / 100) + 1], [Formula: dropped_scaler = dropped_average / 100]. 

#### Step 2: 

Weighted Course Sections: Each course section is assigned a weight based on its duration and the approximate hours an instructor spends teaching the course each term. Single-term courses are assigned a weight of 215, and double-term courses are assigned a weight of 530. 

#### Step 3: 

Course Component: The enrolled and dropped scalers are multiplied by the total number of weighted course sections and the SEI Average of the instructor. [Formula: course_performance = enrolled_scaler * dropped_scaler * (215 * num_course + 530 * num_double_course) * sei_average]. 

#### Step 4: 

Service Hours: Add the total completed service hours for the year and divide by the average number of hours in a year (8,760). [Formula: (service_hours + course_performance) / 8760]. 

#### Step 5:

Final Score: Multiply the result by 1,000 and round to an integer. The score is updated each time performance data is added to the database. [Formula: final_score = (service_hours + course_performance) / 8760 * 1000].

### Leaderboard Badges

#### Golden Champ

1st Place: You’re at the top of your game! This badge is awarded to the highest-ranking individual. 

#### Silver Star

2nd Place: Almost there, keep pushing! This badge is awarded to the second-highest ranking individual. 

#### Bronze Boss

3rd Place: Great job, you’re on the podium! This badge is awarded to the third-highest ranking individual. 

#### Top 5% Wonder

Top 5%: You’re one of the elite few! This badge is awarded to those who rank in the top 5%. 

#### Elite Top 10%

Top 10%: You’re in the top tier! This badge is awarded to those who rank in the top 10%. 

#### Top 25% Dynamo

Top 25%: Strong performance! This badge is awarded to those who rank in the top 25%. 

#### Top 50% Hero

Top 50%: You’re in the upper half! This badge is awarded to those who rank in the top 50%.

#### Top 75% Achiever 

Top 75%: You’re making progress! This badge is awarded to those who rank in the top 75%. 

#### Keep Climbing

Keep Climbing! Greatness awaits! This badge is awarded to those who are working towards higher rankings. 

# Import Data 

## Introduction 

The import pages enable users to manually enter data or upload CSV files for inserting course sections (Workday) and SEI data into the system. 

## Attempting to Update or Overwrite an Existing Course Section

If a user attempts to create a course section that already exists in the system, a warning will be displayed to prevent accidental overwriting. The warning modal will list all existing courses and will appear each time a save attempt is made, until the "I Understand" button is clicked. If "Cancel" is chosen, the modal will continue to appear for subsequent save attempts. 

## Accounting for a Student That Joined a Course After the Last Day to Enroll

To ensure that the dropped value is accurately calculated when this occurs, add the number of students to account for to both the Enrolled Start and Enrolled End values. This adjustment will ensure that the dropped value, which is calculated by the difference in enrolled values, is not skewed. 

### Manual Input - Functionality / Navigation

#### Add Course Sections (Workday)

Clicking on "Add Row" will create a new entry to fill out. To add multiple rows at once, enter the number of rows you want in the input next to the buttons below the table and click "Add Many Rows." To delete a row, click the trash icon on the row you wish to remove. Once all the desired fields are filled, click "Save".

#### Add SEI Data

Clicking on "Add Row" will create a new entry to fill out. To add multiple rows at once, enter the number of rows you want in the input next to the buttons below the table and click "Add Many Rows." To delete a row, click the trash icon on the row you wish to remove. Once all the desired fields are filled, click "Save".

### File Upload - Functionality / Navigation 

#### Upload Workday File

After uploading a file, a populated table will appear with the contents found in the file. This is to ensure that all the data will be correctly inserted. If the table appears empty or is missing information, refer to the sample CSV and ensure all headings are formatted correctly. At this stage, all the fields may be edited in case the data was pulled incorrectly. To delete a row, click the trash icon on the row you wish to remove. Once all the desired fields are filled, click "Save".

#### Upload SEI Data

After uploading a file, a populated table will appear with the contents found in the file. This is to ensure that all the data will be correctly inserted. If the table appears empty or is missing information, refer to the sample CSV and ensure all headings are formatted correctly. At this stage, all the fields may be edited in case the data was pulled incorrectly. To delete a row, click the trash icon on the row you wish to remove. Once all the desired fields are filled, click "Save".

### Validation / Input Field Requirements

#### Course Sections / Workday Data 

The following applies to both the manual and file upload tables. The headers are listed from left to right. 
* **Area:** Select one of the areas from the dropdown.
* **Number:** Three numbers representing the course number. 
* **Section:** Three numbers representing the course section.
* **Session:** Select one of the options from the dropdown. 
* **Term:** Select one of the options from the dropdown. 
* **Year:** 4 numbers representing the year. 
* **Room:** Three capital letters representing the abbreviated name of the building followed by three numbers representing the room number. 
* **Time:** Both fields, representing the start and end time, must be a number in military time notation.
* **Enrolled (Start):** A number from 1-999. This must be lower than the capacity. 
* **Enrolled (End):** A number from 0-999. This must be lower than the capacity. 
* **Capacity:** A number from 1-999. This must be higher than Enrolled (Start) and Enrolled (End). 

#### SEI Data 

The following applies to both the manual and file upload tables. The headers are listed from left to right. 
* **Course Section:** Select an existing course from the dropdown. The only course sections listed are those that do not have associated SEI data. To edit these values, navigate to the Course Section Page. 
* **Q1(IM)-Q6(IM):** A number from 1-5 representing the interpolated median for the corresponding question. 

# Dashboard

## Introduction 

The Performance Dashboard is a comprehensive hub for viewing all performance data within Insight. It provides users with a valuable understanding of various metrics that track their performance.

## Reports

Reports summarizing overall performance data for a specific instructor or department can be generated using the 'View Report' button in the top-right corner of the Dashboard.

### Metrics

#### Course Metrics

Each course section in the Insight database has three associated metrics: Students Enrolled, Students Dropped, and the SEI Average.

##### SEI Average
The SEI Average (Student Experience Survey Average) is the average interpolated mean of student responses to the SEI Survey questions for all courses taught by an instructor, area, or department. These questions are ranked on a 5-point scale, with 5 being the highest score and 1 being the lowest.

##### Enrolled Average
The Enrolled Average is the percentage of students enrolled at the end of the semester. It is calculated by dividing the number of enrolled students by the course capacity and multiplying by 100. [Ex. Formula: Enrolled Average (%) = (Enrolled Students / Course Capacity) * 100]

##### Dropped Average 
The Dropped Average is the percentage of students who have dropped a course (with a W standing). It is calculated by subtracting the number of students enrolled at the end of the semester from the number of students enrolled one day after the last day to withdraw without a W standing, dividing this value by the course capacity, and then multiplying by 100. [Ex. Formula: Dropped Average (%) = ((Initial Enrollment - End Enrollment) / Course Capacity) * 100] 

#### Service Hours 

Instructors within a department complete service hours outside the classroom, tracked as Service Roles and Extra Hours. 

##### Service Roles

Service Roles are duties assigned to instructors within a department, with a set number of work hours each month. These roles are displayed as donut charts on the dashboard. 

##### Extra Hours

Extra hours are additional service hours assigned to instructors, including meetings, conferences, and other events. These hours are also displayed as donut charts on the dashboard. 

### Instructor Dashboard

#### Charts

The Instructor Dashboard presents three charts for users without a set hours target and four charts for users with a set hours target. 

##### Donut Charts

Donut charts display service roles and extra hours assigned to the instructor. Each colored segment represents a specific role or extra hours. Hover over the chart for specific hour values. 

##### Line Chart

The line chart tracks the total service hours assigned to the instructor each month over a year. Instructors with a target see a secondary line representing their target hours. Hover over the chart for specific hour values.

##### Progress Bar (Target Only)

The progress bar displays if a target is assigned for the month. The blue portion shows completed hours, and the grey portion shows hours needed to reach the target. Hover over the bar for specific hour values.

#### Achievements

The Achievements section displays the instructor's current performance status, including score, rank, and badge. Refer to the Leaderboard Help page for more details.

#### Filtering

Use the 'Select Year' dropdown menu to filter data by year. If only one year is available, performance data for other years has yet to be added.

### Department Dashboard 

#### Charts 

The Department Dashboard presents four charts for the department as a whole and three charts if an area is specified. 

##### Donut Charts 

Donut charts display service roles and extra hours within the department. Each segment represents total service roles or extra hours in an area. If an area filter is applied, a top-five list of courses is shown. Hover over the charts for specific values. 

##### Line Chart 

The line chart tracks total service hours within the department and its areas each month over a year. If an area filter is applied, only lines for the selected area and department are shown. Hover over the chart for specific hour values.

#### Leaderboard

The Department Dashboard displays a preview of the Department Leaderboard, showing the top-five performers and their scores. If an area filter is applied, the leaderboard shows the top-five performers in that area. Refer to the Leaderboard Help page for more details.

#### Filtering 

Two filtering options are available: by department area and by year. Use the dropdown menus to filter the data. If only one option appears, additional data must be added to the database. 

#### Switching Roles 

Users with multiple roles (e.g., Department Head and Instructor) can switch between views using the 'Instructor View' or 'Department View' button in the top-right corner of the Dashboard.

# Course Details

## Introduction

This section provides an overview of the course details page. Here, you can find comprehensive information about the courses you are managing, including their structure, assigned teaching assistants, and evaluation data.

## Navigating the Course Details 

The course details page is divided into multiple sections:

1. **Course Information**: Displays basic details like course name, department, and capacity.
2. **Instructor Details**: Lists the instructor's information, including their name and contact details.
3. **Enrolled Students**: Shows the number of students enrolled in the course.
4. **Teaching Assistants**: Lists the TAs assigned to the course and their roles.

Use the tabs at the top to switch between these sections for a more detailed view.

## Editing Course Information

To edit course information:
1. Click the 'Edit' button at the top.
2. Fields such as course name, department, capacity, and others will become editable.
3. Make the necessary changes and click 'Save' to update the course details.

Remember to verify the information before saving to ensure accuracy.

## Assigning TAs

To assign teaching assistants to a course:
1. Click the 'Assign TA' button.
2. A modal will appear where you can select the TA, instructor, and course.
3. Confirm the assignment to save the changes.

The assigned TAs will now appear in the course details under the Teaching Assistants section.

## SEI Data 

The SEI (Student Evaluation of Instruction) data section provides insights into student feedback for the course. 

To edit SEI data:
1. Click the 'Edit SEI' button.
2. Update the relevant fields with the new data.
3. Click 'Save' to apply the changes.

This data helps in evaluating the course performance and identifying areas for improvement.

# Audit Logs

## Introduction

Audit logs provide a detailed record of actions performed within the Insight platform. They track user activities, data modifications, and system events, offering valuable insights into system usage and data integrity.

## Viewing Audit Logs

The Audit Logs page displays a comprehensive list of audit records. You can view details such as:

- **ID:** Unique identifier for each audit log entry. 
- **User:** The user who performed the action.
- **Action:** The type of action performed (e.g., create, update, delete). 
- **Description:** A brief summary of the action.
- **Schema:** The database table or model affected.
- **Operation:** The specific database operation (e.g., insert, update, delete).
- **Old Value:**  The previous value of the data (if applicable).
- **New Value:**  The current value of the data (if applicable). 
- **Created:**  Timestamp indicating when the audit log entry was created. 
- **Updated:** Timestamp indicating when the audit log entry was last updated.

## Filtering and Searching

To refine your view of the audit logs, you can use the following tools:

- **Search:** Enter keywords in the search bar to filter audit logs based on user names, actions, descriptions, or other relevant information.
- **Filters:**  Click the 'Filters' button to access a panel where you can filter audit logs by specific users, actions, schemas, or operation types. Select the desired filters and click 'Apply' to update the list.

## Sorting

You can sort the audit logs by any column. Click the column header to sort in descending order. Click again to sort in ascending order. 

## Data Preview 

For audit log entries involving data modifications, you can preview the old and new values by clicking the 'File Present' icon. This will open a modal window displaying the data in a readable format. 

# Service Requests & Approvals 

## Introduction 

The Service Requests & Approvals system manages various requests and approvals within Insight, ensuring proper authorization and tracking for critical actions. 

## Approval Types

Insight handles several types of approvals:

- **Service Role Approvals:** Requests for new service roles or changes to existing roles.
- **Extra Hour Approvals:**  Requests for additional hours worked by instructors beyond their assigned monthly allocation.
- **Registration Approvals:** New user registration requests require approval before accounts are activated. 
- **Service Request Approvals:** Generic requests for services or support can be created and require approval.

## Approval Statuses

Approvals go through various statuses during their lifecycle:

- **Pending:** The initial state of a request awaiting approval. 
- **Intermediate:** The request is pending and requires approval from multiple approvers.
- **Approved:** The request has been authorized. 
- **Rejected:**  The request has been denied.
- **Cancelled:** The request has been withdrawn or revoked. 

## Managing Requests

You can manage service requests from the 'Service Requests' page. The page offers the following features:

- **View Requests:**  Browse a list of all requests, filtered by type or status.
- **Search:**  Find specific requests using keywords in the search bar.
- **Filter:** Narrow down the list of requests by using various filters (e.g., by user, status, or date).
- **Approve/Reject:** Take action on pending requests by approving or rejecting them, providing comments if necessary. 
- **Cancel:** Withdraw or cancel requests that are no longer needed. 

## Approval History

Every approval request maintains a detailed history, recording:

- **User:**  The user who interacted with the request.
- **Status:** The status change made to the request. 
- **Remarks:** Any comments or notes added during the status change. 
- **Timestamp:** The date and time of the status change.


