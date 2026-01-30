# K-NECT - A Youth Governance System

> Digital Platform for Sangguniang Kabataan (SK) Management  
> Iriga City, Camarines Sur, Philippines

---

## 🎯 What is K-NECT?

**K-NECT** is a web-based system for managing **Sangguniang Kabataan (SK)** operations - the youth councils in the Philippines. It handles youth registration, events, attendance tracking, and analytics across all 36 barangays of Iriga City.

---

## ✨ Key Features

| Module                   | Description                                               |
| ------------------------ | --------------------------------------------------------- |
| **Member Management**    | Youth registration, profile verification, RFID assignment |
| **Event Management**     | Create events, schedule publishing, SMS notifications     |
| **Attendance System**    | RFID check-in/out, AM/PM sessions, export reports         |
| **Document Management**  | Upload/download files with visibility controls            |
| **Bulletin Board**       | Post announcements with categories and visibility         |
| **Analytics Dashboard**  | Demographics, event stats, barangay performance           |
| **SMS Notifications**    | Event broadcasts via TextBee API                          |
| **Google Calendar Sync** | Auto-sync events to Google Calendar                       |

---

## 👤 User Types

| Type            | Role                 | Access                                |
| --------------- | -------------------- | ------------------------------------- |
| **KK Member**   | Regular youth member | View profile, events, attendance      |
| **SK Official** | Barangay admin       | Manage members, events, attendance    |
| **Pederasyon**  | City-wide admin      | Full access, all barangays, analytics |

---

## 💻 Tech Stack

- **Backend:** PHP 8.2+, CodeIgniter 4.6, MySQL
- **Frontend:** Tailwind CSS 3.4
- **Integrations:** TextBee SMS, Google Calendar, PSGC API

---

## 🔐 Login Credentials

### Pederasyon Admin (Super Admin)

```
Username: PED_DessaMareLontayao
Password: Qwerty!1
```

### SK Official (Barangay Admin)

```
Username: SK_DessaMareLontayao
Password: Qwerty!1
Barangay: San Francisco Iriga
```

### KK Member (Regular User)

```
Username: SKPED_DessaMare
Password: Qwerty!1
```

### Database

```
Database: k-nect
Username: root
Password:
Port: 3306
```

---
