# Project Proposal for Project 1

**Team Number:** 4

**Team Members:** Angelina Dubrule, Catherine Magoke, Sumer Mann, Kate Naychuk, Clement Abel-Nwachukwu

## Overview

### Project purpose or justification (UVP)

The Department Management System (DMS) is designed to streamline the management of the CMPS department, made possible through a responsive web application tailored to enhance the department's efficiency and transparency. By providing comprehensive insights into instructor activities, the system empowers department heads to optimize instructor allocation for teaching and service roles, ultimately elevating the quality of student experiences.
With the DMS, department heads gain a clear overview of all instructors' activities, enabling the identification of both high-performing and underperforming instructors through intuitive data visualizations and insightful reports. Meanwhile, instructors benefit from accessing their own performance metrics, allowing them to gauge their progress against department-wide targets.
Centralizing and visualizing instructor data, the DMS fills a crucial void in the department's infrastructure, establishing a standardized management system. Additionally, the system incentivizes instructors to excel through analytical visualizations, ranking mechanisms, and proactive notifications, fostering a culture of continuous improvement and excellence within the CMPS department.

### High-level project description and boundaries

A web application that will enable the CMPS department to efficiently manage and monitor instructor assignments and performance by presenting the relevant data in visualizations that will be displayed on a dashboard. The system will allow all user groups to sign on with secure access and ensure that they are assigned to the correct level of visibility and functionality of the application. Once inside, a user, depending on their role, will be able to view performance, add service roles, and log hours which will update the reports and visualizations returned to the screen. While mobile integration is beyond the scope of the system, the application will still be responsive to work on multiple-sized screens. The system will be built as a fully independent and unique system to maintain simplicity. The application will only be available in English.

### Measurable project objectives and related success criteria (scope of the project)

1. Develop and deploy a secure login mechanism by the project deadline, catering to Department heads, Instructors, and administrative staff. Ensure role-based access controls are integrated and validated through rigorous testing, guaranteeing appropriate data access restrictions based on user roles.
2. Implement a user-friendly interface for instructors to access their activity and performance metrics by the project deadline. Incorporate visualization components such as graphs and charts to display these metrics and facilitate comparisons with historical data.
3. Create a comprehensive dashboard for the department head, providing insights into the performance of all instructors by the project deadline. The dashboard will include key metrics, visualizations, and comparison components. Gather user feedback sessions to refine the dashboard and ensure it effectively meets and supports the department head’s decision-making process.
4. Develop an administrative interface for managing user accounts by the project deadline, enabling efficient addition, removal, and updates of user information by administrative staff. Validate usability through user testing to ensure seamless execution of these tasks without errors or delays.
5. Implement a robust system capable of efficiently handling increasing data volumes by the project deadline. Design a well-structured database containing all necessary entities for generating visuals and other data and conduct testing to ensure efficient data processing and visualizations within reasonable time frames.
6. Integrate a system for visualizing benchmarks and facilitating feedback between department heads and instructors by the project deadline. The system will subtly motivate instructors to set higher goals via gamified responses and constructive feedback mechanisms. Evaluate system effectiveness through user testing to gauge impact on goal-setting and feedback processes.

## Success Criteria

| Objective Number | Objective Name | Success Criteria |
| :-------------: | :-------------: | :-------------: |
| 1 | Secure Login | Role-based access controls are integrated, the user can log into the system with a username and password |
| 2 | User-Friendly Interface | Use of charts and graphs to accurately display data that is easy to understand, supporting uploads of instructor data and downloads of reports, max of 3 clicks for navigation, a tutorial for new users, be able to gain an understanding of performance from a glance|
| 3 | Comprehensive Dashboard for Department Head | Dashboard has visualizations of key performance metrics for each instructor and comparison for all instructors |
| 4 | Administrative Interface | The ability for adding, editing and removing accounts, supporting file uploads for creating service roles, the ability to edit instructor information |
| 5 | Sufficient Database | Database has capacity for all existing department data, data is stored in appropriate table/schema, database is connected to web application |
| 6 | Motivational Visualizations | Visualizations are available for all performance categories (SEI survey results, service hours, course sections taught, and enrollment), a minimum of one gamification has been implemented, the system has passed the acceptance test (meets requirements specified by the client for intended use) |

## Users, Usage Scenarios and High-Level Requirements

### Users Groups

#### Primary User 1. Instructors

### Proto-Persona - Dr. Samuel Chen

| Background| Pain Points | About |
| :-------------: | :-------------: | :-------------: |
| **Age:** 45, **Gender:** Male, **Location:** UBCO, **Marital Status:** Engaged, **Occupation:** Professor, **Education:** Ph.D. | Student Engagement, Research Funding, Outdated Technology | Dr. Samuel Chen is a professor who has been teaching at the university level for 5 years. He has a research background and is passionate about his subject. He balances his time between teaching, research, and administrative duties. |

TECH KNOWLEDGE

███████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

TIME/AVAILABILITY

██████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

GOALS/NEEDS

- Tracking and Performance
  - The user requires the ability to keep track of their personal service hours, service roles, and performance metrics.

- Visualizations
  - The system must provide a dashboard including visualizations (e.g., graphs, charts) to help the user easily understand their completed work and performance over a given period.

- Assigned TAs
  - The user requires the ability to view their assigned Teaching Assistants (TAs) and the hours associated with these TAs.

- Target Setting and Performance Comparison
  - General targets will be set at creation and will adapt based on existing performance data.
  - The user must be able to compare their performance to set targets and anonymously compare their work with other users of the same rank.

- Data Extraction
  - The user must be able to extract this performance data from the system in various formats (e.g., CSV, JSON, XML) for further analysis or reporting.

- Overall Benefits
  - The system must aim to create an incentive structure that encourages users to complete a greater amount of work at a higher standard of quality by providing clear performance tracking and goal-setting features

#### Primary User 2. Department Head

### Proto-Persona - Dr. Jennifer Rodriguez

| Background| Pain Points | About |
| :-------------: | :-------------: | :-------------: |
| **Age:** 52, **Gender:** Female, **Location:** UBCO, **Marital Status:** Married, **Occupation:** Head of CMPS Department, **Education:** Ph.D. | Resource Management, Time Management, Balancing Innovation and Tradition | Dr. Jennifer Rodriguez has extensive experience in both teaching and research within the field of computer science. As the head of the department, she is responsible for overseeing faculty, managing the department's budget, and setting strategic goals. She still teaches one advanced-level course and is involved in research, albeit to a lesser extent than before. |

TECH KNOWLEDGE

████████████████████████████████████████████░░░░░░░

TIME/AVAILABILITY

██████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

GOALS/NEEDS

- Creating and Assigning Service Roles
  - The user requires the ability to assign and remove service roles, which will consist of a title, description, sub-area, and monthly hours of service.
  - These service roles must have the ability to be assigned to any user of the “Instructor” ranking.

- Assigning Additional Service Hours
  - The user must be able to assign extra service hours to individuals or groups of “Instructors” based on additional completed services (e.g., attending faculty meetings, and special projects).

- Visualizations and Performance Data
  - The user requires the ability to view visualizations of overall department performance.
  - These visualizations must include filtering capabilities by area, date, instructor, and predetermined performance measures.

- Data Extraction
  - The user must be able to extract this performance data from the system in various formats (e.g., CSV, JSON, XML) for further analysis or reporting.

- Overall Benefits
  - The system must provide department heads with robust tools to manage and monitor service roles and performance, enhancing their ability to allocate resources effectively and track departmental progress.

#### Primary User 3. Administrator

### Proto-Persona - David Johnson

| Background| Pain Points | About |
| :-------------: | :-------------: | :-------------: |
| **Age:** 38, **Gender:** Male, **Location:** UBCO, **Marital Status:** Single, **Occupation:** University Administrator, **Education:** Master’s in Higher Education Administration | Change Management, Workload, Resource Constraints | David Johnson has been working in university administration for over a decade. He manages various administrative functions, including student services, budget planning, and policy implementation. David plays a key role in ensuring the smooth operation of the university and supports both academic and non-academic staff. |

TECH KNOWLEDGE

███████████████████████████░░░░░░░░░░░░░░░░░░░░░░░

TIME/AVAILABILITY

██████████████████████████████████████░░░░░░░░░░░░

GOALS/NEEDS

- User Account Management
  - This user must have the ability to create users with both department head and instructor statuses.
  - They need the ability to edit information associated with these user accounts and remove users if necessary.

- Performance and Service Hour Data Management
  - The user requires the ability to manage service role and extra hours data, including editing titles, hours, descriptions, and sub-areas.
  - They must be able to delete and add performance data, to ensure all data is accurate and up-to-date.

- Overall Benefits
  - The system must provide administrative managers with robust tools for managing user accounts and ensuring the accuracy of performance and service hour data, thus maintaining the integrity and reliability of departmental records.

### Envisioned Usage

#### Instructor

Professor Samuel Chen wants to improve their performance as an instructor, so they log into the system using their provided username and set password. They are greeted by a dashboard including visualizations and general data, which allows them to immediately understand their current performance quality compared to the rest of their department. Professor Chen requires more detailed feedback to gain a real understanding of his strengths and weaknesses, so he uses single-click navigation from the dashboard to check the hours associated with his current service roles and assigned teaching assistants, as well as enrollment, grade averages, and general feedback from SEI surveys from his recently taught courses. Satisfied with the information they obtained, Professor Chen signs out of the system. Next month, the professor logs into the system to check on their progress. They are pleased to see a fun popup with a positive message on the increase in their overall performance.

#### Department Head

Dr. Jennifer Rodriguez wants to check the performance of a particular instructor within her department. They log into the system using their secure username and password. They see a dashboard including the overall performance of their department. Data is available for all different sub-areas, and a list of high-performing and underperforming instructors is clearly provided. Using the interface, they look up the instructor in question and can immediately see what service roles they have been assigned and the hours and details associated with each role. In addition to the currently assigned service roles, Dr. Jennifer Rodriguez is able to quickly gain an understanding of the instructor's performance using the chart and graph visualizations the system provides. They extract this performance data and schedule a meeting with the professor in question to discuss their performance. Dr. Jennifer Rodriguez also needs to add a service role to several instructors’ workloads. They navigate to the role assignment page and are able to access and submit the role assignment easily since the form allows them to submit an existing CSV file containing the instructor names and the service roles they will be assigned. Before exiting the software, Dr. Jennifer Rodriguez navigates to another page which allows them to simultaneously assign extra hours to all instructors who attended the Faculty meeting this week. Finally, they log out of the system feeling content with how efficiently tier tasks were completed.

#### Administrator

David Johnson is an administrator for the system and must ensure all data is up to date. They log in using their admin username and password and instantly see a homepage where they can import and modify data, create a service role, or manage existing accounts. They import the performance data for the previous month by uploading a file (CSV), but are also given the option to enter it manually. They then create a service role by completing a form requesting the associated name, description, sub-area and expected monthly hours. Next, they increase the monthly hours associated with an existing service role from 20 to 30 and remove a service role that is no longer in practice. An instructor requires David to update his account information, so using another form, they update the instructor’s username and password. Finally, David adds a new account for a recently hired instructor and removes two accounts that belonged to instructors who will not be returning for the upcoming semester. David logs out of the system and is happy they were able to complete their daily tasks efficiently and without roadblocks.

## Requirements

### Functional Requirements

- User Authentication and Authorization
  - Provide a secure log-in functionality for the department head, instructors, and administrative staff.
  - Implement role-based access controls to ensure proper data access and security.

- Instructor dashboard
  - Displays the course information being taught by the respective professor.
  - Present visualizations on the performance analytics, benchmarks, and progress toward the set goals.
  - Displays the total service hours completed by the respective professor.
  - Displays assigned teaching assistants and their associated service hours.

- Department Head dashboard
  - Displays detailed performance metrics for all instructors in the department, covering both teaching and service roles.
  - Generating reports, and visualizations to assist in decision making.
  - Incorporating elements of gamification to display the reports in an interesting way to assist the head of the department in coming up with ways to motivate the instructors.

- Administrative Dashboard
  - Facilitates management of the user account and roles, including adding, removing, and updating instructor records.
  - Maintains and ensures the accuracy of the data from current and previous years.

- Data Flexibility
  - The system will be capable of seamlessly integrating data from various platforms, regardless of their structure
  - Additionally, the system will enable the export of data, which allows for the transfer of information from our system to other solutions or platforms.

### Non-functional Requirements

- Performance
  - Making sure that the system is responsive and takes minimum load time.  
  - Optimization for quick data processing and generation of visualizations.

- Scalability
  - Designing the database so that the future data can be accommodated easily.
  - Ensuring that the performance does not degrade when large amounts of data are added.

- Usability
  - Making an intuitive user interface that can be used even by first-timers.
  - Providing a user guide, and FAQs for enhancing the user experience.

- Security
  - Implement security features to protect sensitive data.

- Maintainability
  - Ensure that the system is easy to maintain and that regular updates can be released without any hassle.
  - Creating documentation for future developers and administrators.

- Time
   - Each key milestone will be completed by the milestone date
   - This project will be completed by the end of the summer semester

-  Resource
  - Ensuring that the system is operable in the available infrastructure.

- Quality
  - Appropriate testing of the system to ensure high-quality deliverables
  - Adhering to the best software development process to maintain code quality including CI/CD
  


### User Requirements

- Department Head
  - Securely log in to the system.
  - Access to up-to-date information on all instructors' performance, able to change, add, and remove the service roles and clocked hours.
  - Retrieve information from the previous years for analysis and comparison to make informed decisions.
  - Import and export data for comprehensive data management and backup respectively. 

- Instructor
  - Securely log in to the system.
  - Viewing the courses and services assigned.
  - Tracking the performance via a dashboard through graphs and visualization.
  - Compare the performance with already set benchmarks and adjust the goals accordingly.
  - Exporting their data for personal record-keeping.  

- Administrative Staff
  - Securely log in to the system.
  - Adding, updating, and removing the data for instructors.
  - Import and export data for backup, and integration purposes. 

### Technical Requirements

- System Architecture
  - Having a robust, scalable database to manage users, roles, service hours, performance data, and benchmarks.
  - Implementing a responsive web design that is well-connected with the back end ensuring that the website works on all kinds of systems.

- Technological Stack
  - Develop front end using web technologies like React.
  - Develop the back end using Laravel, and PHP for better server-side logic and API development.
  - Implement the Relational Database using MySQL for data storage.

- Deployment and Hosting
  - Using docker to containerize the system for consistent and reliable deployment means.

- Authentication and Authorization
  - The system must implement security mechanisms to control the access granted to different interfaces and functionality based on user roles.

- Data security
  - There must be measures in place to ensure that the database is secure and that private information is stored safely.

- Testing and Quality Assurance
  - The system must be tested thoroughly to ensure that each component works as intended and that the system works smoothly.
  - Implementing user acceptance testing to validate that the system meets user needs.
  - Planned testing methods include integration testing and unit testing with PHPUnit

- Documentation
  - There must be recorded documentation covering the system’s architecture, codebase, database, and APIs to ensure that future maintenance and updates can be completed.

## Tech Stack

### User Interaction

- Technology: Web Browser
- Justification: A web-based application ensures broad accessibility and platform independence. It is easily accessible from any desktop or laptop computer with an internet connection, which aligns with the project's requirement for desktop use.

### Frontend Development

- Technology: React
- Justification: React is a popular JavaScript library for building user interfaces. It offers a component-based architecture that facilitates the development of complex and interactive UIs. React's extensive ecosystem, community support, and performance optimizations make it an ideal choice for creating responsive web applications.

### Backend Development

- Technology: Laravel
- Justification: Laravel is a robust PHP framework known for its elegant syntax, powerful features, and extensive ecosystem. It simplifies common tasks such as routing, authentication, and caching, which speeds up development and ensures maintainability. Laravel's built-in ORM (Eloquent) facilitates database interactions, and its strong community support ensures access to a wealth of resources and plugins.

### Database

- Technology: MySQL
- Justification: MySQL, while a relational database, provides the necessary flexibility for accommodating changing service roles and metrics. Due to its widespread usage and reputation for stability and reliability, it will act as a strong foundation for storing data. However, a database adapter will be implemented to ensure the system can switch to PostgreSQL if needed, offering flexibility and risk mitigation against potential issues with a single database technology.

### Database Adapter

- Technology: Custom Database Adapter
- Justification: The database adapter pattern allows the application to switch between different database technologies with minimal code changes. This ensures that if the primary database (MySQL) becomes unsuitable or another database (PostgreSQL) offers better features, the switch can be made seamlessly.

### Visualization and Reporting

- Technology: Tableau
- Justification: Tableau is a leading data visualization tool that allows for the creation of interactive and shareable dashboards. It will be used to provide visual analytics and reporting for department heads and instructors, enabling them to track performance metrics and identify trends.

### Infrastructure and Deployment

- Technology: Docker
- Justification: Docker allows us to package our application and its dependencies into lightweight containers that ensure consistency across different environments and simplify deployment.

### Architecture

- Pattern: Model-View-Controller (MVC)
- Justification: MVC is a widely-used architectural pattern that separates the application into three interconnected components. This separation of concerns facilitates maintainability, scalability, and testability. Laravel, with its built-in support for MVC, makes it easier to implement and manage the application structure.

### Programming Languages

- Frontend: JavaScript (React)
  - Justification: JavaScript is the standard language for web development, and React is built with JavaScript. It ensures compatibility and seamless interaction within the frontend ecosystem.

- Backend: PHP (Laravel)
  - Justification: PHP is a widely-used server-side scripting language, and Laravel leverages PHP's capabilities to deliver a feature-rich backend framework that is secure, scalable, and easy to manage.

### APIs

- Technology: RESTful APIs
- Justification: RESTful APIs are a standard way to enable communication between the frontend and backend. They are stateless, scalable, and easy to implement. Using RESTful APIs ensures that the system can integrate with other services or modules in the future.

### Summary Table

|  Component | Technology | Justification |
| :-------------: | ------------- |------------- |
| User Interface | Web Browser| Ensures broad accessibility and platform independence. |
| Frontend | React | Component-based architecture, strong ecosystem, and performance optimizations. |
| Backend | Laravel | Elegant syntax, built-in ORM, extensive ecosystem, and strong community support. |
| Database | MySQL (Primary), PostgreSQL (Secondary) | Flexibility in handling relational data and risk mitigation through database adapter pattern. |
| Database Adapter | Custom Adapter | Allows seamless potential switching between MySQL, PostgreSQL. |
| Visualization | Tableau | Leading data visualization tool providing interactive and shareable dashboards. |
| Infrastructure | Docker | Packages applications and dependencies into lightweight containers for consistency and simplified deployment. |
| Architecture | MVC |Separation of concerns facilitates maintainability, scalability, and testability. |
| Languages | JavaScript (React), PHP (Laravel) | Standard languages for web development, ensuring compatibility and ease of integration. |
| APIs | RESTful APIs | Stateless, scalable, easy to implement, and ensures future integration capabilities. |

### Justification for Choices

- React and Laravel: Both technologies are current industry standards with large communities, ensuring long-term support and availability of resources.
- MySQL with Adapter: Chosen for its flexibility and scalability. The adapter ensures that switching to PostgreSQL is straightforward, reducing the risk associated with database technology choices.
- Tableau: Provides robust visualization and reporting capabilities, essential for tracking performance metrics and making data-driven decisions.
- MVC Architecture: Ensures separation of concerns, facilitating maintainability, scalability, and testability.
- Docker: Simplifies deployment and ensures consistency across different environments, reducing deployment-related risks.
- RESTful APIs: Provide a standard method of communication between frontend and backend, ensuring scalability and ease of integration with other systems.

### Final Justification

We chose Tableau as our visualization tool because it enables us to create visualization templates without the need to reinvent the wheel or resort to additional software for programmatically generating graphs, which could potentially prolong development time. This expedited approach enhances efficiency by utilizing Tableau's robust visualization capabilities. Additionally, we opted for the MVC architecture due to its simplicity and effectiveness in organizing project components. React was selected for the front end for its clean, modular structure and extensive community support, which helps with rapid development and maintenance. Meanwhile, Laravel was chosen for the backend to bolster maintainability with its elegant syntax and built-in features. These decisions collectively contribute to a streamlined and efficient development process that aligns closely with our project's objectives.

## High-level risks

### Risk: Unauthorized access to the system

- Potential Impact:
  - Users may see sensitive or private information and be able to copy or manipulate it.
  - Users can edit data which will affect reporting.
- Possible Mitigation Options:
  - Remove the ability to register for an account. Only an authorized admin can add users.
  - Only admin actions handle database interaction (creation, deletion, etc).
  - Ensuring database vulnerabilities such as injection are handled correctly.

### Risk: Data Accuracy

- Potential Impact:
  - Inaccurate data entry may lead to incorrect reporting and analysis.
- Possible Mitigation Options
  - Implement validation checks, regular data audits, and provide user feedback mechanisms.

### Risk: Database Technology

- Potential Impact:
  - The chosen database might become unsuitable over time.
- Possible Mitigation Options:
  - Implement a database adapter pattern to allow easy switching between different database technologies.

### Risk: User Adoption

- Potential Impact:
  - Faculty and staff may be cautious about using a new system, possibly even refusing due to extra workload or having to learn a new interface.
- Possible Mitigation Options
  - Minimize the interaction needed with the interface. Just display data, no need for excess input.
  - Conduct thorough user training and provide comprehensive user guides and support.

### Risk: Scalability and Flexibility

- Potential Impact:
  - The addition of a large amount of new staff may affect the performance or functionality of the existing system.
  - The addition of new service roles or user types may disrupt database design.
- Possible Mitigation Options:
  - Thinking ahead when creating the database schema to allow large amounts of data in the future.
  - Using modular coding practices for maintainability and testing purposes.

### Risk: System Longevity

- Potential Impact:
  - The system may become out of date/obsolete or require new implementations for the functionality to work correctly.
- Possible Mitigation Options:
  - Using a modular design system to ensure that maintenance and testing is simplified.
  - Using industry standards, benefit the technical team tasks to maintain the system.
  - Creating comprehensive documentation for the system’s design, code base, database, and basic operations/functionality.

### Risk: Scope Creep / Resource Constraints

- Potential Impact:
  - The limited time assigned to the creation of the system, may affect the completeness or delivery of the core functionalities required.
  - The quality of the software may be affected due to limitations with the budget, knowledge, and allocated resources.
- Possible Mitigation Options:
  - Creating a detailed plan and establishing clear milestones or goals to ensure efficient use of time and resources.
  - Prioritizing core functionalities and ensuring they work as intended before continuing on to excess features.
  - Clearly define project scope, implement a change control process, and manage stakeholder expectations.

### Risk: Visualization

- Potential Impact:
  - Complexity in integrating Tableau for reporting.
- Possible Mitigation Options:
  - Allocate dedicated resources for Tableau integration and ensure proper training.

## Assumptions and constraints

### Assumptions

- Users will primarily consist of department heads, instructors, and staff
- The system will be mainly used on desktop computers in the browser
- The system will not need to handle a high amount of data at the time of delivery
- The university will be able to understand and maintain the technical infrastructure used to create the system
- The system complies with institutional policies

### Constraints

- The system must be functional and complete by the end of the summer term
- The system is being built by a small team of student developers with limited knowledge and access to advanced tools and skills
- The users will have varying levels of digital literacy

## Summary Milestone Schedule

Identify the major milestones in your solution and align them to the course timeline. In particular, what will you have ready to present and/or submit for the following deadlines? List the anticipated features you will have for each milestone, and we will help you scope things out in advance and along the way. Use the table below and just fill in the appropriate text to describe what you expect to submit for each deliverable. Use the placeholder text in there to guide you on the expected length of the deliverable descriptions. You may also use bullet points to clearly identify the features associated with each milestone (which means your table will be lengthier, but that’s okay).  The dates are correct for the milestones.  

|  Milestone  | Deliverable |
| :-------------: | ------------- |
|  May 29th  | A markdown file with our project plan for the Department Management System (DMS)|
| May 29th  | A short video presentation describing the user groups and requirements for the DMS |
| June 5th  | Design Submission: This will be a document that contains the design of the DMS and the system architecture plan. It will include at least 10 use cases, DFD diagrams (levels 0 and 1), and the general user interface design with mock-ups showing how the user will interact with the system. Initial tests should pass |
| June 5th  |  A short video presentation describing the design for our DMS |
| June 14th  | Mini-Presentations: This presentation will show the envisioned usage of our system. We will demonstrate at least 3 features for this milestone including user log-in with credentials and permissions, at least one feature for the instructor dashboard, one feature for the department head dashboard and one feature for the administrator dashboard.|
| July 5th  | MVP Mini-Presentations: Proper authentication system to allow specific users to log in, log out, and register. A dashboard, which changes based on the user, that displays the performance of instructors in various visualizations. The ability to assign service roles and display the relevant hours associated.|
| July 19th  | Peer testing and feedback: Gamification features will be implemented and tested as well as admin functionalities. The Department Head will be able to import data and extract data from reports. Code reviews, integration, and regression testing will be continued.|
| August 2nd  | Test-O-Rama: A working full-scale system and user testing with everyone |
| August 9th  |  Final project submission and group presentations |

## Teamwork Planning and Anticipated Hurdles

|  Category  | Angelina Dubrule | Catherine Magoke | Clement Abel-Nwachukwu | Sumer Mann | Kate Naychuk | 
| ------------- | ------------- | ------------- | ------------- | ------------- | ------------- |
|  **Experience**  | Web application frontend and backend development, limited PHP experience,  using Docker, software engineering project experience, and Git, GitHub, and GitHub Projects | Built functional Reddit clone using HTML, CSS and JavaScript for frontend and PHP, MySQL for backend and database. Built a functional shopping website, mainly focusing on database functionality - used JSP and MySQLi. Worked with GitHub for multiple projects. | Worked as a full stack developer professionally using mainly Python, NodeJS, PHP and React. Built an e-commerce website with Laravel. Worked with MySQL(i), Firebase, Mongo and Postgres Database technologies. | Web-based applications development front end, and back end using Python, Java, Javascript, and CSS. Experience in using Docker, GitHub, MySQL, and Tableau | Worked with HTML, CSS, JavaScript, Java, PHP, and MySQL for school projects making various systems including a shopping and forum application. Personal experience with React, Node.js, and Mongo | 
|  **Good At**  | Setting goals, organization, and learning and adapting to new languages and environments | learning quickly, prioritizing, troubleshooting, and MySQL  | Full Stack Development. Code maintenance and upkeep. | Documentation, learning new languages quickly and flexibly to various environments | Front end (design, implementation, etc), SQL, documentation, presentations/reporting |
|  **Expect to learn**  | Improve PHP understanding and capabilities, enhance Docker skills, learning React, new development strategies and troubleshooting styles | React framework, Lavarel, how to set up Docker container, how to set up unit tests to run with GitHub Actions, MongoDB | I hope to get a deeper understanding of Laravel. Additionally, I hope to improve my knowledge and understanding of Docker and its microservices. I also want to improve my design architecture skills. | Learning React, Laravel, and setting up testing for the system development, improving docker skills | Using Laravel, setting up docker, writing good unit tests, code review |

## Division of Responsibilities

|  Category of Work/Features  | Angelina Dubrule | Catherine Magoke | Clement Abel-Nwachukwu | Sumer Mann | Kate Naychuk | 
| ------------- | :-------------: | :-------------: | :-------------: | :-------------: | :-------------: |
|  **Project Management: Kanban Board Maintenance**  | ✔️  |  | ✔️ |  |  |
|  **System Architecture Design**  | ✔️ | ✔️ | ✔️ | ✔️ |✔️|
|  **User Interface Design**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Instructor Dashboard**  | ✔️ | ✔️ |  |  |  |
|  **Administrator Dashboard**  |  | ✔️ |  |  | ✔️ |
|  **Department Head Dashboard**  |  |  | ✔️ | ✔️ |  |
|  **Module Manager**  |  |  | ✔️ |  |  |
|  **Tableau Visualizations**  |  | ✔️ | ✔️ | ✔️ |  |
|  **Tableau Integration**  | ✔️ |  | ✔️ | ✔️ |  |
|  **User Profile**  |  |  |  | ✔️ | ✔️ |
|  **Usage Guide**  |  | ✔️ |  |  | ✔️ |
|  **Account Settings**  |  |  |  | ✔️ | ✔️ |
|  **Service Role Component**  | ✔️ | ✔️ |  |  |  |
|  **Leaderboard Component**  |  |  |  | ✔️ | ✔️ |
|  **Instructor List Component**  |  |  |  |  | ✔️ |
|  **Data Synchronization**  | ✔️ |  | ✔️ |  |  |
|  **Data Import**  | ✔️ |  | ✔️ |  |  |
|  **Data Export**  |  |  | ✔️ |  | ✔️ |
|  **Login Authentication**  | ✔️ |  |  ✔️ |  |  |
|  **Registration Authentication**  |  |  | ✔️ | ✔️ |  | 
|  **Reset Password Authentication**  |  |  | ✔️ | ✔️ |  | 
|  **Weekly Log**  |  | ✔️ |  |  | ✔️ |
|  **Communications Log**  |  |  |  | ✔️ | ✔️ |
|  **Gamification**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ | 
|  **Database setup**  | ✔️ |  |  | ✔️ | ✔️ |
|  **Docker Container**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Docker Microservices**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Testing**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Documentation**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Presentation Preparation**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Design Video Creation**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Design Video Editing**  | ✔️ |  | ✔️ |  |  |
|  **Design Report**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Final Video Creation**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Final Video Editing** | ✔️ |  | ✔️ |  | ✔️ |
|  **Final Team Report**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |
|  **Final Individual Report**  | ✔️ | ✔️ | ✔️ | ✔️ | ✔️ |

We tried to evenly distribute the workload amongst all team members while allowing the person with the most expertise (i.e. Clement) to have the most tasks. Each team member has a minimum of 20 tasks assigned to them. Some tasks will involve all team members such as documentation, reporting, testing, etc, because these tasks will more or less require input from each team member. We made sure that each task had a minimum of 2 to 3 people based on the anticipated difficulty and each task was assigned based on preference as well as expertise.
