# Angelina's Personal Log 

## Friday (7/12 - 7/16)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log712-716.png)

### Current Tasks 
  * #1: Create Dashboard Tests
  * #2: Leaderboard Design
  * #3: Add Pie Charts to Department Dashboard
  * #4: Leaderboard Front End
  * #5: Leaderboard Back End
  * #6: Leaderboard Tests
  * #7: Create and Implement Value for Score in the Database
  * #8: Make Leaderboard Department Specific
  * #9: Add leaderboard preview to Dashboard

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Dashboard Tests           | Completed   |
| Leaderboard Design        | Completed   |
| Add Pie Charts            | Completed   |
| Leaderboard Front End     | Completed   |
| Leaderboard back End      | Completed   |
| Leaderboard Tests         | Completed   |
| Score Calculation         | In Progress |
| Dept. Spec. Leaderboard   | Completed   |
| Leaderboard Preview       | Completed   |

### Cycle Goal Review 
Added more tests for the chart controller/dashboard view to cover all baseline tests. All tests now pass. Established a design template for the leaderbpard and used Catherine's staff page as a model to construct the front and back end for the Leaderboard. Ensured that routes were restricted to users with permission to access, and created tests to ensure the leaderboard's functionality. All tests are curently passing, tests cover baseline functionality. Fixed the bug issue with the pie charts on the department head dashboard, and adjusted them to fit the dashboard container. All added changes were pulled into pre-dev-integration, and will be added to developement soon. Created a formula for score to rank instructors in our gamification. Added a preview of the leaderboard to the department dashboard, and edited the leaderboard to be specific to the department of the viewer.

### Next Cycle Goals 
 * Add Leaderboard Rank to Instructor Dashboard
 * Add Badges to the Database for Ranking Gamification
 * Add Instructor Performance page between Report Export page and Staff/Leaderboard pages
 * Add Filtering, Links, Color Changes and Role-Switching to Dashboard
 * Add score calculation method to Performance Model

---

## Wednesday (7/10 - 7/11)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log710-711.png)

### Current Tasks 
  * #1: Create Dashboard Tests
  * #2: Refactor Chart Controller
  * #3: Seed Database for Testing
  * #4: Divide Leftover Features
  * #5: Leaderboard Design
  * #6: Add Pie Charts to Department Dashboard

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Dashboard Tests           | In Progress |
| Refactor Controller       | Completed   |
| Seed Database             | Completed   |
| Divide Features           | Completed   |
| Leaderboard Design        | In Progress |
| Add Pie Charts            | In Progress |

### Cycle Goal Review 
Updated database seeder and tested with Catherine's help. Completed and fixed all bugs in the chart controller. Created first 6 tests for the chart controller. Divided tasks among team members. Began planning for dashboard enhancement and leaderboard feature. Created a pie chart function for the chart controller. Debugged chart view.

### Next Cycle Goals 
 * Begin Leaderboard integration for Dashboard
 * Complete basic Dashboard testing
 * Begin Leaderboard Frontend
 * Finish Pie Chart

---

## Friday (7/5 - 7/9)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log75-79.png)

### Current Tasks 
  * #1: Create Dashboard Tests
  * #2: Refactor Chart Controller
  * #3: Seed Database for Testing

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Dashboard Tests           | In Progress |
| Refactor Controller       | In Progress |
| Seed Database             | Completed   |

### Cycle Goal Review 
Created seeders to allow more extensive Dashboard testing. Began refactoring the chart controller for a more efficient backend. Troubleshooted seeder issues and display issues with the new chart controller.

### Next Cycle Goals 
 * Divide leftover tasks
 * Begin new tasks
 * Continue testing and UI enhancement for Dashboard

---

## Thursday (7/4)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log74.png)

### Current Tasks 
  * #1: Remove Capacity Metric
  * #2: MVP Presentation Slides
  * #3: Test Feauture Integration
  * #4: Create Dashboard Tests
  * #5: MVP Presentation

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Remove Capacity Metric    | Completed   |
| MVP Presentation Slides   | Completed   |
| Test Feature Integration  | Completed   |
| Create Dashboard Tests    | In Progress |
| MVP Presentation          | Completed   |

### Cycle Goal Review 
Created the presentation slides for the MVP and tested the features once they were integretated in our development branches. Removed unnecessary capacity metric from the performance tables in the database. Completed MVP presentation.

### Next Cycle Goals 
 * Review MVP Feedback
 * Divide remaining features amongst group members and begin work on them
 * Continue testing and UI enhancement for Dashboard

---

## Wednesday (6/30 - 7/3)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log630-73.png)

### Current Tasks 
  * #1: Create Additional Visualizations for Department Dashboard
  * #2: Connect Database to Chart Visualizations
  * #3: Create Leaderboard

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Department Visualizations | Completed   |
| Connect Database to Charts| Completed   |
| Dashboard Leaderboard     | In Progess  |
| Create Dashboard Tests    | In Progress |


### Cycle Goal Review 
Updated the database to supply all of the necessary data for the dashboard to extract. Completed modifying the charts backend to allow for role based access and account for several user types by connecting to the database. Successfully implemented first draft of dashboard front and backend fucntionality, including role based access and redirection for certain user types.

### Next Cycle Goals 
 * Create tests for dashboard features
 * Get ready for MVP presentation
 * Get a leaderboard and ranking system added to the dashboard

---

## Friday (6/27 - 6/29)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log627-629.png)

### Current Tasks
  * #1: Create Additional Visualizations for Department Dashboard
  * #2: Connect Database to Chart Visualizations
  * #3: Remove Need for Custom Plugin for the Progress Bar
  * #4: Create Leaderboard
  * #5: Improve Container Structure for Dashboard

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Department Visualizations | In Progess  |
| Connect Database to Charts| In Progess  |
| Remove Progress Bar Plugin| Completed   |
| Dashboard Leaderboard     | In Progess  |
| Improve Containers        | Completed   |


### Cycle Goal Review 
Removed the need for a progress bar plugin by using a horizontally stacked bar chart instead of a regular horizontal bar chart. Added details to the new progress bar and began creating a solid container structure for the dashboard. Began creating visualizations and designing the final version of the dashboard, and created space for all of the data we wished to present, including the leaderboard.

### Next Cycle Goals 
 * Integrate user role based access for dashboard
 * Begin connecting database to the charts
 * Continue creating formatting for the leaderboard and lists
 * Create tests for dashboard features

---

## Wednesday (6/23 - 6/26)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log623-626.png)

### Current Tasks 
  * #1: Debug Chart.JS issues
  * #2: Continue Integration of Progress Bar Plugin
  * #3: Improve Dashboard Containers
  * #4: Change Environment to Linux for faster navigation

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Debug Chart.JS Issues     | Completed   |
| Progress Bar Plugin       | In Progress |
| Improve Containers        | In Progress |
| Move to Linux             | Completed   |


### Cycle Goal Review 
Began debugging Chart.JS and worked with Clement to move my environment to linux so that the pages loaded faster as the slow rendering was hindering my chart testing process. This environment change proved to be more complicated than expected, and we encountered hurdles such as long download and updating time, needing to reinstall docker, conflicting index.html files, reconfiguring php, and other extension confiuration related errors. This process took the majority of our project meeting. Debugging Cahrt.JS also took some time, as the plugin setup caused the charts to no longer appear. Upon reintegrating CHart.JS, I have decided using a plugin for the progress bar may be overkill and unnecessary, and my time would be more valuably spent working on all of the dashboard components.

### Next Cycle Goals 
  * Find a new way to display a progress bar without a custom plugin
  * Continue improving the container structure of the dashboard
  * Add leaderboard to dashboard frontend
  * Connect database to charts
  * Create charts for the department dashboard

---

## Friday (6/20 - 6/22)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log620-622.png)

### Current Tasks 
  * #1: Modify Chart Controller to Display a Relevant Chart Template
  * #2: Add Second Chart to Chart Controller
  * #3: Create Progress Bar Plugin
  * #4: Add Instructor Lists to Dashboard
  * #5: Create Container Structure for Dashboard

### Progress Update
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Modify Chart Controller    | Completed   |
| Dashboard Containers      | In Progess  |
| Add Chart Controller      | In Progress |
| Dashboard Instructor List | Completed   |
| Progress Bar Plugin       | In Progress |


### Cycle Goal Review 
Finished updating the chart controller to display a chart relevant to our data and began formatting the dashboard containers. Began adding a second chart to display the progress bar, and started to create a plugin for said chart. Added lists for service roles, course sections, and extra hours to the dashboard, and formatted them appropriately for the project vision. Creating the progress bar plugin proved more difficult than expected, and setting up Chart.JS proved to be a bit of a learning curve. I have not done much front end design for web pages either, so I am working to become more familiar with the container structue. More research was necessary this week than in other parts of the project, but progress is still evident.

### Next Cycle Goals 
  * Contiune configuring progress bar plugin
  * Finalize chart controller and connect to database
  * Finish creating containers for all dashboard items, and submit to group for review

---


## Wednesday (6/16 - 6/19)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log616-619.png)

### Current Tasks 
  * #1: Database Model Factory Setup
  * #2: Database Model Basic Testing
  * #3: Database Model Detailed Testing
  * #4: Research Chart Library
  * #5: Create Chart Controller

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Basic Model Testing       | Completed   |
| Detailed Model Testing    | In Progess  |
| Model Factory Setup       | Completed   |
| Research Chart Library    | Completed   |
| Create Chart Controller   | Completed   |


### Cycle Goal Review 
Finished creating Factories for the models and completed basic unit tests for each model. Continued detailed testing for database relations. Researched open source libraries for charts/visualizations and selected Laravel ChartJS. Created a chart controller file and began configuration.

### Next Cycle Goals 
  * Contiune adding relation tests to database models
  * Continue creating dashboard visualizations

---

## Friday (6/13 - 6/15)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log613-615.png)

### Current Tasks 
  * #1: Database Model Factory Setup
  * #2: Database Model Testing
  * #3: Discuss priorities for next Milestone
  * #3: Assigned next issues to group members
  * #4: Finalized and presented Mini-Presentaion 1

### Progress Update
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Assign next issues        | Completed   |
| Discuss priorities        | Completed   |
| Database Model Testing    | In Progess  |
| Model Factory Setup       | In Progess  |
| Mini-Presentation 1       | Completed   |

### Cycle Goal Review 
Comtinued creating Factories for the models and creating unit tests for each model. Discussed progress with the group and delivered presentation.

### Next Cycle Goals 
  * Complete Database Model Factory 
  * Complete Database Model Testing
  * Begin dashboard visualization research
  * Begin dashboard visualization implementation

---

## Wednesday (6/9 - 6/12)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log69-612.png)

### Current Tasks 
  * #1: Database Model Setup
  * #2: Database Table Setup
  * #3: Fix Personal Log
  * #4: Database Model Factory Setup
  * #5: Datbase Model Testing

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Database Table Setup      | Completed   |
| Database Model Testing    | In Progess  |
| Model Factory Setup       | In Progess  |
| Database Model Setup      | Completed   |
| Fix Personal Log          | Completed   |

### Cycle Goal Review 
Created the database tables in the migrations folder. Created models for the completed tables. Completed the requested restructuring of the personal log. Began creating Factories for the models and created the first of the unit tests for each model. Discussed progress with the group and prepared for the presentation.

### Next Cycle Goals 
  * Complete Database Model Factory 
  * Prepare for client review
  * Complete Database Model Testing
  * Complete and continue preparing for presentation
  * Divide tasks for next cycle

---


## Friday (6/6 - 6/8)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log66-68.png)

### Current Tasks 
  * #1: Design Presentation
  * #2: Docker setup
  * #3: Database Table setup

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Design Presentation       | Complete    |
| Docker setup              | Complete    |
| Database Table setup      | In Progress |

### Cycle Goal Review 
I completed the Design Presentation and Docker setup successfully. The Database Table setup is still in progress.

### Next Cycle Goals 
  * Complete Database Table setup
  * Prepare for client review
  * Continue improving Docker environment

---

## Wednesday (6/2 - 6/5)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log62-65.png)

### Current Tasks 
  * #1: Design Document
  * #2: Design Presentation
  * #3: Setup Laravel

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Design Document           | In Progress |
| Design Presentation       | In Progress |
| Setup Laravel             | Complete    |

### Cycle Goal Review 
I made significant progress on the Design Document and Presentation. Both are still in progress.

### Next Cycle Goals 
  * Complete Design Document
  * Finalize Design Presentation
  * Continue Environment Setup

---

## Friday (5/30 - 6/1)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log53061.png)

### Current Tasks 
  * #1: Design Document
  * #2: Design Presentation

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Design Document           | In Progress |
| Design Presentation       | In Progress |

### Cycle Goal Review 
I started working on the Design Document and Design Presentation.

### Next Cycle Goals 
  * Continue Design Document
  * Continue Design Presentation
  * Set up project environment

---

## Wednesday (5/26 - 5/29)

### Timesheet
Clockify report
![Clockify report](./clockifylogs/log526529.png)

### Current Tasks 
  * #1: Project Plan Document
  * #2: Project Plan Presentation
  * #3: Design Documentation

### Progress Update 
| **TASK/ISSUE #**          | **STATUS**  |
|---------------------------|-------------|
| Project Plan Document     | Complete    |
| Project Plan Presentation | Complete    |
| Design Documentation      | In Progress |

### Cycle Goal Review 
We completed the Project Plan Document and Presentation as planned. The design documentation has been started.

### Next Cycle Goals 
  * Finalize Design Documentation
  * Start Design Presentation

---

