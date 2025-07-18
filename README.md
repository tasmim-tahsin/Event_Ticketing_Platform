# 🎟️ TicketKing - Event Ticketing Platform

<img src="https://i.ibb.co/bMbWqZks/Screenshot-2025-06-25-193524.png" alt="banner" border="0">

TicketKing is a comprehensive event ticketing platform that connects event organizers with attendees through a seamless, secure and user-friendly interface.

## 🌟 Features

### 🎭 Event Management
<div align="center">
  <img src="https://i.ibb.co/SwKJ3yzH/460shots-so.png"" width="800" alt="Event Management">
  <img src="https://i.ibb.co/xScDrmz5/532shots-so.png"" width="800" alt="Event Management">
  
  
</div>

- **Organizer Dashboard**: Create, edit, and manage events with rich details
- **Multi-Day Events**: Support for events spanning multiple days
- **QR Code Tickets**: Secure digital tickets with unique QR codes
- **Event Approval System**: Admin approval workflow for new events


## 👑 Admin Dashboard

<div align="center">
  <img src="https://i.ibb.co/sv0D8bNs/238shots-so.png" width="800" alt="Admin Dashboard Preview">
  <br><br>
</div>

TicketKing's powerful admin dashboard provides complete control over the platform with these features:

### 🔍 Key Features

| Feature | Description
|---------|------------|
| **User Management** | Ban/unban users, view activity logs, manage profiles
| **Event Moderation** | Approve/reject events, edit event details
| **Analytics** | View platform growth metrics and revenue reports
| **Content Control** | Manage all platform content and media

### 🛡️ Admin Privileges

```diff
+ Super Admin: Full system access including admin management
+ Admin: User/event moderation and content management
+ Moderator: Basic content approval capabilities
```

### 🧩 Components Breakdown

1. **Navigation Sidebar**
   - Role-based menu items
   - Quick access to all sections
   - Collapsible design

2. **Data Visualization**
   - Data in pie and bar chart
   - Quick access to all sections
   - Collapsible design

3. **Quick Actions**
   - 🚀 Approve pending events
   - ⚠️ Review flagged content
   - 📊 Generate reports


### 🔐 Security Features
- Role-based access control (RBAC)
- Session timeout after 30 minutes
- All actions logged for audit trails

<div align="center">
  <img src="https://i.ibb.co/ccRYf4kw/208shots-so.png" width="600" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin: 20px 0;">
  <p><em>Admin Dashboard Interface</em></p>
</div>
```

### 🎟️ Ticketing System
<div align="center">
  <img src="https://i.ibb.co/0VMQqFsW/263shots-so.png" alt="119shots-so" width="800">
  <img src="https://i.ibb.co/ynMML55w/514shots-so.png" width="800" alt="Ticketing System">
<!--   [https://i.ibb.co/YxkXq4S/119shots-so.png](https://ibb.co/xNjYRKb) https://i.ibb.co/Gvfw3bkB/532shots-so.png -->
</div>

- **Multiple Ticket Types**: VIP, Regular, Student options
- **Dynamic Pricing**: Set different prices for different ticket tiers
- **Quantity Management**: Real-time ticket availability tracking
- **PDF Tickets**: Automatically generated ticket PDFs with QR codes

### 🔐 User System
<div align="center">
  <img src="https://i.ibb.co/ycWY2Lns/45shots-so.png" width="800" alt="User System">
</div>

- **Role-Based Access**: Attendees, Organizers, and Admins
- **User Profiles**: Customizable profiles with social links
- **Account Security**: Secure authentication with bcrypt hashing
- **Ban System**: Admin controls for user management

### 💳 Payment Processing
<div align="center">
  <img src="https://i.ibb.co/Gf5zSTTf/989shots-so.png" width="800" alt="Payment Processing">
  <img src="https://i.ibb.co/Gvfw3bkB/532shots-so.png" width="800" alt="Payment Processing">
</div>

- **Multiple Payment Methods**: Bkash, Nagad, Visa, Mastercard
- **Order History**: Detailed purchase records for users
- **Email Receipts**: Automatic PDF ticket delivery via email

## 🛠️ Technology Stack

### Frontend
<div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white">
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black">
</div>

### Backend
<div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white">
</div>

### Libraries & Tools
<div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
  <img src="https://img.shields.io/badge/FPDF-000000?style=for-the-badge">
  <img src="https://img.shields.io/badge/PHPMailer-6C5CE7?style=for-the-badge">
  <img src="https://img.shields.io/badge/phpqrcode-4285F4?style=for-the-badge">
</div>

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Composer
- Web server (Apache/Nginx)

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/TicketKing.git
   cd TicketKing
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up environment:
   ```bash
   cp .env.example .env
   ```

4. Configure database in `.env`:
   ```ini
   DB_HOST=localhost
   DB_NAME=ticketking
   DB_USER=root
   DB_PASS=
   ```

5. Import database schema:
   ```bash
   mysql -u root -p ticketking < database.sql
   ```

6. Start development server:
   ```bash
   php -S localhost:8000
   ```

## 📸 Screenshots

| Feature | Preview |
|---------|---------|
| **Homepage** | <img src="https://i.ibb.co/n8P0CmR7/427shots-so.png" width="400"> |
| **Live Event Page** | <img src="https://i.ibb.co/ccnwbVKZ/306shots-so.png" width="400"> |
| **Events in Review page** | <img src="https://i.ibb.co/qSScdxm/528shots-so.png" width="400"> |
| **Homepage** | <img src="https://i.imgur.com/JxQZ3Fj.png" width="400"> |
| **Event Page** | <img src="https://i.imgur.com/5XwQYnR.png" width="400"> |
| **Admin Dashboard** | <img src="https://i.imgur.com/mVJZ4lD.png" width="400"> |
| **Homepage** | <img src="https://i.imgur.com/JxQZ3Fj.png" width="400"> |
| **Event Page** | <img src="https://i.imgur.com/5XwQYnR.png" width="400"> |
| **Admin Dashboard** | <img src="https://i.imgur.com/mVJZ4lD.png" width="400"> |

## 🤝 Contributing

We welcome contributions! Please follow these steps:
1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📜 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📧 Contact

Project Maintainer - [Md Tasmim Al Tahsin](mailto:tahsinniyan@gmail.com)  
GitHub Profile - [@tahsinniyan](https://github.com/tasmim-tahsin)

<p align="center">
  <img src="https://i.ibb.co/vCT0XVVB/830shots-so.png" width="400">
</p>
```

## Key Design Elements:

1. **Modern Header** with emoji and banner image
2. **Feature Sections** with descriptive images
3. **Technology Stack** with colorful badges
4. **Clean Installation Guide** with code blocks
5. **Screenshot Gallery** in table format
6. **Contributing Guidelines** with clear steps
7. **Contact Section** with maintainer info
8. **Consistent Color Scheme** throughout
9. **Responsive Layout** that works on all devices
10. **Visual Hierarchy** with proper spacing and headers
