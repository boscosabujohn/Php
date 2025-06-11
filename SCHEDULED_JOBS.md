# Scheduled Jobs for Facility Maintenance System

This document lists all scheduled/automated jobs (cron jobs, scheduled tasks, etc.) for the Facility Maintenance Request project. Add new jobs as needed to automate workflows, notifications, escalations, reporting, and more.

---

## 1. Escalation & Notification Workflow
- **Description:** Checks for delayed/non-completed requests, escalates them, and notifies supervisors/admins.
- **Command:**

```sh
curl -s http://localhost:8080/workflow/escalations > /dev/null 2>&1
```

- **Recommended Cron Entry:**

```
*/10 * * * * curl -s http://localhost:8080/workflow/escalations > /dev/null 2>&1
```

- **Notes:**
    - Adjust the URL/port as needed for your environment.
    - Can be run as often as required (e.g., every 10 minutes).

---

## 2. [Add New Job Here]
- **Description:**
- **Command:**
- **Recommended Cron Entry:**
- **Notes:**

---

### How to Add a New Job
1. Add a new section above with a clear title and description.
2. Specify the shell command to run (can be a curl, PHP CLI, artisan, etc.).
3. Provide a sample cron entry for easy copy-paste.
4. Add any notes or requirements.

---

**File location:** `/Users/sabujohnbosco/Php/SCHEDULED_JOBS.md`

Keep this file up to date as you add more automation to your project.
