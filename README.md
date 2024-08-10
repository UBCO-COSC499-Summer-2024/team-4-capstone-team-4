# Team Members
- Angelina Dubrule
- Catherine Magoke
- Clement Abel-Nwachukwu
- Sumer Mann
- Kate Naychuk

# Project Information
### Project 1 - Department Management System

**Client**: CMPS Department at UBCO

**Overview**: Insight is a Department Management System (DMS) that aims to create a responsive web application to enhance the management of the CMPS department. The system will improve the visibility and efficiency of instructor activities, contributing to a higher quality of student experiences.

# Branch Workflow

- To view features in production: [master Branch](https://github.com/UBCO-COSC499-Summer-2024/team-4-capstone-team-4/blob/master)
- To view features in development: [development Branch](https://github.com/UBCO-COSC499-Summer-2024/team-4-capstone-team-4/blob/development)
- To view documentation (Plan, Design, etc.): [documentation Branch](https://github.com/UBCO-COSC499-Summer-2024/team-4-capstone-team-4/blob/documentation/docs)
- To view logs (Personal, Weekly, Commuincation, Dashboard): [logs Branch](https://github.com/UBCO-COSC499-Summer-2024/team-4-capstone-team-4/tree/logs/docs)

# User Guide
[Click here for user guide](https://github.com/UBCO-COSC499-Summer-2024/team-4-capstone-team-4/blob/pre-dev-integration/docs/final/UserGuide.md)

# Installation Steps

1. Install IDE of choice
2. Install Composer
3. Install Node and NPM 
4. Install PHP 8.3
5. Download Docker Desktop
6. Clone the repository to your device: `git clone [repo URL]`.
7. Open the cloned folder in your IDE (Visual Studio Code is recommended).
8. Create a .env file (similar to .env.example) and fill it with the necessary environment data.
9. Open the terminal and navigate to the cloned folder.
10. Run `composer install` in the terminal to install the vendor dependencies.
11. Run `npm install` in the terminal to install the node_modules.
12. Launch Docker.
13. Run `./vendor/bin/sail build` in the terminal to build the Docker containers.
14. Run `./vendor/bin/sail up -d` in the terminal to start the containers.
15. Run `./vendor/bin/sail artisan migrate` in the terminal to set up the database.
16. Run `npx vite` to start the Vite server.
17. Open your browser and navigate to  `localhost` to view the website.

